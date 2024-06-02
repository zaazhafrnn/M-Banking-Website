<?php
include 'session.php';
include 'get_account.php';

$title = "Transfer Funds";
$accounts = getUserAccounts($_SESSION['user_id'], $conn);
ob_start();
?>

<main class="min-h-screen bg-gray-100 dark:bg-gray-900">
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
