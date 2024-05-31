<?php
include 'session.php';

// Get the account number from the session
$account_number = $_SESSION['account_number'];

$title = "Transaction";
ob_start();
?>

<main>
    <div class="header">
        <div class="left">
            <h1>Transaction</h1>
            <ul class="breadcrumb">
                /
                <li><a href="#" class="active">Transaction</a></li>
            </ul>
        </div>
    </div>

    <div class="bottom-data justify-center text-center">
        <h2 class="text-yellow text-2xl mb-5">Choose Transaction Type</h2>
        <a href="deposit.php" class="w-5/12 bg-blue-500 text-white py-2 px-4 rounded-full mt-5 inline-block">
            Deposit
        </a>
        <a href="withdrawal.php" class="w-5/12 bg-red-500 text-white py-2 px-4 rounded-full mt-5 inline-block">
            Withdrawal
        </a>
    </div>
</main>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
