<?php
include 'session.php';
include 'get_account.php';

$title = "Card & Saving";
$user_id = $_SESSION['user_id'];
$accounts = getUserAccounts($user_id, $conn);
$savings = getUserSavings($user_id, $conn);

ob_start();
?>

<main class="p-6">
    <!-- Cards Section -->
    <div class="header flex justify-between items-center">
        <h1 class="text-3xl font-semibold text-white">My Cards</h1>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-8 mb-10">
        <?php foreach ($accounts as $account) : ?>
            <div class="bg-gray-900 p-6 rounded-xl shadow-md">
                <h2 class="text-xl font-semibold text-white"><?php echo htmlspecialchars($account['bank']); ?></h2>
                <h2 class="text-xl font-semibold text-white">Account Number: <?php echo htmlspecialchars($account['no_rekening']); ?></h2>
                <p class="text-white mt-2">Balance: Rp. <?php echo number_format($account['saldo'], 2, ',', '.'); ?></p>
            </div>
        <?php endforeach; ?>

        <a href="add_new_card.php" class="bg-gray-900 p-6 rounded-xl shadow-md flex justify-center items-center border-4 border-blue-500">
            <span class="text-blue-500 font-bold text-4xl">+</span>
        </a>
    </div>

    <!-- Savings Section -->
    <div class="header flex justify-between items-center">
        <h1 class="text-3xl font-semibold text-white">Savings</h1>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
        <?php foreach ($savings as $saving) : ?>
            <div class="bg-gray-900 p-6 rounded-xl shadow-md relative">
                <h2 class="text-xl font-semibold text-white"><?php echo htmlspecialchars($saving['title']); ?></h2>
                <p class="text-white mt-2">Budget Goal: Rp. <?php echo number_format($saving['budget_goal'], 2, ',', '.'); ?></p>
                <p class="text-white mt-2">Current Balance: Rp. <?php echo number_format($saving['current_balance'], 2, ',', '.'); ?></p>

                <!-- 3-Dot Menu -->
                <div class="absolute top-4 right-4">
                    <button class="text-gray-400 hover:text-white focus:outline-none" onclick="toggleDropdown(<?php echo $saving['id']; ?>)">
                        <i class='bx bx-dots-vertical-rounded'></i>
                    </button>
                    <div id="dropdown-<?php echo $saving['id']; ?>" class="hidden absolute right-0 mt-2 w-48 bg-gray-800 rounded-md shadow-lg z-20">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700" onclick="showAddBalanceModal(<?php echo $saving['id']; ?>)">Add Balance</a>
                        <!-- Other actions can be added here -->
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <a href="add_new_saving.php" class="bg-gray-900 p-6 rounded-xl shadow-md flex justify-center items-center border-4 border-blue-500">
            <span class="text-blue-500 font-bold text-4xl">+</span>
        </a>
    </div>
</main>

<!-- Add Balance Modal -->
<div id="addBalanceModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-gray-900 p-6 rounded-xl shadow-md w-1/3">
        <h2 class="text-xl font-semibold text-white mb-4">Add Balance to Saving</h2>
        <form id="addBalanceForm" action="confirm_account.php" method="POST">
            <input type="hidden" id="saving_id" name="saving_id">
            <div class="mb-4">
                <label for="amount" class="block text-gray-300 text-sm font-bold mb-2">Amount:</label>
                <input type="number" id="amount" name="amount" class="border border-gray-500 px-3 py-2 w-full bg-gray-700 text-gray-300" required>
                <input type="hidden" name="type" value="saving">
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Next</button>
        </form>
        <button class="mt-4 px-4 py-2 bg-red-500 text-white rounded" onclick="closeAddBalanceModal()">Cancel</button>
    </div>
</div>

<script>
    function toggleDropdown(savingId) {
        var dropdown = document.getElementById('dropdown-' + savingId);
        dropdown.classList.toggle('hidden');
    }

    function showAddBalanceModal(savingId) {
        document.getElementById('saving_id').value = savingId;
        document.getElementById('addBalanceModal').classList.remove('hidden');
    }

    function closeAddBalanceModal() {
        document.getElementById('addBalanceModal').classList.add('hidden');
    }
</script>

<?php
$content = ob_get_clean();
include 'layout.php';
?>