<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection
    include 'db.php';

    // Retrieve username and password from the login form
    $username = $_POST['username']; // Assuming username is used for login
    $password = $_POST['password'];

    // Prepare SQL statement to fetch user data
    $sql = "SELECT id, nik, nama_depan, nama_belakang FROM users WHERE nik = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $stmt->bind_result($user_id, $nik, $nama_depan, $nama_belakang);
    $stmt->fetch();
    $stmt->close();

    // Check if a user with the given credentials exists
    if ($user_id) {
        // Query the rekening table to retrieve the associated account number
        $sql = "SELECT no_rekening FROM rekening WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($account_number);
        $stmt->fetch();
        $stmt->close();

        // Store user data and account number in the session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['nik'] = $nik;
        $_SESSION['nama_depan'] = $nama_depan;
        $_SESSION['nama_belakang'] = $nama_belakang;
        $_SESSION['account_number'] = $account_number;

        // Redirect the user to the dashboard or desired page
        header("Location: index.php");
        exit();
    } else {
        // If no user found with the given credentials, display an error message
        $error_message = "Invalid username or password.";
    }
}
?>
