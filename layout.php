<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <title><?php echo $title; ?> - M-Banking</title>
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

<?php include 'components/header.php'; ?>
<?php include 'components/sidebar.php'; ?>

<div class="content">
    <?php include 'components/navbar.php'; ?>
    <?php echo $content; ?>
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
