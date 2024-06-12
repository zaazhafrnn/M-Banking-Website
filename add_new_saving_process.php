<?php
include 'session.php';
include 'db.php';
include 'get_account.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $budget_goal = $_POST['budget_goal'];
    $initial_balance = isset($_POST['initial_balance']) ? $_POST['initial_balance'] : 0;
    $account_id = isset($_POST['account']) ? $_POST['account'] : null;
    $pin = $_POST['pin'];

    // Validate inputs
    if (empty($title) || empty($budget_goal) || (!empty($initial_balance) && empty($account_id))) {
        header("Location: add_new_saving.php?status=error&message=Invalid+input");
        exit();
    }

    // Validate PIN
    $stmt = $conn->prepare("SELECT pin FROM rekening WHERE user_id = ? AND id = ?");
    $stmt->bind_param("ii", $user_id, $account_id);
    $stmt->execute();
    $stmt->bind_result($stored_pin);
    $stmt->fetch();
    $stmt->close();

    if ($stored_pin != $pin) {
        header("Location: add_new_saving.php?status=error&message=Invalid+PIN");
        exit();
    }

    // Start transaction
    $conn->begin_transaction();

    try {
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

        // Commit transaction
        $conn->commit();

        header("Location: cardsaving.php?status=success&message=Saving+added+successfully");
    } catch (Exception $e) {
        $conn->rollback();
        header("Location: add_new_saving.php?status=error&message=" . urlencode($e->getMessage()));
    }

    exit();
} else {
    header("Location: add_new_saving.php?status=error&message=Invalid+request+method");
}

$conn->close();
?>
