<?php
include 'session.php';
// include 'db.php';
include 'get_account.php';

$status = isset($_GET['status']) ? $_GET['status'] : '';
$message = isset($_GET['message']) ? urldecode($_GET['message']) : '';

$user_id = $_SESSION['user_id'];
$accounts = getUserAccounts($user_id, $conn);

$title = "Home";
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

<main>
    <div class="header">
        <div class="left">
            <h1>Overview</h1>
            <ul class="breadcrumb">
                /
                <li><a href="#" class="active">Home</a></li>
            </ul>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
        <?php foreach ($accounts as $account): ?>
            <div class="bg-gray-800 p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-white"><?php echo htmlspecialchars($account['bank']); ?></h2>
                <h2 class="text-xl font-semibold text-white">Account Number: <?php echo htmlspecialchars($account['no_rekening']); ?></h2>
                <p class="text-white mt-2">Balance: Rp. <?php echo number_format($account['saldo'], 2, ',', '.'); ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="bottom-data mt-8">
        <div class="history">
            <div class="header">
                <i class='bx bx-receipt'></i>
                <h3>Last Transaction</h3>
                <i class='bx bx-filter'></i>
                <i class='bx bx-search'></i>
            </div>
            <table class="w-full">
                <thead></thead>
                <tbody id="transaction-history" class="text-sm">
                    <!-- Transactions will be loaded here via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
