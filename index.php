<?php
include 'session.php';
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

            $.ajax({
                url: 'get_transactions.php',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.error) {
                        $('#transaction-history').html('<tr><td colspan="4">Error: ' + data.error + '</td></tr>');
                    } else {
                        var transactionRows = '';
                        data.forEach(function (transaction) {
                            var date = new Date(transaction.tanggal).toLocaleString('id-ID');
                            var amountClass = transaction.jumlah > 0 ? 'status completed' : 'status pending';
                            var amount = (transaction.jumlah > 0 ? '+ ' : '- ') + 'Rp. ' + Math.abs(transaction.jumlah).toLocaleString('id-ID');
                            transactionRows += `
                                <tr class="border-b-2 border-gray-700">
                                    <td>
                                        <div class="flex flex-col items-center">
                                            <span>${date}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <p>${transaction.deskripsi}</p>
                                    </td>
                                    <td><span class="${amountClass}">${amount}</span></td>
                                    <!-- <td>#${transaction.transaksi_id}</td> --!> 
                                </tr>
                            `;
                        });
                        $('#transaction-history').html(transactionRows);
                    }
                },
                error: function (xhr, status, error) {
                    console.log('Error:', error);
                    $('#transaction-history').html('<tr><td colspan="4">Error loading transactions</td></tr>');
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
            <li><a href="/transaction.php"><i class='bx bx-transfer bx-rotate-90'></i>Transaction</a></li>
            <li><a href="/transfer.php"><i class='bx bx-paper-plane' style='color:#ffffff'  ></i>Transfer</a></li>
            <li><a href="#"><i class='bx bx-credit-card-alt'></i>Card</a></li>
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

    <!-- Main Content -->
    <div class="content">
        <!-- Navbar -->
        <nav>
        <i class='bx bx-menu'></i>
        <h1 class="text-white">Welcome back, <?php echo $_SESSION['nama_depan']; ?> <?php echo $_SESSION['nama_belakang']; ?></h1>
        <p class="text-white">Account Number: <?php echo $_SESSION['account_number']; ?></p>
        <a href="#" class="notif">
                <i class='bx bx-bell'></i>
                <span class="count">12</span>
            </a>
            <a href="#" class="profile">
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
                        <tbody id="transaction-history" class="text-sm">
                            <!-- Transactions will be loaded here via AJAX -->
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
