<?php
include 'session.php';
include 'db.php';

$title = "Transfer Funds";
ob_start();
?>

<main class="min-h-full bg-gray-100 dark:bg-gray-900">
    <div class="header">
        <div class="left">
            <h1>Transfer</h1>
            <ul class="breadcrumb">
                /
                <li><a href="transfer.php" class="active">Transfer</a></li>
            </ul>
        </div>
    </div>

    <div class="container mx-auto px-4">
        <div class="max-w-lg mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="progress-bar mt-1">
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: 20%;"></div>
                </div>
                <div class="flex justify-between text-sm text-gray-500 mt-1 mb-8">
                    <div>Select Bank and Input Account Number</div>
                    <div>Step 1 of 5</div>
                </div>
            </div>
            <div class="header mb-6">
                <h1 class="text-2xl font-bold text-white">Transfer Funds</h1>
            </div>
            <form action="transfer_confirmation.php" method="POST">
                <div class="mb-4">
                    <label for="bank" class="block text-sm font-medium text-gray-300">Select Bank:</label>
                    <select id="bank" name="bank" required class="mt-1 p-2 border rounded-md w-full">
                        <?php
                        $banks = getAllBanks($conn);
                        foreach ($banks as $bank) : ?>
                            <option value="<?php echo htmlspecialchars($bank); ?>">
                                <?php echo htmlspecialchars($bank); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="recipient_account" class="block text-sm font-medium text-gray-300">Recipient Account Number:</label>
                    <input type="text" id="recipient_account" name="recipient_account" required class="mt-1 p-2 border rounded-md w-full">
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 mt-6">Next</button>
            </form>
        </div>
    </div>
</main>

<?php
$content = ob_get_clean();
include 'layout.php';

function getAllBanks($conn)
{
    $sql = "SELECT DISTINCT bank FROM rekening";
    $result = $conn->query($sql);
    $banks = [];
    while ($row = $result->fetch_assoc()) {
        $banks[] = $row['bank'];
    }
    return $banks;
}
?>