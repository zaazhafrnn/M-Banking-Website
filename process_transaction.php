<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include 'db.php';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if 'amount' and 'no_rekening' are set in the POST data
    if(isset($_POST['amount']) && isset($_POST['no_rekening'])) {
        $amount = $_POST['amount'];
        $no_rekening = $_POST['no_rekening'];

        // Check if the transaction is a deposit or a withdrawal
        if(isset($_POST['type']) && $_POST['type'] == 'deposit') {
            // Deposit transaction
            $sql = "UPDATE rekening SET saldo = saldo + ? WHERE no_rekening = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("di", $amount, $no_rekening);
            $transaction_type = 'Deposit';
        } else {
            // Withdrawal transaction
            $sql = "UPDATE rekening SET saldo = saldo - ? WHERE no_rekening = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("di", $amount, $no_rekening);
            $transaction_type = 'Withdrawal';
        }

        // Execute the transaction
        if ($stmt->execute()) {
            // Transaction successful
            echo json_encode(array('message' => $transaction_type . ' successful'));
        } else {
            // Transaction failed
            echo json_encode(array('error' => 'Failed to process ' . $transaction_type));
        }

        $stmt->close();
    } else {
        // Missing required POST parameters
        echo json_encode(array('error' => 'Missing parameters'));
    }
} else {
    // Request method other than POST
    echo json_encode(array('error' => 'Invalid request method'));
}

$conn->close();
?>
