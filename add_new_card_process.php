<?php
// Include necessary files and session handling
include 'session.php';
include 'db.php'; // Assuming you have a file for database connection

// Check if the form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $bank = $_POST['bank'];
    $pin = $_POST['pin'];

    // Validate form data
    if (empty($bank) || empty($pin)) {
        // Handle empty fields
        header("Location: add_new_card.php?error=empty_fields");
        exit();
    }

    // Check if the user already has an account with the selected bank
    $user_id = $_SESSION['user_id'];
    $sql_check = "SELECT * FROM rekening WHERE user_id = ? AND bank = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("is", $user_id, $bank);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // User already has an account with the selected bank
        header("Location: add_new_card.php?error=existing_account");
        exit();
    }

    // Generate a new card number
    $card_number = generateCardNumber($bank);

    // Insert the new card into the database
    $sql = "INSERT INTO rekening (no_rekening, user_id, pin, saldo, bank) VALUES (?, ?, ?, 0, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiis", $card_number, $user_id, $pin, $bank);

    if ($stmt->execute()) {
        // Card added successfully, redirect to card saving page
        header("Location: cardsaving.php");
        exit();
    } else {
        // Error inserting card
        header("Location: add_new_card.php?error=insert_error");
        exit();
    }
} else {
    // Redirect if accessed directly
    header("Location: add_new_card.php");
    exit();
}

// Function to generate a new card number based on bank
function generateCardNumber($bank) {
    // Generate a random 6-digit number
    $random_number = mt_rand(100000, 999999);
    
    // Append bank identification numbers to the random number
    switch ($bank) {
        case 'BCA':
            $card_number = '24' . $random_number;
            break;
        case 'BRI':
            $card_number = '34' . $random_number;
            break;
        // Add more cases for other banks if needed
        default:
            // Default to a generic identification number
            $card_number = '99' . $random_number;
            break;
    }

    return $card_number;
}
?>
