<?php
include 'session.php';

// Get the account number from the session
$account_number = $_SESSION['account_number'];

$title = "Withdrawal";
ob_start();
?>

<main>
<div class="header">
        <div class="left">
            <h1>Withdrawal</h1>
            <ul class="breadcrumb">
                <li><a href="/transaction.php">Transaction</a> /</li>
                <li><a href="/deposit.php" class="active">withdrawal</a> /</li>
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
        <input type="hidden" name="type" value="withdrawal">
        <button
            type="submit"
            id="confirm-withdrawal"
            class="w-5/12 bg-red-500 text-white py-2 px-4 rounded-full mt-5"
        >
            Withdrawal
        </button>
    </form>
</main>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
