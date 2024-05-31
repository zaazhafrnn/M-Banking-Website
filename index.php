<?php
include 'session.php';

$status = isset($_GET['status']) ? $_GET['status'] : '';
$message = isset($_GET['message']) ? urldecode($_GET['message']) : '';

$title = "Home";
ob_start();
?>

<?php if ($status && $message): ?>
    <div class="message <?php echo htmlspecialchars($status); ?>">
        <p><?php echo htmlspecialchars($message); ?></p>
    </div>
    <script>
        setTimeout(function() {
            document.querySelector('.message').style.display = 'none';
        }, 5000);
    </script>
<?php endif; ?>

<main>
    <div class="header">
        <div class="left">
            <h1>Overview</h1>
            <ul class="breadcrumb">
                /
                <li><a href="#" class="active">Home</a></li>
            </ul>
        </div>
    </div>

    <h1 class="text-white text-4xl mt-8">Your Balance Rp. <span id="balance">Loading...</span></h1>

    <div class="bottom-data">
        <div class="history">
            <div class="header">
                <i class='bx bx-receipt'></i>
                <h3>Last Transaction</h3>
                <i class='bx bx-filter'></i>
                <i class='bx bx-search'></i>
            </div>
            <table class="w-full">
                <thead></thead>
                <tbody id="transaction-history" class="text-sm">
                    <!-- Transactions will be loaded here via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
