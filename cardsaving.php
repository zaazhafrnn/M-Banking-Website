<?php
include 'session.php';
include 'get_account.php';

$title = "Card & Saving";
$user_id = $_SESSION['user_id'];
$accounts = getUserAccounts($user_id, $conn);

ob_start();
?>

<main class="p-6">
    <div class="header flex justify-between items-center">
        <h1 class="text-3xl font-semibold text-white">Card & Saving</h1>
        <button id="newCardBtn" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Add New Card</button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
        <?php foreach ($accounts as $account): ?>
            <div class="bg-gray-900 p-6 rounded-xl shadow-md">
                <h2 class="text-xl font-semibold text-white"><?php echo htmlspecialchars($account['bank']); ?></h2>
                <h2 class="text-xl font-semibold text-white">Account Number: <?php echo htmlspecialchars($account['no_rekening']); ?></h2>
                <p class="text-white mt-2">Balance: Rp. <?php echo number_format($account['saldo'], 2, ',', '.'); ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- New Card Modal -->
    <div id="newCardModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center hidden">
        <div class="bg-gray-900 p-8 rounded-xl shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-semibold text-white mb-4">Add New Card</h2>
            <form id="newCardForm" action="add_card.php" method="POST" class="space-y-4">
                <div>
                    <label for="bank" class="block text-sm text-gray-400">Bank Name</label>
                    <input type="text" id="bank" name="bank" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" required>
                </div>
                <div>
                    <label for="account_number" class="block text-sm text-gray-400">Account Number</label>
                    <input type="text" id="account_number" name="account_number" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" required>
                </div>
                <div>
                    <label for="balance" class="block text-sm text-gray-400">Initial Balance</label>
                    <input type="number" id="balance" name="balance" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" required>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" id="cancelBtn" class="bg-red-500 text-white px-4 py-2 rounded-lg">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Add Card</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    document.getElementById('newCardBtn').addEventListener('click', function () {
        document.getElementById('newCardModal').classList.remove('hidden');
    });

    document.getElementById('cancelBtn').addEventListener('click', function () {
        document.getElementById('newCardModal').classList.add('hidden');
    });
</script>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
