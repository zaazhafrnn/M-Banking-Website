<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_pin'])) {
    include 'db.php';

    $account_id = $_POST['account_id'];
    $new_pin = $_POST['pin'];

    $update_sql = "UPDATE rekening SET pin='$new_pin' WHERE id=$account_id";

    if ($conn->query($update_sql) === TRUE) {
        header("Location: manage_accounts.php?status=success&message=PIN updated successfully");
        exit();
    } else {
        header("Location: manage_accounts.php?status=error&message=Error updating PIN: " . $conn->error);
        exit();
    }
}
