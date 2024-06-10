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
$title = "Manage Accounts";

// Fetch all accounts
$sql = "SELECT * FROM rekening";
$result = $conn->query($sql);

// Include the layout file
include 'layout_admin.php';
?>

<!-- Page content -->
<div class="container ml-40 text-white">
    <h1 class="text-3xl font-semibold mb-6">Manage Accounts</h1>
    <div class="overflow-x-auto">
        <table class="min-w-full border-collapse">
            <thead>
                <tr>
                    <th class="px-4 py-2 bg-gray-700">Account Number</th>
                    <th class="px-4 py-2 bg-gray-700">User ID</th>
                    <th class="px-4 py-2 bg-gray-700">PIN</th>
                    <th class="px-4 py-2 bg-gray-700">Bank</th>
                    <th class="px-4 py-2 bg-gray-700">Balance</th>
                    <th class="px-4 py-2 bg-gray-700">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='border border-gray-700 px-4 py-2'>{$row['no_rekening']}</td>";
                        echo "<td class='border border-gray-700 px-4 py-2'>{$row['user_id']}</td>";
                        echo "<td class='border border-gray-700 px-4 py-2'>{$row['pin']}</td>";
                        echo "<td class='border border-gray-700 px-4 py-2'>{$row['bank']}</td>";
                        echo "<td class='border border-gray-700 px-4 py-2'>{$row['saldo']}</td>";
                        echo "<td class='border border-gray-700 px-4 py-2'>";
                        echo "<button onclick='openEditModal({$row['id']}, {$row['pin']})' class='px-4 py-2 bg-blue-500 text-white rounded mr-2'>Edit PIN</button>";
                        echo "<button onclick='deleteAccount({$row['id']})' class='px-4 py-2 bg-red-500 text-white rounded'>Delete</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='border px-4 py-2'>No accounts found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Edit PIN Modal -->
<div id="editPinModal" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden">
    <div class="flex items-center justify-center h-full">
        <div class="bg-white p-6 rounded shadow-lg w-1/3">
            <h2 class="text-xl font-semibold mb-4">Edit PIN</h2>
            <form id="editPinForm" action="edit_account.php" method="POST">
                <input type="hidden" id="accountId" name="account_id">
                <div class="mb-4">
                    <label for="editPin" class="block text-sm font-medium text-gray-700">New PIN</label>
                    <input type="text" id="editPin" name="pin" class="border px-3 py-2 w-full" required>
                </div>
                <div class="flex justify-end">
                    <button type="submit" name="update_pin" class="px-4 py-2 bg-blue-500 text-white rounded mr-2">Update PIN</button>
                    <button type="button" onclick="closeEditPinModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditModal(accountId, currentPin) {
        document.getElementById('accountId').value = accountId;
        document.getElementById('editPin').value = currentPin;
        document.getElementById('editPinModal').classList.remove('hidden');
    }

    function closeEditPinModal() {
        document.getElementById('editPinModal').classList.add('hidden');
    }

    function deleteAccount(accountId) {
        if (confirm('Are you sure you want to delete this account?')) {
            // Send AJAX request to delete account
            $.ajax({
                url: 'delete_account.php',
                type: 'POST',
                data: {
                    account_id: accountId
                },
                success: function(response) {
                    // Reload the page after successful deletion
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error deleting account:', error);
                    // Display error message
                    alert('Error deleting account. Please try again later.');
                }
            });
        }
    }
</script>

