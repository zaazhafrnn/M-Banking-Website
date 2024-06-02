<?php
include 'session.php';
// include 'db.php';
include 'get_account.php';

$user_id = $_SESSION['user_id'];

$title = "Transaction History";
ob_start();
?>

<main>
    <div class="header">
        <div class="left">
            <h1>Transactional History</h1>
            <ul class="breadcrumb">
                /
                <li><a href="#" class="active">History</a></li>
            </ul>
        </div>
    </div>

    <div class="bottom-data mt-8 pb-10">
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
