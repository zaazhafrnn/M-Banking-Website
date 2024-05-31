<?php
include 'session.php';

// Get the account number from the session
$account_number = $_SESSION['account_number'];

$title = "Deposit";
ob_start();
?>

<main>
    <div class="header">
        <div class="left">
            <h1>Deposit</h1>
            <ul class="breadcrumb">
                <li><a href="/transaction.php">Transaction</a> /</li>
                <li><a href="/deposit.php" class="active">Deposit</a> /</li>
            </ul>
        </div>
    </div>

    <form action="process_transaction.php" method="POST" class="bottom-data justify-center text-center">
        <input
            type="number"
            id="amount"
            name="amount"
            class="w-5/12 bg-gray-700 p-5 rounded-full text-white"
            placeholder="Amount"
            required
        />
        <input type="hidden" name="account_number" value="<?php echo $account_number; ?>">
        <input type="hidden" name="type" value="deposit">
        <button
            type="submit"
            id="confirm-deposit"
            class="w-5/12 bg-blue-500 text-white py-2 px-4 rounded-full mt-5"
        >
            Deposit
        </button>
    </form>
</main>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
