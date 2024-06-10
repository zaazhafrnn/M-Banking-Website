<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.php");
    exit();
}

// Set the title for the admin dashboard
$title = "Admin Dashboard";

?>

<?php ob_start(); ?>
<!-- Admin dashboard content -->
<div class="container text-white">
    <h1>Welcome to the Admin Dashboard!</h1>
    <p>You can manage users, view reports, and perform other administrative tasks here.</p>
</div>
<?php $content = ob_get_clean(); ?>

<?php include 'layout_admin.php'; ?>
