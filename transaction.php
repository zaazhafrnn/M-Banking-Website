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
                <div class="flex items-center space-x-4 mb-8">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15M9 12l3 3m0 0 3-3m-3 3V2.25" />
                    </svg>
                </div>
                Top Up Balance
            </a>
            <a href="transaction_description.php?type=payment" class="w-1/2 p-5 border-2 rounded-2xl h-full bg-gray-800 text-white flex flex-col items-center justify-center text-4xl">
                <div class="flex items-center space-x-4 mb-8">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15m0-3-3-3m0 0-3 3m3-3V15" />
                    </svg>
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