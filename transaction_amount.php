<?php
include 'session.php';

$type = $_GET['type'];
$description = isset($_GET['description']) ? $_GET['description'] : '';
$progress = ($type == 'topup') ? '33%' : '40%';
$step = ($type == 'topup') ? '1 of 3' : '2 of 4';

$title = "Transaction Amount";
ob_start();
?>

<main class="flex flex-col h-screen">
    <div class="header">
        <div class="left">
            <h1><?php echo ucfirst($type); ?> Amount</h1>
            <ul class="breadcrumb">
                <li><a href="transaction.php">Transaction</a></li>
                <li><a href="#" class="active"><?php echo ucfirst($type); ?></a></li>
            </ul>
        </div>
    </div>

    <div class="flex flex-grow justify-center items-center">
        <div class="w-full max-w-md bg-gray-800 text-white p-8 rounded-lg shadow-lg">
            <div class="progress-bar mt-1">
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: <?php echo $progress; ?>;"></div>
                </div>
                <div class="flex justify-between text-sm text-gray-500 mt-1 mb-8">
                    <div>Input Amount</div>
                    <div>Step <?php echo $step; ?></div>
                </div>
            </div>
            <form action="confirm_account.php" method="POST">
                <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
                <?php if ($type == 'payment' && !empty($description)) : ?>
                    <input type="hidden" name="description" value="<?php echo htmlspecialchars($description); ?>">
                <?php endif; ?>
                <div class="mb-6">
                    <label for="amount" class="block mb-2 text-sm font-medium">Amount</label>
                    <input type="number" id="amount" name="amount" step="0.01" class="w-full p-2 rounded-md bg-gray-700 border border-gray-600 text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Enter the amount" required>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Next</button>
            </form>
        </div>
    </div>
</main>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
