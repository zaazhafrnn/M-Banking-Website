<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include 'db.php';

// Start the session
session_start();

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if 'amount' and 'account_number' are set in the POST data
    if (isset($_POST['amount']) && isset($_POST['account_number'])) {
        $amount = $_POST['amount'];
        $account_number = $_POST['account_number'];

        // Get the no_rekening (id from rekening table) for the account_number
        $sql = "SELECT id FROM rekening WHERE no_rekening = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $account_number);
        $stmt->execute();
        $stmt->bind_result($no_rekening);
        $stmt->fetch();
        $stmt->close();

        if (!$no_rekening) {
            echo json_encode(array('error' => 'Invalid account number'));
            exit;
        }

        // Determine if it's a deposit or a withdrawal
        if (isset($_POST['type']) && $_POST['type'] == 'deposit') {
            $sql = "UPDATE rekening SET saldo = saldo + ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("di", $amount, $no_rekening);
            $transaction_type = 'Setor';
            $description = 'Setor Tunai';
        } else {
            $sql = "UPDATE rekening SET saldo = saldo - ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("di", $amount, $no_rekening);
            $transaction_type = 'Tarik';
            $description = 'Tarik Tunai';
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
            $transaction_amount = (isset($_POST['type']) && $_POST['type'] == 'deposit') ? $amount : -$amount;
            $sql = "INSERT INTO transaksi (transaksi_id, no_rekening, tipe_transaksi, jumlah, deskripsi) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iisds", $new_transaksi_id, $no_rekening, $transaction_type, $transaction_amount, $description);

            if ($stmt->execute()) {
                echo json_encode(array('message' => $transaction_type . ' successful'));
            } else {
                echo json_encode(array('error' => 'Failed to record transaction'));
            }
            $stmt->close();
        } else {
            echo json_encode(array('error' => 'Failed to process ' . $transaction_type));
        }
    } else {
        echo json_encode(array('error' => 'Missing parameters'));
    }
} else {
    echo json_encode(array('error' => 'Invalid request method'));
}

$conn->close();
?>
