<?php
include 'session.php';

$title = "Transaction";
ob_start();
?>

<main class="flex flex-col h-screen">
    <div class="header">
        <div class="left">
            <h1>Transaction</h1>
            <ul class="breadcrumb">
                /
                <li><a href="#" class="active">Transaction</a></li>
            </ul>
        </div>
    </div>

    <div class="flex flex-grow justify-center items-center">
        <div class="w-full h-full flex p-10 gap-8">
            <a href="transaction_amount.php?type=topup" class="w-1/2 p-5 border-2 rounded-2xl h-full bg-gray-800 text-white flex flex-col items-center justify-center text-4xl">
                <div class="flex items-center space-x-4 mb-4">
                    <i class='bx bx-money text-6xl'></i>
                    <i class='bx bx-arrow-back bx-rotate-90 text-6xl'></i>
                </div>
                Top Up Balance
            </a>
            <a href="transaction_description.php?type=payment" class="w-1/2 p-5 border-2 rounded-2xl h-full bg-gray-800 text-white flex flex-col items-center justify-center text-4xl">
                <div class="flex items-center space-x-4 mb-4">
                    <i class='bx bx-money text-6xl'></i>
                    <i class='bx bx-arrow-back bx-rotate-270 text-6xl'></i>
                </div>
                Payment
            </a>
        </div>
    </div>
</main>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
