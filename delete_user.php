<?php
// Include database connection
include 'db.php';

// Check if user_id is provided via POST request
if (isset($_POST['user_id'])) {
    // Sanitize user_id to prevent SQL injection
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);

    // SQL query to delete the user with the provided user_id
    $sql = "DELETE FROM users WHERE id = '$user_id'";

    if ($conn->query($sql) === TRUE) {
        // If deletion is successful, return success message
        echo json_encode(array('status' => 'success', 'message' => 'User deleted successfully'));
    } else {
        // If deletion fails, return error message
        echo json_encode(array('status' => 'error', 'message' => 'Error deleting user: ' . $conn->error));
    }
} else {
    // If user_id is not provided, return error message
    echo json_encode(array('status' => 'error', 'message' => 'User ID not provided'));
}
?>
