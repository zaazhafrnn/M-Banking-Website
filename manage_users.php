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
$title = "Manage Users";

// Fetch all users
$sql = "SELECT id, nik, nama_depan, nama_belakang, alamat, no_telp, password FROM users";
$result = $conn->query($sql);

// Check if form is submitted for updating user details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {
        // Retrieve data from form
        $user_id = $_POST['user_id'];
        $nama_depan = $_POST['nama_depan'];
        $nama_belakang = $_POST['nama_belakang'];
        $alamat = $_POST['alamat'];
        $no_telp = $_POST['no_telp'];
        $password = $_POST['password'];

        // Update user details in database
        $update_sql = "UPDATE users SET nama_depan='$nama_depan', nama_belakang='$nama_belakang', alamat='$alamat', no_telp='$no_telp', password='$password' WHERE id=$user_id";
        if ($conn->query($update_sql) === TRUE) {
            // Redirect to manage_users.php with success message
            header("Location: manage_users.php?status=success&message=User details updated successfully");
            exit();
        } else {
            // Redirect to manage_users.php with error message
            header("Location: manage_users.php?status=error&message=Error updating user details: " . $conn->error);
            exit();
        }
    }
}

// Include the layout file
include 'layout_admin.php';
?>

<!-- Page content -->
<div class="container ml-40 text-white">
    <h1 class="text-3xl font-semibold mb-6">Manage Users</h1>
    <?php
    // Display success or error message if any
    if (isset($_GET['status'])) {
        $status = $_GET['status'];
        $message = $_GET['message'];
        $alertClass = ($status === 'success') ? 'bg-green-500' : 'bg-red-500';
        echo "<div class='py-2 px-4 mb-4 text-white rounded notification $alertClass'>$message</div>";
    }
    ?>
    <div class="overflow-x-auto">
        <table class="min-w-full border-collapse">
            <thead>
                <tr>
                    <th class="px-4 py-2 bg-gray-700">NIK</th>
                    <th class="px-4 py-2 bg-gray-700">First Name</th>
                    <th class="px-4 py-2 bg-gray-700">Last Name</th>
                    <th class="px-4 py-2 bg-gray-700">Address</th>
                    <th class="px-4 py-2 bg-gray-700">Phone</th>
                    <th class="px-4 py-2 bg-gray-700">Password</th>
                    <th class="px-4 py-2 bg-gray-700">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='border border-gray-700 px-4 py-2'>{$row['nik']}</td>";
                        echo "<td class='border border-gray-700 px-4 py-2'>{$row['nama_depan']}</td>";
                        echo "<td class='border border-gray-700 px-4 py-2'>{$row['nama_belakang']}</td>";
                        echo "<td class='border border-gray-700 px-4 py-2'>{$row['alamat']}</td>";
                        echo "<td class='border border-gray-700 px-4 py-2'>{$row['no_telp']}</td>";
                        echo "<td class='border border-gray-700 px-4 py-2'>{$row['password']}</td>";
                        echo "<td class='border border-gray-700 px-4 py-2'>";
                        echo "<button onclick='openEditModal({$row['id']}, \"{$row['nama_depan']}\", \"{$row['nama_belakang']}\", \"{$row['alamat']}\", \"{$row['no_telp']}\", \"{$row['password']}\")' class='px-4 py-2 bg-blue-500 text-white rounded mr-2'>Edit</button>";
                        echo "<button onclick='deleteUser({$row['id']})' class='px-4 py-2 bg-red-500 text-white rounded'>Delete</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='border px-4 py-2'>No users found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden">
    <div class="flex items-center justify-center h-full">
        <div class="bg-white p-6 rounded shadow-lg w-1/2">
            <h2 class="text-xl font-semibold mb-4">Edit User Details</h2>
            <form id="editUserForm" action="" method="POST">
                <input type="hidden" id="editUserId" name="user_id">
                <div class="mb-4">
                    <label for="editNamaDepan" class="block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" id="editNamaDepan" name="nama_depan" class="border px-3 py-2 w-full" required>
                </div>
                <div class="mb-4">
                    <label for="editNamaBelakang" class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" id="editNamaBelakang" name="nama_belakang" class="border px-3 py-2 w-full" required>
                </div>
                <div class="mb-4">
                    <label for="editAlamat" class="block text-sm font-medium text-gray-700">Address</label>
                    <input type="text" id="editAlamat" name="alamat" class="border px-3 py-2 w-full" required>
                </div>
                <div class="mb-4">
                    <label for="editTelepon" class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" id="editTelepon" name="no_telp" class="border px-3 py-2 w-full" required>
                </div>
                <div class="mb-4">
                    <label for="editPassword" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="text" id="editPassword" name="password" class="border px-3 py-2 w-full" required>
                </div>
                <div class="flex justify-end">
                    <button type="submit" name="update" class="px-4 py-2 bg-blue-500 text-white rounded mr-2">Update</button>
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditModal(userId, namaDepan, namaBelakang, alamat, noTelp, password) {
        document.getElementById('editUserId').value = userId;
        document.getElementById('editNamaDepan').value = namaDepan;
        document.getElementById('editNamaBelakang').value = namaBelakang;
        document.getElementById('editAlamat').value = alamat;
        document.getElementById('editTelepon').value = noTelp;
        document.getElementById('editPassword').value = password;
        document.getElementById('editUserModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editUserModal').classList.add('hidden');
    }

    function deleteUser(userId) {
        if (confirm('Are you sure you want to delete this user?')) {
            // Send AJAX request to delete user
            $.ajax({
                url: 'delete_user.php',
                type: 'POST',
                data: {
                    user_id: userId
                },
                success: function(response) {
                    // Reload the page after successful deletion
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error deleting user:', error);
                    // Display error message
                    alert('Error deleting user. Please try again later.');
                }
            });
        }
    }

    $(document).ready(function() {
        // Remove notification message after 3 seconds
        setTimeout(function() {
            $('.notification').fadeOut('slow');
        }, 3000);
    });
</script>
