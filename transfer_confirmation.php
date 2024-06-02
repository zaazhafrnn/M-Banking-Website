<?php
include 'session.php';
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recipient_account = $_POST['recipient_account'];
    $bank = $_POST['bank'];

    // Validate recipient account
    $stmt = $conn->prepare("SELECT id, user_id FROM rekening WHERE no_rekening = ? AND bank = ?");
    $stmt->bind_param("ss", $recipient_account, $bank);
    $stmt->execute();
    $stmt->bind_result($recipient_id, $recipient_user_id);
    $stmt->fetch();
    $stmt->close();

    if (!$recipient_id) {
        header("Location: transfer.php?status=error&message=Recipient+account+not+found");
        exit();
    }

    // Get recipient name
    $stmt = $conn->prepare("SELECT nama_depan FROM users WHERE id = ?");
    $stmt->bind_param("i", $recipient_user_id);
    $stmt->execute();
    $stmt->bind_result($recipient_name);
    $stmt->fetch();
    $stmt->close();

    $_SESSION['transfer'] = [
        'recipient_account' => $recipient_account,
        'recipient_name' => $recipient_name,
        'recipient_id' => $recipient_id,
        'bank' => $bank
    ];

    $title = "Confirm Recipient";
    ob_start();
?>

<main class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-lg mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="header mb-6">
                <h1 class="text-2xl font-bold text-white">Confirm Recipient</h1>
                <div class="progress-bar mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: 42%;"></div>
                    </div>
                    <div class="text-left text-sm text-gray-500 mt-1">Confirm Recipient Details</div>
                    <div class="text-right text-sm text-gray-500 mt-1">Step 2 of 5</div>
                </div>
            </div>
            <div class="mb-4 dark:text-white">
                <p><strong>Recipient Name:</strong> <?php echo htmlspecialchars($recipient_name); ?></p>
                <p><strong>Recipient Account:</strong> <?php echo htmlspecialchars($recipient_account); ?></p>
                <p><strong>Bank:</strong> <?php echo htmlspecialchars($bank); ?></p>
            </div>
            <form action="transfer_amount.php" method="POST">
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 mt-6">Next</button>
            </form>
        </div>
    </div>
</main>

<?php
    $content = ob_get_clean();
    include 'layout.php';
} else {
    header("Location: transfer.php");
    exit();
}
?>
