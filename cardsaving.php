<?php
include 'session.php';
include 'get_account.php';

$title = "Card & Saving";
$user_id = $_SESSION['user_id'];
$accounts = getUserAccounts($user_id, $conn);

ob_start();
?>

<main class="p-6">
    <div class="header flex justify-between items-center">
        <h1 class="text-3xl font-semibold text-white">Card & Saving</h1>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
        <?php foreach ($accounts as $account): ?>
            <div class="bg-gray-900 p-6 rounded-xl shadow-md">
                <h2 class="text-xl font-semibold text-white"><?php echo htmlspecialchars($account['bank']); ?></h2>
                <h2 class="text-xl font-semibold text-white">Account Number: <?php echo htmlspecialchars($account['no_rekening']); ?></h2>
                <p class="text-white mt-2">Balance: Rp. <?php echo number_format($account['saldo'], 2, ',', '.'); ?></p>
            </div>
        <?php endforeach; ?>

        <a href="add_new_card.php" class="bg-gray-900 p-6 rounded-xl shadow-md flex justify-center items-center border-4 border-blue-500">
            <span class="text-blue-500 font-bold text-4xl">+</span>
        </a>
    </div>
</main>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
