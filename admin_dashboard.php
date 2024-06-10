<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.php");
    exit();
}

// Set the title for the admin dashboard
$title = "Admin Dashboard";

ob_start();
?>

<main class="flex flex-col h-screen">
    <div class="header">
        <div class="left">
            <h1>Admin Dashboard</h1>
            <ul class="breadcrumb">
                /
                <li><a href="#" class="active">Admin Dashboard</a></li>
            </ul>
        </div>
    </div>

    <div class="flex flex-grow justify-center items-center">
        <div class="w-full h-full flex flex-wrap justify-center gap-8 mt-10">
            <!-- Button to manage users -->
            <a href="manage_users.php" class="w-96 p-5 border-2 rounded-2xl h-full bg-gray-800 text-white flex flex-col items-center justify-center text-4xl">
                <div class="flex items-center space-x-4 mb-8">
                    <i class='bx bx-user text-6xl'></i>
                </div>
                Manage Users
            </a>
            <!-- Button to manage accounts -->
            <a href="manage_accounts.php" class="w-96 p-5 border-2 rounded-2xl h-full bg-gray-800 text-white flex flex-col items-center justify-center text-4xl">
                <div class="flex items-center space-x-4 mb-8">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                    </svg>
                </div>
                Manage Accounts
            </a>
            <!-- Button to view all transactions -->
            <a href="view_transactions.php" class="w-96 p-5 border-2 rounded-2xl h-full bg-gray-800 text-white flex flex-col items-center justify-center text-4xl">
                <div class="flex items-center space-x-4 mb-8">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                    </svg>
                </div>
                View Transactions
            </a>
        </div>
    </div>
</main>

<?php
$content = ob_get_clean();
include 'layout_admin.php';
?>