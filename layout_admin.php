<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <title><?php echo $title; ?> - Admin Dashboard</title>
    <style>
        body { opacity: 0; }
        body.dark { opacity: 1; transition: opacity 0.3s; }
    </style>
</head>
<body class="dark">
    <?php include 'components/sidebar_admin.php'; ?>

    <div class="content">
        <?php include 'components/navbar.php'; ?>
        <?php echo $content; ?>
    </div>

    <script src="index.js"></script>
    <script>
        $(document).ready(function () {
            document.body.classList.add('dark');
            document.body.style.opacity = '1';

            // Add your admin-specific scripts here
        });
    </script>
</body>
</html>
