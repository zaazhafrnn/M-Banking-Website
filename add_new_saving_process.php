<?php
include 'session.php';
include 'db.php';
include 'get_account.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $action = $_POST['action'];
    $pin = $_POST['pin'];
    $account_id = $_POST['account_id'];

    // Validate PIN
    $stmt = $conn->prepare("SELECT pin FROM rekening WHERE user_id = ? AND id = ?");
    $stmt->bind_param("ii", $user_id, $account_id);
    $stmt->execute();
    $stmt->bind_result($stored_pin);
    $stmt->fetch();
    $stmt->close();

    if ($stored_pin != $pin) {
        header("Location: cardsaving.php?status=error&message=Invalid+PIN");
        exit();
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        if ($action == 'new') {
            $title = $_POST['title'];
            $budget_goal = $_POST['budget_goal'];
            $initial_balance = isset($_POST['initial_balance']) ? $_POST['initial_balance'] : 0;

            // Validate inputs
            if (empty($title) || empty($budget_goal) || (!empty($initial_balance) && empty($account_id))) {
                throw new Exception("Invalid input");
            }

            // Insert new saving record
            $stmt = $conn->prepare("INSERT INTO tabungan (user_id, title, budget_goal, current_balance) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isdi", $user_id, $title, $budget_goal, $initial_balance);
            $stmt->execute();
            $saving_id = $stmt->insert_id;
            $stmt->close();

            // Deduct initial balance from selected account
            if (!empty($initial_balance)) {
                $stmt = $conn->prepare("UPDATE rekening SET saldo = saldo - ? WHERE id = ? AND saldo >= ?");
                $stmt->bind_param("dii", $initial_balance, $account_id, $initial_balance);
                if (!$stmt->execute()) {
                    throw new Exception("Failed to deduct initial balance from account");
                }
                $stmt->close();

                // Insert transaction record for the initial balance deduction
                $stmt = $conn->prepare("INSERT INTO transaksi (no_rekening, tipe_transaksi, jumlah, deskripsi) VALUES (?, ?, ?, ?)");
                $transaction_type = "Saving Initial Balance";
                $description = "Initial balance for saving: $title";
                $negative_balance = -abs($initial_balance);
                $stmt->bind_param("isds", $account_id, $transaction_type, $negative_balance, $description);
                if (!$stmt->execute()) {
                    throw new Exception("Failed to record initial balance transaction");
                }
                $stmt->close();
            }
        } elseif ($action == 'add_balance') {
            $saving_id = $_POST['saving_id'];
            $amount = $_POST['amount'];

            // Validate inputs
            if (empty($saving_id) || empty($amount) || empty($account_id)) {
                throw new Exception("Invalid input");
            }

            // Deduct balance from selected account
            $stmt = $conn->prepare("UPDATE rekening SET saldo = saldo - ? WHERE id = ? AND saldo >= ?");
            $stmt->bind_param("dii", $amount, $account_id, $amount);
            if (!$stmt->execute()) {
                throw new Exception("Failed to deduct balance from account");
            }
            $stmt->close();

            // Update saving balance
            $stmt = $conn->prepare("UPDATE tabungan SET current_balance = current_balance + ? WHERE id = ?");
            $stmt->bind_param("di", $amount, $saving_id);
            if (!$stmt->execute()) {
                throw new Exception("Failed to update saving balance");
            }
            $stmt->close();

            // Insert transaction record for the balance deduction
            $stmt = $conn->prepare("INSERT INTO transaksi (no_rekening, tipe_transaksi, jumlah, deskripsi) VALUES (?, ?, ?, ?)");
            $transaction_type = "Add Balance to Saving";
            $description = "Added balance to saving ID: $saving_id";
            $negative_balance = -abs($amount);
            $stmt->bind_param("isds", $account_id, $transaction_type, $negative_balance, $description);
            if (!$stmt->execute()) {
                throw new Exception("Failed to record balance transaction");
            }
            $stmt->close();
        } elseif ($action == 'delete') {
            $saving_id = $_POST['saving_id'];
        
            // Validate inputs
            if (empty($saving_id)) {
                throw new Exception("Invalid input");
            }
        
            // Delete saving record
            $stmt = $conn->prepare("DELETE FROM tabungan WHERE id = ?");
            $stmt->bind_param("i", $saving_id);
            if (!$stmt->execute()) {
                throw new Exception("Failed to delete saving");
            }
            $stmt->close();
        
        } else {
            throw new Exception("Invalid action");
        }

        // Commit transaction
        $conn->commit();

        header("Location: cardsaving.php?status=success&message=Successful+adding+balance+into+saving.");
    } catch (Exception $e) {
        $conn->rollback();
        header("Location: cardsaving.php?status=error&message=" . urlencode($e->getMessage()));
    }

    exit();
} else {
    header("Location: cardsaving.php?status=error&message=Invalid+request+method");
}

$conn->close();
?>