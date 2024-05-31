<?php
// Get the current file name
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">
    <a href="#" class="logo">
        <div class="logo-name ml-3"><span>M-</span>Banking</div>
    </a>
    <ul class="side-menu">
        <li class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>"><a href="index.php"><i class='bx bxs-dashboard'></i>Home</a></li>
        <li class="<?php echo ($current_page == 'history.php') ? 'active' : ''; ?>"><a href="history.php"><i class='bx bx-receipt'></i>History</a></li>
        <li class="<?php echo ($current_page == 'wallet.php') ? 'active' : ''; ?>"><a href="wallet.php"><i class='bx bx-wallet'></i>Wallet</a></li>
        <li class="<?php echo in_array($current_page, ['transaction.php', 'deposit.php', 'withdrawal.php']) ? 'active' : ''; ?>">
            <a href="transaction.php"><i class='bx bx-transfer bx-rotate-90'></i>Transaction</a>
        </li>
        <li class="<?php echo ($current_page == 'transfer.php') ? 'active' : ''; ?>"><a href="transfer.php"><i class='bx bx-paper-plane'></i>Transfer</a></li>
        <li class="<?php echo ($current_page == 'cards.php') ? 'active' : ''; ?>"><a href="cards.php"><i class='bx bx-credit-card-alt'></i>My Cards</a></li>
        <li class="<?php echo ($current_page == 'friends.php') ? 'active' : ''; ?>"><a href="friends.php"><i class='bx bx-group'></i>Friends</a></li>
        <li class="<?php echo ($current_page == 'settings.php') ? 'active' : ''; ?>"><a href="settings.php"><i class='bx bx-cog'></i>Settings</a></li>
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
