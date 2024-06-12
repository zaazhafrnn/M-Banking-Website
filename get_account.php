<?php
include 'db.php'; // Include database connection

function getUserAccounts($user_id, $conn) {
    $sql = "SELECT id, no_rekening, saldo, bank FROM rekening WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $accounts = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $accounts;    
}

function getUserSavings($user_id, $conn) {
    $sql = "SELECT * FROM tabungan WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $savings = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $savings;
}
?>
