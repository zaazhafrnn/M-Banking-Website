<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include 'db.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(array('error' => 'Not logged in'));
    exit;
}

$user_id = $_SESSION['user_id'];

// No need to fetch account numbers separately
$sql_transactions = "
    SELECT 
        t.transaksi_id, 
        r.no_rekening, 
        t.tipe_transaksi, 
        t.jumlah, 
        t.deskripsi, 
        t.tanggal,
        r.bank
    FROM 
        transaksi t
        JOIN rekening r ON t.no_rekening = r.id
    WHERE 
        r.user_id = ?
    ORDER BY 
        t.tanggal DESC
    LIMIT 10";

$stmt_transactions = $conn->prepare($sql_transactions);
$stmt_transactions->bind_param("i", $user_id);
$stmt_transactions->execute();
$result_transactions = $stmt_transactions->get_result();

$transactions = array();
while ($row = $result_transactions->fetch_assoc()) {
    $transactions[] = $row;
}

$stmt_transactions->close();

echo json_encode(empty($transactions) ? array('error' => 'No transactions found') : $transactions);

$conn->close();
?>