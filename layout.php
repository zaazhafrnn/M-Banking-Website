<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <title><?php echo $title; ?> - M-Banking</title>
    <style>
        body { opacity: 0; }
        body.dark { opacity: 1; transition: opacity 0.3s; }
    </style>
</head>
<body class="dark">
    <?php include 'components/sidebar.php'; ?>

    <div class="content">
        <?php include 'components/navbar.php'; ?>
        <?php echo $content; ?>
    </div>

    <script src="index.js"></script>
    <script>
        $(document).ready(function () {
            document.body.classList.add('dark');
            document.body.style.opacity = '1';

            // AJAX request to fetch transactions
            $.ajax({
                url: '/get_transactions.php',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    // console.log(data);
                    if (data.error) {
                        $('#transaction-history').html('<tr><td colspan="4" class="py-4 text-center text-gray-400">' + data.error + '</td></tr>');
                    } else {
                        var transactionRows = '';
                        data.forEach(function (transaction) {
                            var date = new Date(transaction.tanggal).toLocaleString('id-ID', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                            var amountClass = transaction.jumlah > 0 ? 'text-green-500' : 'text-red-500';
                            var amount = (transaction.jumlah > 0 ? '+ ' : '- ') + 'Rp. ' + Math.abs(transaction.jumlah).toLocaleString('id-ID');
                            transactionRows += `
                                <tr class="border-b border-gray-700">
                                    <td class="py-3">
                                        <div class="flex flex-col items-center">
                                            <span class="text-gray-300">${date}</span>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <p class="text-white">${transaction.deskripsi}</p>
                                        <p class="text-gray-400 text-sm">${transaction.bank} - ${transaction.no_rekening}</p>
                                    </td>
                                    <td class="py-3"><span class="${amountClass} font-semibold">${amount}</span></td>
                                </tr>
                            `;
                        });
                        $('#transaction-history').html(transactionRows);
                    }
                },
                error: function (xhr, status, error) {
                    console.log('Error:', error);
                    $('#transaction-history').html('<tr><td colspan="4" class="py-4 text-center text-red-500">Error loading transactions</td></tr>');
                }
            });

            const toggler = document.getElementById('theme-toggle');
            toggler.addEventListener('change', function () {
                document.body.classList.toggle('dark');
            });
        });
    </script>
</body>
</html>
