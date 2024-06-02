<?php
include "session.php";
include "db.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['pin']) && isset($_SESSION['transaction'])) {
        $pin = $_POST['pin'];
        $account_id = $_SESSION['transaction']['account_id'];
        $type = $_SESSION['transaction']['type'];
        $amount = $_SESSION['transaction']['amount'];

        // For transfer type
        if ($type == 'transfer') {
            // $recipient_account_id = $transaction['recipient_account_id'];
            $recipient_account = $_SESSION['transaction']['recipient_account'];
            $recipient_name = $_SESSION['transaction']['recipient_name'];
            $bank = $_SESSION['transaction']['bank'];
        }

        // Retrieve the PIN associated with the user's account
        $stmt = $conn->prepare("SELECT pin, no_rekening FROM rekening WHERE id = ?");
        $stmt->bind_param("i", $account_id);
        $stmt->execute();
        $stmt->bind_result($stored_pin, $account_number);
        $stmt->fetch();
        $stmt->close();

        if (strval($pin) !== strval($stored_pin)) {
            header("Location: index.php?status=error&message=Invalid+PIN");
            exit();
        }

        if ($type == 'deposit') {
            $sql = "UPDATE rekening SET saldo = saldo + ? WHERE id = ?";
            $transaction_type = 'Deposit';
            $description = 'Deposit into account';

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("di", $amount, $account_id);
        } elseif ($type == 'withdrawal') {
            $sql = "UPDATE rekening SET saldo = saldo - ? WHERE id = ? AND saldo >= ?";
            $transaction_type = 'Withdrawal';
            $description = 'Withdraw cash';

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("dii", $amount, $account_id, $amount);
        } elseif ($type == 'transfer') {
            // Start transaction
            $conn->begin_transaction();
            try {
                // Deduct amount from sender's account
                $sql = "UPDATE rekening SET saldo = saldo - ? WHERE id = ? AND saldo >= ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("dii", $amount, $account_id, $amount);
                if (!$stmt->execute()) {
                    throw new Exception("Failed to deduct amount from sender's account");
                }
                $stmt->close();

                // Add amount to recipient's account
                $sql = "UPDATE rekening SET saldo = saldo + ? WHERE no_rekening = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ds", $amount, $recipient_account);
                if (!$stmt->execute()) {
                    throw new Exception("Failed to add amount to recipient's account");
                }
                $stmt->close();

                // Retrieve recipient's account id
                $sql = "SELECT id FROM rekening WHERE no_rekening = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $recipient_account);
                $stmt->execute();
                $stmt->bind_result($recipient_account_id);
                $stmt->fetch();
                $stmt->close();

                // Insert transaction record for sender
                $sql = "INSERT INTO transaksi (no_rekening, tipe_transaksi, jumlah, deskripsi) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $sender_description = "Transfer to $recipient_name ($bank)";
                $transaction_amount = -$amount;
                $stmt->bind_param("isds", $account_id, $type, $transaction_amount, $sender_description);
                if (!$stmt->execute()) {
                    throw new Exception("Failed to record sender's transaction");
                }
                $stmt->close();

                // Insert transaction record for recipient
                $sql = "INSERT INTO transaksi (no_rekening, tipe_transaksi, jumlah, deskripsi) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $recipient_description = "Transfer from $account_number";
                $transaction_amount = $amount;
                $stmt->bind_param("isds", $recipient_account_id, $type, $transaction_amount, $recipient_description);
                if (!$stmt->execute()) {
                    throw new Exception("Failed to record recipient's transaction");
                }
                $stmt->close();

                // Commit transaction
                $conn->commit();

                header("Location: index.php?status=success&message=Transfer+successful");
            } catch (Exception $e) {
                $conn->rollback();
                header("Location: index.php?status=error&message=" . urlencode($e->getMessage()));
            }
            exit();
        }

        // Execute the balance update transaction
        if ($stmt->execute()) {
            // Retrieve the highest transaksi_id
            $stmt->close();
            $sql = "SELECT MAX(transaksi_id) FROM transaksi";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $stmt->bind_result($max_transaksi_id);
            $stmt->fetch();
            $stmt->close();
            $new_transaksi_id = $max_transaksi_id ? $max_transaksi_id + 1 : 1;

            // Insert the transaction record into the transaksi table
            $transaction_amount = ($type == 'deposit') ? $amount : -$amount;
            $stmt = $conn->prepare("INSERT INTO transaksi (transaksi_id, no_rekening, tipe_transaksi, jumlah, deskripsi) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iisds", $new_transaksi_id, $account_id, $transaction_type, $transaction_amount, $description);

            if ($stmt->execute()) {
                // Store transaction details in the session for the receipt page
                $_SESSION['transaction']['id'] = $new_transaksi_id;
                $_SESSION['transaction']['description'] = $description;
                $_SESSION['transaction']['amount'] = $amount;
                $_SESSION['transaction']['account_id'] = $account_id;

                header("Location: index.php?status=success&message=" . $transaction_type . "+successful");
            } else {
                header("Location: index.php?status=error&message=Failed+to+record+transaction");
            }
            $stmt->close();
        } else {
            header("Location: index.php?status=error&message=Failed+to+process+" . $transaction_type);
        }
    } else {
        header("Location: index.php?status=error&message=Missing+parameters");
    }
} else {
    header("Location: index.php?status=error&message=Invalid+request+method");
}

$conn->close();
