<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'db.php';

// Set the title for the page
$title = "Transaction History";

// Fetch transaction history
$sql = "SELECT * FROM transaksi";
$result = $conn->query($sql);

// Include the layout file
include 'layout_admin.php';
?>

<!-- Page content -->
<div class="container ml-40 text-white">
    <h1 class="text-3xl font-semibold mb-6">Transaction History</h1>
    <div class="overflow-x-auto">
        <table class="min-w-full border-collapse">
            <thead>
                <tr>
                    <th class="px-4 py-2 bg-gray-700">Transaction ID</th>
                    <th class="px-4 py-2 bg-gray-700">Account Number</th>
                    <th class="px-4 py-2 bg-gray-700">Transaction Type</th>
                    <th class="px-4 py-2 bg-gray-700">Amount</th>
                    <th class="px-4 py-2 bg-gray-700">Description</th>
                    <th class="px-4 py-2 bg-gray-700">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='border border-gray-700 px-4 py-2'>{$row['transaksi_id']}</td>";
                        echo "<td class='border border-gray-700 px-4 py-2'>{$row['no_rekening']}</td>";
                        echo "<td class='border border-gray-700 px-4 py-2'>{$row['tipe_transaksi']}</td>";
                        echo "<td class='border border-gray-700 px-4 py-2'>{$row['jumlah']}</td>";
                        echo "<td class='border border-gray-700 px-4 py-2'>{$row['deskripsi']}</td>";
                        echo "<td class='border border-gray-700 px-4 py-2'>{$row['tanggal']}</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='border px-4 py-2'>No transaction history found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
