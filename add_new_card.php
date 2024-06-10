<?php
// Include necessary files and session handling
include 'session.php';

$title = "Add New Card";
ob_start();

// Check for error messages passed through URL parameters
$error_message = '';
if (isset($_GET['error'])) {
    $error = $_GET['error'];
    switch ($error) {
        case 'empty_fields':
            $error_message = "Please fill in all the fields.";
            break;
        case 'existing_account':
            $error_message = "You already have an account with the selected bank. Try with another bank";
            break;
        case 'insert_error':
            $error_message = "Failed to add the new card. Please try again later.";
            break;
        // Add more error cases if needed
        default:
            $error_message = "An error occurred.";
            break;
    }
}
?>

<!-- Main content -->
<main class="p-6">
    <div class="header flex justify-between items-center">
        <h1 class="text-3xl font-semibold text-white">Add New Card</h1>
    </div>

    <!-- Add New Card Form -->
    <div class="mt-8 flex justify-center">
        <form id="addCardForm" class="bg-gray-900 shadow-md rounded w-1/3 px-8 py-6" action="add_new_card_process.php" method="POST">
            <div id="step1">
                <h2 class="text-xl font-semibold text-white mb-4">Step 1: Select Bank</h2>
                <div class="mb-4">
                    <label for="bank" class="block text-gray-300 text-sm font-bold mb-2">Bank:</label>
                    <select id="bank" name="bank" class="border border-gray-500 px-3 py-2 w-full bg-gray-700 text-gray-300">
                        <option value="">Select Bank</option>
                        <option value="BCA">BCA</option>
                        <option value="BRI">BRI</option>
                        <!-- Add more options for other banks if needed -->
                    </select>
                </div>
                <button type="button" id="nextStep1" class="px-4 py-2 bg-blue-500 text-white rounded">Next</button>
            </div>

            <div id="step2" class="hidden">
                <h2 class="text-xl font-semibold text-white mb-4">Step 2: Set PIN</h2>
                <div class="mb-4">
                    <label for="pin" class="block text-gray-300 text-sm font-bold mb-2">New PIN (4 digits):</label>
                    <input type="password" id="pin" name="pin" class="border border-gray-500 px-3 py-2 w-full bg-gray-700 text-gray-300" minlength="4" maxlength="4" pattern="\d{4}" title="PIN must be 4 digits">
                </div>
                <button type="button" id="nextStep2" class="px-4 py-2 bg-blue-500 text-white rounded">Next</button>
            </div>

            <div id="step3" class="hidden">
                <h2 class="text-xl font-semibold text-white mb-4">Step 3: Confirm PIN</h2>
                <div class="mb-4">
                    <label for="confirm_pin" class="block text-gray-300 text-sm font-bold mb-2">Confirm PIN:</label>
                    <input type="password" id="confirm_pin" name="confirm_pin" class="border border-gray-500 px-3 py-2 w-full bg-gray-700 text-gray-300">
                </div>
                <button type="submit" id="addCard" class="px-4 py-2 bg-blue-500 text-white rounded">Add Card</button>
            </div>

            <!-- Error Message Display -->
            <div id="error_message" class="text-red-500 mb-4"><?php echo $error_message; ?></div>
        </form>
    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const nextStep1Btn = document.getElementById('nextStep1');
        const nextStep2Btn = document.getElementById('nextStep2');
        const addCardForm = document.getElementById('addCardForm');
        const errorMessage = document.getElementById('error_message');
        const steps = ['step1', 'step2', 'step3'];
        let currentStep = 0;

        // Next buttons for each step
        nextStep1Btn.addEventListener('click', function() {
            const bank = document.getElementById('bank').value;
            if (!bank) {
                displayErrorMessage("Please select a bank.");
                return;
            }
            hideErrorMessage();
            showStep(++currentStep);
        });

        nextStep2Btn.addEventListener('click', function() {
            const pin = document.getElementById('pin').value;
            if (!pin || pin.length !== 4) {
                displayErrorMessage("PIN must be 4 digits.");
                return;
            }
            hideErrorMessage();
            showStep(++currentStep);
        });

        // Prevent form submission on Step 2 when pressing Enter
        addCardForm.addEventListener('submit', function(event) {
            if (currentStep === 1) {
                event.preventDefault();
                const confirm_pin = document.getElementById('confirm_pin').value;
                if (!confirm_pin) {
                    displayErrorMessage("Please confirm your new PIN.");
                    return;
                }

                const pin = document.getElementById('pin').value;
                if (pin !== confirm_pin) {
                    displayErrorMessage("PIN and confirm PIN do not match.");
                    return;
                }

                // If validation passes, proceed to the next step
                hideErrorMessage();
                showStep(++currentStep);
            }
        });

        // Function to show error message
        function displayErrorMessage(message) {
            errorMessage.textContent = message;
        }

        // Function to hide error message
        function hideErrorMessage() {
            errorMessage.textContent = '';
        }

        // Function to switch between form steps
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
