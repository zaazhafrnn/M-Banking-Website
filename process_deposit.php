<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = $_POST['amount'];
    $no_rekening = $_POST['no_rekening'];

    // Validate inputs
    if (empty($amount) || empty($no_rekening) || !is_numeric($amount) || $amount <= 0) {
        die(json_encode(array('error' => 'Invalid input')));
    }

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Get current balance
        $sql = "SELECT saldo FROM rekening WHERE no_rekening = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception($conn->error);
        }

        $stmt->bind_param("i", $no_rekening);
        if (!$stmt->execute()) {
            throw new Exception($stmt->error);
        }

        $stmt->bind_result($saldo);
        $stmt->fetch();
        $stmt->close();

        if (is_null($saldo)) {
            throw new Exception('No balance found for the account number');
        }

        // Calculate new balance
        $new_balance = $saldo + $amount;

        // Update balance
        $sql = "UPDATE rekening SET saldo = ? WHERE no_rekening = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception($conn->error);
        }

        $stmt->bind_param("ii", $new_balance, $no_rekening);
        if (!$stmt->execute()) {
            throw new Exception($stmt->error);
        }

        // Commit transaction
        $conn->commit();

        echo json_encode(array('success' => true, 'new_balance' => $new_balance));
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        echo json_encode(array('error' => $e->getMessage()));
    }

    $conn->close();
} else {
    echo json_encode(array('error' => 'Invalid request method'));
}
?>
