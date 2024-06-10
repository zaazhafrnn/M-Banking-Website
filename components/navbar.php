<nav>
    <i class='bx bx-menu'></i>
    <?php
    // Check if the user is logged in as admin
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
        echo "<h1 class='text-white'>Welcome back, Admin</h1>";
    } else {
        echo "<h1 class='text-white'>Welcome back, {$_SESSION['nama_depan']} {$_SESSION['nama_belakang']}</h1>";
    }
    ?>
    <a href="#" class="notif">
        <i class='bx bx-bell'></i>
        <span class="count">12</span>
    </a>
    <a href="#" class="profile">
    </a>
</nav>
