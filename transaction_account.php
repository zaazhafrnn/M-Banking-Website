<?php
include 'session.php';
include 'get_account.php';

$type = $_GET['type']; // deposit or withdrawal
$title = ucfirst($type);
$accounts = getUserAccounts($_SESSION['user_id'], $conn);
ob_start();
?>

<main class="min-h-screen bg-gray-900 overflow-hidden">
    <div class="header">
        <div class="left">
            <h1>Deposit</h1>
            <ul class="breadcrumb">
                /
                <li><a href="#">Transaction</a></li>
                <li><a href="#" class="active">Deposit</a></li>
            </ul>
        </div>
    </div>

    <div class="container mx-auto px-4 pb-10">
        <div class="max-w-lg mx-auto bg-gray-800 rounded-lg shadow-md p-6">
            <div class="progress-bar mt-1">
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: 59%;"></div>
                </div>
                <div class="flex justify-between text-sm text-gray-500 mt-1 mb-8">
                    <div>Select Account/Card</div>
                    <div>Step 2 of 3</div>
                </div>
            </div>
            <div class="header mb-6">
                <h1 class="text-2xl font-bold text-white"><?php echo ucfirst($type); ?> Account</h1>
            </div>
            <form action="transaction_pin.php" method="POST">
                <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
                <input type="hidden" name="amount" value="<?php echo $_GET['amount']; ?>">
                <div class="grid grid-cols-1 gap-6 mt-8">
                    <?php foreach ($accounts as $account) : ?>
                        <label class="flex items-center justify-start p-4 bg-gray-200 rounded-lg shadow-md cursor-pointer">
                            <input type="radio" name="selected_account" value="<?php echo htmlspecialchars($account['id']); ?>" class="mr-2 form-radio" required>
                            <div class="text-sm">
                                <h2 class="text-xl font-semibold text-gray-800"><?php echo htmlspecialchars($account['bank']); ?></h2>
                                <h2 class="text-xl font-semibold text-gray-800">Account Number: <?php echo htmlspecialchars($account['no_rekening']); ?></h2>
                                <p class="text-gray-600 mt-2">Balance: Rp. <?php echo number_format($account['saldo'], 2, ',', '.'); ?></p>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 mt-6">Next</button>
            </form>
        </div>
    </div>
</main>

<?php
$content = ob_get_clean();
include 'layout.php';
?>