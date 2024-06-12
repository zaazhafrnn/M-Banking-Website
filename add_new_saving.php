<?php
include 'session.php';
include 'get_account.php';

$title = "Add New Saving";
$user_id = $_SESSION['user_id'];
$accounts = getUserAccounts($user_id, $conn);

ob_start();
?>

<main class="p-6">
    <div class="header flex justify-between items-center">
        <h1 class="text-3xl font-semibold text-white">Add New Saving</h1>
    </div>

    <div class="mt-8 flex justify-center">
        <form id="addSavingForm" class="bg-gray-900 shadow-md rounded w-1/3 px-8 py-6" action="add_new_saving_process.php" method="POST">
            <div id="step1">
                <h2 class="text-xl font-semibold text-white mb-4">Step 1: Saving Details</h2>
                <div class="mb-4">
                    <label for="title" class="block text-gray-300 text-sm font-bold mb-2">Saving Title:</label>
                    <input type="text" id="title" name="title" class="border border-gray-500 px-3 py-2 w-full bg-gray-700 text-gray-300" required>
                </div>
                <div class="mb-4">
                    <label for="budget_goal" class="block text-gray-300 text-sm font-bold mb-2">Budget Goal:</label>
                    <input type="number" id="budget_goal" name="budget_goal" class="border border-gray-500 px-3 py-2 w-full bg-gray-700 text-gray-300" required>
                </div>
                <div class="mb-4">
                    <label for="initial_balance" class="block text-gray-300 text-sm font-bold mb-2">Initial Balance (optional):</label>
                    <input type="number" id="initial_balance" name="initial_balance" class="border border-gray-500 px-3 py-2 w-full bg-gray-700 text-gray-300">
                </div>
                <button type="button" id="nextStep1" class="px-4 py-2 bg-blue-500 text-white rounded">Next</button>
            </div>

            <div id="step2" class="hidden">
                <h2 class="text-xl font-semibold text-white mb-4">Step 2: Select Account</h2>
                <div class="mb-4">
                    <label for="account" class="block text-gray-300 text-sm font-bold mb-2">Select Account:</label>
                    <select id="account" name="account" class="border border-gray-500 px-3 py-2 w-full bg-gray-700 text-gray-300">
                        <option value="">Select Account</option>
                        <?php foreach ($accounts as $account) : ?>
                            <option value="<?php echo htmlspecialchars($account['id']); ?>">
                                <?php echo htmlspecialchars($account['bank'] . ' - ' . $account['no_rekening']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="button" id="nextStep2" class="px-4 py-2 bg-blue-500 text-white rounded">Next</button>
            </div>

            <div id="step3" class="hidden">
                <h2 class="text-xl font-semibold text-white mb-4">Step 3: Confirm PIN</h2>
                <div class="mb-4">
                    <label for="pin" class="block text-gray-300 text-sm font-bold mb-2">Enter PIN:</label>
                    <input type="password" id="pin" name="pin" class="border border-gray-500 px-3 py-2 w-full bg-gray-700 text-gray-300" required>
                </div>
                <button type="submit" id="addSaving" class="px-4 py-2 bg-blue-500 text-white rounded">Add Saving</button>
            </div>

            <div id="error_message" class="text-red-500 mb-4"></div>
        </form>
    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const nextStep1Btn = document.getElementById('nextStep1');
        const nextStep2Btn = document.getElementById('nextStep2');
        const addSavingForm = document.getElementById('addSavingForm');
        const errorMessage = document.getElementById('error_message');
        const steps = ['step1', 'step2', 'step3'];
        let currentStep = 0;

        nextStep1Btn.addEventListener('click', function() {
            const title = document.getElementById('title').value;
            const budget_goal = document.getElementById('budget_goal').value;
            if (!title || !budget_goal) {
                displayErrorMessage("Please fill in the required fields.");
                return;
            }
            hideErrorMessage();
            if (document.getElementById('initial_balance').value) {
                showStep(++currentStep);
            } else {
                currentStep = 2;
                showStep(currentStep);
            }
        });

        nextStep2Btn.addEventListener('click', function() {
            const account = document.getElementById('account').value;
            if (!account) {
                displayErrorMessage("Please select an account.");
                return;
            }
            hideErrorMessage();
            showStep(++currentStep);
        });

        addSavingForm.addEventListener('submit', function(event) {
            const pin = document.getElementById('pin').value;
            if (!pin) {
                event.preventDefault();
                displayErrorMessage("Please enter your PIN.");
                return;
            }
            hideErrorMessage();
        });

        function displayErrorMessage(message) {
            errorMessage.textContent = message;
        }

        function hideErrorMessage() {
            errorMessage.textContent = '';
        }

        function showStep(step) {
            steps.forEach((stepId, index) => {
                if (index === step) {
                    document.getElementById(stepId).classList.remove('hidden');
                } else {
                    document.getElementById(stepId).classList.add('hidden');
                }
            });
        }
    });
</script>

<?php
$content = ob_get_clean();
include 'layout.php';
?>