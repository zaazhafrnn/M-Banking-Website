<?php
include 'session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $account_id = $_POST['selected_account'];

    // For transfer transactions
    $recipient_account_id = isset($_POST['recipient_account_id']) ? $_POST['recipient_account_id'] : null;
    $recipient_account = isset($_POST['recipient_account']) ? $_POST['recipient_account'] : null;
    $recipient_name = isset($_POST['recipient_name']) ? $_POST['recipient_name'] : null;
    $bank = isset($_POST['bank']) ? $_POST['bank'] : null;

    if (empty($account_id)) {
        header("Location: index.php?status=error&message=Please+select+an+account");
        exit();
    }

    // Store transaction details in session
    $_SESSION['transaction'] = [
        'type' => $type,
        'amount' => $amount,
        'account_id' => $account_id,
        'recipient_account_id' => $recipient_account_id,
        'recipient_account' => $recipient_account,
        'recipient_name' => $recipient_name,
        'bank' => $bank
    ];

    $title = ucfirst($type);
    ob_start();
?>
    <main class="h-screen bg-gray-100 dark:bg-gray-900">
        <div class="header">
            <div class="left">
                <h1><?php echo ucfirst($type); ?></h1>
                <ul class="breadcrumb">
                    <li><a href="#">Transaction</a></li>
                    <li><a href="#" class="active"><?php echo ucfirst($type); ?></a></li>
                </ul>
            </div>
        </div>

        <div class="container mx-auto px-4 my-auto">
            <div class="max-w-lg mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="progress-bar mt-1">
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: 100%;"></div>
                    </div>
                    <div class="flex justify-between text-sm text-gray-500 mt-1 mb-8">
                        <div>Confirmation PIN</div>
                        <div>Step <?php echo ($type == 'transfer') ? '5 of 5' : '3 of 3'; ?></div>
                    </div>
                </div>
                <div class="header mb-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Confirm <?php echo ucfirst($type); ?></h1>
                </div>
                <form action="process_transaction.php" method="POST" class="mt-8 max-w-md mx-auto">
                    <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
                    <input type="hidden" name="amount" value="<?php echo htmlspecialchars($amount); ?>">
                    <input type="hidden" name="account_id" value="<?php echo htmlspecialchars($account_id); ?>">
                    <?php if ($type == 'transfer') : ?>
                        <input type="hidden" name="recipient_account_id" value="<?php echo htmlspecialchars($recipient_account_id); ?>">
                        <input type="hidden" name="recipient_account" value="<?php echo htmlspecialchars($recipient_account); ?>">
                        <input type="hidden" name="recipient_name" value="<?php echo htmlspecialchars($recipient_name); ?>">
                        <input type="hidden" name="bank" value="<?php echo htmlspecialchars($bank); ?>">
                    <?php endif; ?>
                    <div class="mb-4">
                        <label for="pin" class="block text-sm font-medium text-gray-300">Enter PIN:</label>
                        <input type="password" id="pin" name="pin" required class="mt-1 p-2 border rounded-md w-full">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Confirm</button>
                </form>
            </div>
        </div>
    </main>

<?php
    $content = ob_get_clean();
    include 'layout.php';
} else {
    header("Location: transaction.php");
    exit();
}
?>
