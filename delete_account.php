<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['account_id'])) {
    include 'db.php';

    $account_id = $_POST['account_id'];

    $delete_sql = "DELETE FROM rekening WHERE id=$account_id";

    if ($conn->query($delete_sql) === TRUE) {
        echo "Account deleted successfully";
        exit();
    } else {
        echo "Error deleting account: " . $conn->error;
        exit();
    }
}
