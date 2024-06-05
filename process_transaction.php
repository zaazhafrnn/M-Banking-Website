<?php
include "session.php";
include "db.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");

// Function to get the next available transaction ID
function getNextTransactionId($conn)
{
    $sql = "SELECT MAX(transaksi_id) FROM transaksi";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->bind_result($max_transaksi_id);
    $stmt->fetch();
    $stmt->close();
    return $max_transaksi_id ? $max_transaksi_id + 1 : 1;
}

// Function to insert transaction record into the database
function insertTransaction($conn, $transaksi_id, $account_id, $type, $amount, $description, $is_outgoing = false)
{
    // Determine the transaction type and amount
    switch ($type) {
        case 'deposit':
            $transaction_type = 'Deposit';
            $transaction_amount = abs($amount);
            break;
        case 'withdrawal':
            $transaction_type = 'Withdrawal';
            $transaction_amount = -abs($amount);
            break;
        case 'transfer':
            $transaction_type = $is_outgoing ? 'Transfer Out' : 'Transfer In';
            $transaction_amount = $is_outgoing ? -abs($amount) : abs($amount);
            break;
        default:
            return false;
    }

    // Insert the transaction record into the transaksi table
    $stmt = $conn->prepare("INSERT INTO transaksi (transaksi_id, no_rekening, tipe_transaksi, jumlah, deskripsi) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisds", $transaksi_id, $account_id, $transaction_type, $transaction_amount, $description);

    return $stmt->execute();
}

// Function to update account balance
function updateBalance($conn, $account_id, $amount, $is_credit)
{
    $sql = $is_credit ? "UPDATE rekening SET saldo = saldo + ? WHERE id = ?" : "UPDATE rekening SET saldo = saldo - ? WHERE id = ? AND saldo >= ?";
    $stmt = $conn->prepare($sql);

    if ($is_credit) {
        $stmt->bind_param("di", $amount, $account_id);
    } else {
        $stmt->bind_param("dii", $amount, $account_id, $amount);
    }

    return $stmt->execute();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['pin']) && isset($_SESSION['transaction'])) {
        $pin = $_POST['pin'];
        $account_id = $_SESSION['transaction']['account_id'];
        $type = $_SESSION['transaction']['type'];
        $amount = $_SESSION['transaction']['amount'];

        // For transfer type
        $recipient_account = $type == 'transfer' ? $_SESSION['transaction']['recipient_account'] : null;
        $bank = $type == 'transfer' ? $_SESSION['transaction']['bank'] : null;

        // Retrieve the PIN, account number, bank, and full name of the sender's account
        $stmt = $conn->prepare("SELECT r.pin, r.no_rekening, r.bank, u.nama_depan, u.nama_belakang FROM rekening r JOIN users u ON r.user_id = u.id WHERE r.id = ?");
        $stmt->bind_param("i", $account_id);
        $stmt->execute();
        $stmt->bind_result($stored_pin, $sender_account_number, $sender_bank, $sender_first_name, $sender_last_name);
        $stmt->fetch();
        $stmt->close();

        $sender_name = trim("$sender_first_name $sender_last_name");

        if (strval($pin) !== strval($stored_pin)) {
            header("Location: index.php?status=error&message=Invalid+PIN");
            exit();
        }

        // Start transaction for all types to ensure atomicity
        $conn->begin_transaction();

        try {
            // Get the next available transaction ID
            $new_transaksi_id = getNextTransactionId($conn);

            switch ($type) {
                case 'deposit':
                    $description = 'Deposit into account';
                    if (
                        !updateBalance($conn, $account_id, $amount, true) ||
                        !insertTransaction($conn, $new_transaksi_id, $account_id, $type, $amount, $description)
                    ) {
                        throw new Exception("Failed to process deposit");
                    }
                    break;

                case 'withdrawal':
                    $description = 'Withdraw cash';
                    if (
                        !updateBalance($conn, $account_id, $amount, false) ||
                        !insertTransaction($conn, $new_transaksi_id, $account_id, $type, $amount, $description)
                    ) {
                        throw new Exception("Failed to process withdrawal");
                    }
                    break;

                case 'transfer':
                    // Deduct amount from sender's account
                    if (!updateBalance($conn, $account_id, $amount, false)) {
                        throw new Exception("Insufficient funds for transfer");
                    }

                    // Retrieve recipient's account details
                    $sql = "SELECT r.id, r.no_rekening, r.bank, r.saldo, u.nama_depan, u.nama_belakang 
                           FROM rekening r JOIN users u ON r.user_id = u.id 
                           WHERE r.no_rekening = ? AND r.bank = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ss", $recipient_account, $bank);
                    $stmt->execute();
                    $stmt->bind_result($recipient_account_id, $recipient_account_number, $recipient_bank, $recipient_balance, $recipient_first_name, $recipient_last_name);
                    $stmt->fetch();
                    $stmt->close();

                    $recipient_name = trim("$recipient_first_name $recipient_last_name");

                    if (!$recipient_account_id) {
                        throw new Exception("Recipient account not found");
                    }

                    // Update recipient's balance
                    $sql = "UPDATE rekening SET saldo = saldo + ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("di", $amount, $recipient_account_id);
                    if (!$stmt->execute()) {
                        throw new Exception("Failed to add amount to recipient's account");
                    }
                    $stmt->close();

                    // Create descriptive transaction messages
                    $sender_description = "Transfer to $recipient_name ($bank - $recipient_account_number)";
                    $recipient_description = "Transfer from $sender_name ($sender_bank - $sender_account_number)";

                    // Insert transaction records for both parties with the same transaksi_id
                    if (
                        !insertTransaction($conn, $new_transaksi_id, $account_id, $type, $amount, $sender_description, true) ||
                        !insertTransaction($conn, $new_transaksi_id, $recipient_account_id, $type, $amount, $recipient_description, false)
                    ) {
                        throw new Exception("Failed to record transfer transactions");
                    }
                    break;

                default:
                    throw new Exception("Invalid transaction type");
            }

            // Commit transaction
            $conn->commit();

            // Store transaction details in the session for the receipt page
            $_SESSION['transaction']['id'] = $new_transaksi_id;
            $_SESSION['transaction']['description'] = $type == 'transfer' ? $sender_description : $description;
            $_SESSION['transaction']['amount'] = $amount;
            $_SESSION['transaction']['account_id'] = $account_id;

            header("Location: index.php?status=success&message=" . ucfirst($type) . "+successful&transaksi_id=$new_transaksi_id");
        } catch (Exception $e) {
            $conn->rollback();
            header("Location: index.php?status=error&message=" . urlencode($e->getMessage()));
        }

        exit();
    } else {
        header("Location: index.php?status=error&message=Missing+parameters");
    }
} else {
    header("Location: index.php?status=error&message=Invalid+request+method");
}

$conn->close();
