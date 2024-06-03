<!-- transaction_amount.php -->
<?php
include 'session.php';

$type = $_GET['type']; // deposit or withdrawal
$title = ucfirst($type);
ob_start();
?>

<main class="h-screen bg-gray-900">
    <div class="header">
        <div class="left">
            <h1><?php echo ucfirst($type); ?></h1>
            <ul class="breadcrumb">
                /
                <li><a href="#">Transaction</a></li>
                <li><a href="#" class="active"><?php echo ucfirst($type); ?></a></li>
            </ul>
        </div>
    </div>

    <div class="container mx-auto px-4 my-auto">
        <div class="max-w-lg mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="progress-bar mt-1">
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: 28%;"></div>
                </div>
                <div class="flex justify-between text-sm text-gray-500 mt-1 mb-8">
                    <div>Input Amount</div>
                    <div>Step 1 of 3</div>
                </div>
            </div>
            <div class="header mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo ucfirst($type); ?> Amount</h1>
            </div>
            <form action="confirm_account.php" method="POST" class="space-y-4"> <!-- Change method to POST -->
                <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-300">Enter Amount:</label>
                    <input type="number" id="amount" name="amount" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
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
