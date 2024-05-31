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
