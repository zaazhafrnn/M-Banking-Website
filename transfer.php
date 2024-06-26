<?php
include 'session.php';
include 'get_account.php';

$status = isset($_GET['status']) ? $_GET['status'] : '';
$message = isset($_GET['message']) ? urldecode($_GET['message']) : '';

$title = "Transfer Funds";
$accounts = getUserAccounts($_SESSION['user_id'], $conn);
ob_start();
?>

<?php if ($status && $message): ?>
    <div class="message <?php echo htmlspecialchars($status); ?>">
        <p><?php echo htmlspecialchars($message); ?></p>
    </div>
    <script>
        setTimeout(function() {
            document.querySelector('.message').style.display = 'none';
        }, 5000);
    </script>
<?php endif; ?>

<main class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <div class="header">
        <div class="left">
            <h1>Transfer</h1>
            <ul class="breadcrumb">
                /
                <li><a href="#" class="active">Transfer</a></li>
            </ul>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-lg mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="header mb-6">
                <h1 class="text-2xl font-bold text-white">Transfer Funds</h1>
            </div>
            <form action="transfer_new_recipient.php" method="POST">
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 mt-6">Transfer to New Recipient</button>
            </form>
        </div>
    </div>
</main>

<?php
$content = ob_get_clean();
include 'layout.php';
?>