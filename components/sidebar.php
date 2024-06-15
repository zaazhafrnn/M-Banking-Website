<?php
// Get the current file name
$current_page = basename($_SERVER['PHP_SELF']);

// Check if the current page is part of a transaction or transfer process
$is_transaction_page = in_array($current_page, ['transaction.php', 'deposit.php', 'withdrawal.php', 'transaction_amount.php', 'transaction_account.php', 'transaction_pin.php']);
$is_transfer_page = ($current_page == 'transfer.php');
?>

<div class="sidebar">
    <a href="#" class="logo">
        <div class="logo-name ml-3"><span>M-</span>Banking</div>
    </a>
    <ul class="side-menu">
        <li class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>"><a href="index.php"><i class='bx bxs-dashboard'></i>Home</a></li>
        <li class="<?php echo ($current_page == 'history.php') ? 'active' : ''; ?>"><a href="history.php"><i class='bx bx-receipt'></i>History</a></li>
        <li class="<?php echo ($current_page == 'cardsaving.php') ? 'active' : ''; ?>"><a href="cardsaving.php"><i class='bx bx-wallet'></i>Card & Saving</a></li>
        <li class="<?php echo $is_transaction_page ? 'active' : ''; ?>">
            <a href="transaction.php"><i class='bx bx-transfer bx-rotate-90'></i>Transaction</a>
        </li>
        <li class="<?php echo $is_transfer_page ? 'active' : ''; ?>"><a href="transfer.php"><i class='bx bx-paper-plane'></i>Transfer</a></li>
        <!-- <li class="<?php echo ($current_page == 'friends.php') ? 'active' : ''; ?>"><a href="friends.php"><i class='bx bx-group'></i>Friends</a></li> -->
        <!-- <li class="<?php echo ($current_page == 'settings.php') ? 'active' : ''; ?>"><a href="settings.php"><i class='bx bx-cog'></i>Settings</a></li> -->
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
