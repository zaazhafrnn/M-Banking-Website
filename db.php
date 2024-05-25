<?php
$servername = "localhost";
$username = "user-baru";
$password = "password";
$dbname = "atm_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
