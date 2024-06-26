<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db.php';

    $status = isset($_GET['status']) ? $_GET['status'] : '';
    $message = isset($_GET['message']) ? urldecode($_GET['message']) : '';

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the provided username and password are "admin"
    if ($username === "admin" && $password === "admin") {
        // If admin credentials, set admin session flag
        $_SESSION['is_admin'] = true;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        // Otherwise, proceed with regular user login
        $sql = "SELECT id, nik, nama_depan, nama_belakang FROM users WHERE nik = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $stmt->bind_result($user_id, $nik, $nama_depan, $nama_belakang);
        $stmt->fetch();
        $stmt->close();

        if ($user_id) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['nik'] = $nik;
            $_SESSION['nama_depan'] = $nama_depan;
            $_SESSION['nama_belakang'] = $nama_belakang;

            header("Location: index.php");
            exit();
        } else {
            $error_message = "Invalid username or password.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                        Login to your account
                    </h1>
                    <?php if (!empty($success_message)) : ?>
                        <div class="mt-4 text-green-600 dark:text-green-400">
                            <?php echo $success_message; ?>
                        </div>
                    <?php endif; ?>
                    <form class="space-y-4 md:space-y-6" action="" method="POST">
                        <div>
                            <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your Username</label>
                            <input type="text" name="username" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="username" required="">
                        </div>
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                            <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
                        </div>
                        <button type="submit" class="w-full text-white bg-blue-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Sign in</button>
                    </form>
                    <?php if (isset($error_message)) : ?>
                        <div class="mt-4 text-red-600 dark:text-red-400">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>
                    <div class="mt-4 text-center">
                        <span class="text-white">Don't have an account?<a href="sign_up.php" class="text-blue-600 hover:underline dark:text-blue-500"> Sign up</a></span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>