<?php
// session_save_path('/home/aza/tugas');
session_start();

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    // User is logged in, do nothing
} else {
    // User is not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

// Include database connection
include 'db.php';
?>