<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">
    <a href="#" class="logo">
        <div class="logo-name ml-3"><span>M-</span>Banking Admin</div>
    </a>
    <ul class="side-menu">
        <li class="<?php echo ($current_page == 'admin_dashboard.php') ? 'active' : ''; ?>"><a href="admin_dashboard.php"><i class='bx bxs-dashboard'></i>Admin Home</a></li>
        <li class="<?php echo ($current_page == 'admin_users.php') ? 'active' : ''; ?>"><a href="admin_users.php"><i class='bx bx-user'></i>Manage Users</a></li>
        <!-- Add more admin-specific links here -->
    </ul>
    <ul class="side-menu">
        <li>
            <a href="logout.php" class="logout">
                <i class='bx bx-log-out-circle'></i>
                Logout
            </a>
        </li>
    </ul>
</div>
