<?php
include 'session.php';

// Get the account number from the session
$account_number = $_SESSION['account_number'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Transaction - M-Banking</title>
    <style>
        /* Ensure dark mode is applied by default */
        body {
            opacity: 0;
        }
        body.dark {
            opacity: 1;
            transition: opacity 0.3s;
        }
    </style>
</head>
<body class="dark">
    <!-- Main Content -->
    <div class="content">
        <!-- Navbar -->
        <nav>
            <i class='bx bx-menu'></i>
            <h1 class="text-white">Transaction</h1>
            <a href="#" class="notif">
                <i class='bx bx-bell'></i>
                <span class="count">12</span>
            </a>
            <a href="#" class="profile">
                <!-- <img src="images/logo.png"> -->
            </a>
        </nav>
        <!-- End of Navbar -->

        <!-- Sidebar -->
        <div class="sidebar">
            <a href="#" class="logo">
                <div class="logo-name ml-3"><span>M-</span>Banking</div>
            </a>
            <ul class="side-menu">
                <li><a href="/index.php"><i class='bx bxs-dashboard'></i>Home</a></li>
                <li><a href="#"><i class='bx bx-receipt'></i>History</a></li>
                <li><a href="#"><i class='bx bx-wallet'></i>Wallet</a></li>
                <li class="active"><a href="/transaction.php"><i class='bx bx-transfer bx-rotate-90'></i>Transaction</a></li>
                <li><a href="/transfer.php"><i class='bx bx-paper-plane' style='color:#ffffff'></i>Transfer</a></li>
                <li><a href="#"><i class='bx bx-credit-card-alt'></i>My Cards</a></li>
                <li><a href="#"><i class='bx bx-group'></i>Friends</a></li>
                <li><a href="#"><i class='bx bx-cog'></i>Settings</a></li>
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
        <!-- End of Sidebar -->

        <main>
            <div class="header">
                <div class="left">
                    <h1>Transaction</h1>
                    <ul class="breadcrumb">
                        /
                        <li><a href="#" class="active">Transaction</a></li>
                    </ul>
                </div>
            </div>

            <div class="bottom-data justify-center text-center">
                <h2 class="text-white text-2xl mb-5">Choose Transaction Type</h2>
                <a href="deposit.php" class="w-5/12 bg-blue-500 text-white py-2 px-4 rounded-full mt-5 inline-block">
                    Deposit
                </a>
                <a href="withdrawal.php" class="w-5/12 bg-red-500 text-white py-2 px-4 rounded-full mt-5 inline-block">
                    Withdrawal
                </a>
            </div>
        </main>
    </div>

    <script src="index.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.body.classList.add('dark');
            document.body.style.opacity = '1';
        });

        const toggler = document.getElementById('theme-toggle');

        toggler.addEventListener('change', function () {
            if (this.checked) {
                document.body.classList.add('dark');
            } else {
                document.body.classList.remove('dark');
            }
        });
    </script>
</body>
</html>
