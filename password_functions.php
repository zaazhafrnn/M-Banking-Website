<?php
// Connect to your database
$conn = new mysqli("localhost", "user-baru", "password", "atm_database");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all users from the database
$sql = "SELECT id, password FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Loop through each user record
    while ($row = $result->fetch_assoc()) {
        // Hash the user's password
        $hashedPassword = password_hash($row['password'], PASSWORD_DEFAULT);

        // Update the database with the hashed password
        $updateSql = "UPDATE users SET password = '$hashedPassword' WHERE id = " . $row['id'];
        if ($conn->query($updateSql) === TRUE) {
            echo "Password hashed successfully for user with ID: " . $row['id'] . "<br>";
        } else {
            echo "Error updating password for user with ID: " . $row['id'] . ": " . $conn->error . "<br>";
        }
    }
} else {
    echo "No users found in the database.";
}

// Close the database connection
$conn->close();
?>
