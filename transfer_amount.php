<?php
include 'session.php';

$title = "Transfer Amount";

if (!isset($_SESSION['transfer'])) {
  header("Location: transfer.php");
  exit();
}

$recipient_name = $_SESSION['transfer']['recipient_name'] ?? '';
$recipient_account = $_SESSION['transfer']['recipient_account'] ?? '';
$recipient_id = $_SESSION['transfer']['recipient_id'] ?? '';
$bank = $_SESSION['transfer']['bank'] ?? '';

// Check if POST request is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Check if 'amount' is set in POST data
  if (isset($_POST['amount'])) {
    $amount = $_POST['amount'];
    if (empty($amount)) {
      // Handle error: amount is required
      header("Location: transfer_amount.php?error=amount_required");
      exit();
    }

    $_SESSION['transfer']['amount'] = $amount;
    header("Location: confirm_account.php");
    exit();
  }
}

ob_start();
?>

<main class="min-h-full bg-gray-100 dark:bg-gray-900">
  <div class="header">
    <div class="left">
      <h1>Transfer</h1>
      <ul class="breadcrumb">
        /
        <li><a href="#" class="active">Transfer</a></li>
      </ul>
    </div>
  </div>

  <div class="container mx-auto px-4 mx-auto">
    <div class="max-w-lg mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
      <div class="progress-bar mt-1">
        <div class="w-full bg-gray-200 rounded-full h-2.5">
          <div class="bg-blue-600 h-2.5 rounded-full" style="width: 56%;"></div>
        </div>
        <div class="flex justify-between text-sm text-gray-500 mt-1 mb-8">
          <div>Input Amount</div>
          <div>Step 3 of 5</div>
        </div>
      </div>
      <div class="header mb-6">
        <h1 class="text-2xl font-bold text-white">Transfer Amount</h1>
      </div>
      <div class="mb-4 dark:text-white">
        <p><strong>Recipient Name:</strong> <?php echo htmlspecialchars($recipient_name); ?></p>
        <p><strong>Recipient Account:</strong> <?php echo htmlspecialchars($recipient_account); ?></p>
        <p><strong>Bank:</strong> <?php echo htmlspecialchars($bank); ?></p>
      </div>
      <form action="confirm_account.php" method="POST">
        <input type="hidden" name="type" value="transfer">
        <input type="hidden" name="recipient_account" value="<?php echo htmlspecialchars($recipient_account); ?>">
        <input type="hidden" name="recipient_id" value="<?php echo htmlspecialchars($recipient_id); ?>">
        <input type="hidden" name="recipient_name" value="<?php echo htmlspecialchars($recipient_name); ?>">
        <input type="hidden" name="bank" value="<?php echo htmlspecialchars($bank); ?>">
        <div class="mb-4">
          <label for="amount" class="block text-sm font-medium text-gray-300">Amount:</label>
          <input type="text" id="amount" name="amount" required class="mt-1 p-2 border rounded-md w-full">
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 mt-6">Next</button>
      </form>
    </div>
  </div>
</main>


<?php
$content = ob_get_clean();
include 'layout.php';
?>