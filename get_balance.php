<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, return an error response
    echo json_encode(array('error' => 'User not logged in'));
    exit();
}

// Include database connection
include 'db.php';

// Retrieve account number from session
$account_number = $_SESSION['account_number'];

// Prepare SQL statement to fetch balance
$sql = "SELECT saldo FROM rekening WHERE no_rekening = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $account_number);
$stmt->execute();
$stmt->bind_result($saldo);
$stmt->fetch();

// Check if the balance is null
if (is_null($saldo)) {
    // If the balance is null, return an error response
    echo json_encode(array('error' => 'No balance found for the account number'));
} else {
    // If the balance is found, return it as a JSON response
    echo json_encode(array('saldo' => $saldo));
}

// Close the statement and the database connection
$stmt->close();
$conn->close();
?>
