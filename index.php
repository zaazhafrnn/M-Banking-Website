<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Include database connection
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>M-Banking</title>
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
    <script>
        $(document).ready(function () {
            $.ajax({
                url: 'get_balance.php',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.error) {
                        $('#balance').text('Error: ' + data.error);
                    } else {
                        $('#balance').text(data.saldo.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }));
                    }
                },
                error: function (xhr, status, error) {
                    console.log('Error:', error);
                    $('#balance').text('Error loading balance');
                }
            });
        });
    </script>
</head>

<body class="dark">
    <!-- Sidebar -->
    <div class="sidebar">
        <a href="#" class="logo">
            <div class="logo-name ml-3"><span>M-</span>Banking</div>
        </a>
        <ul class="side-menu">
            <li class="active"><a href=""><i class='bx bxs-dashboard'></i>Home</a></li>
            <li><a href="#"><i class='bx bx-receipt'></i>History</a></li>
            <li><a href="#"><i class='bx bx-wallet'></i>Wallet</a></li>
            <li><a href="/transaction.php"><i class='bx bx-transfer'></i>Transaction</a></li>
            <li><a href="#"><i class='bx bx-credit-card-alt'></i>Card</a></li>
            <li><a href="#"><i class='bx bx-group'></i>Friends</a></li>
            <li><a href="#"><i class='bx bx-cog'></i>Settings</a></li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="" class="logout">
                    <i class='bx bx-log-out-circle'></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>
    <!-- End of Sidebar -->

    <!-- Main Content -->
    <div class="content">
        <!-- Navbar -->
        <nav>
            <i class='bx bx-menu'></i>
            <h1 class="text-white">Welcome back, <?php echo $_SESSION['nama_depan']; ?> <?php echo $_SESSION['nama_belakang']; ?></h1>
            <a href="#" class="notif">
                <i class='bx bx-bell'></i>
                <span class="count">12</span>
            </a>
            <a href="#" class="profile">
                <img src="images/logo.png">
            </a>
        </nav>
        <!-- End of Navbar -->

        <main>
            <div class="header">
                <div class="left">
                    <h1>Overview</h1>
                    <ul class="breadcrumb">
                        /
                        <li><a href="#" class="active">Home</a></li>
                    </ul>
                </div>
            </div>

            <h1 class="text-white text-4xl mt-8">Your Balance Rp. <span id="balance">Loading...</span></h1>

            <div class="bottom-data">
                <div class="history">
                    <div class="header">
                        <i class='bx bx-receipt'></i>
                        <h3>Last Transaction</h3>
                        <i class='bx bx-filter'></i>
                        <i class='bx bx-search'></i>
                    </div>
                    <table class="w-full">
                        <thead>
                            <!-- <tr>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Allocation</th>
                            </tr> -->
                        </thead>
                        <tbody class="text-sm">
                            <tr class="border-b-2 border-gray-700">
                                <td>
                                    <div class="flex flex-col items-center">
                                        <span>Today</span>
                                        <span>12.30PM</span>
                                    </div>
                                </td>
                                <td>
                                    <p>Deposit</p>
                                </td>
                                <td><span class="status completed">+ Rp. 1000,00</span></td>
                            </tr>
                            <tr class="border-b-2 border-gray-700">
                                <td>
                                    <div class="flex flex-col items-center">
                                        <span>Yesterday</span>
                                        <span>14.10PM</span>
                                    </div>
                                </td>
                                <td>
                                    <p>Payment Shopping</p>
                                </td>
                                <td><span class="status pending">- Rp. 2000,00</span></td>
                            </tr>
                            <tr class="border-b-2 border-gray-700">
                                <td>
                                    <div class="flex flex-col items-center">
                                        <span>Wednesday</span>
                                        <span>10.00PM</span>
                                    </div>
                                </td>
                                <td>
                                    <p>Received Transfer</p>
                                </td>
                                <td><span class="status process">+ Rp. 10.000,00</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
