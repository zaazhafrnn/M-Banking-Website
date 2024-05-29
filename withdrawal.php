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
    <title>Withdrawal - M-Banking</title>
    <style>
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
            <li><a href=""><i class='bx bx-paper-plane' style='color:#ffffff'></i>Transfer</a></li>
            <li><a href=""><i class='bx bx-credit-card-alt'></i>My Cards</a></li>
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
            <h1 class="text-white">Withdrawal</h1>
            <a href="#" class="notif">
                <i class='bx bx-bell'></i>
                <span class="count">12</span>
            </a>
            <a href="#" class="profile"></a>
        </nav>
        <!-- End of Navbar -->

        <main>
            <div class="header">
                <div class="left">
                    <h1>Withdrawal</h1>
                </div>
            </div>

            <form action="process_transaction.php" method="POST" class="bottom-data justify-center text-center">
                <input
                    type="number"
                    id="amount"
                    name="amount"
                    class="w-5/12 bg-gray-700 p-5 rounded-full text-white"
                    placeholder="Amount"
                    required
                />
                <input type="hidden" name="account_number" value="<?php echo $account_number; ?>">
                <input type="hidden" name="type" value="withdrawal">
                <button
                    type="submit"
                    id="confirm-withdrawal"
                    class="w-5/12 bg-red-500 text-white py-2 px-4 rounded-full mt-5"
                >
                    Withdrawal
                </button>
            </form>
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
