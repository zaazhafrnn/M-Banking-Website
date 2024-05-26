<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include 'db.php';

// Start the session
session_start();

if (!isset($_SESSION['account_number'])) {
    echo json_encode(array('error' => 'Not logged in'));
    exit;
}

// Get the account number from the session
$account_number = $_SESSION['account_number'];

// Fetch transactions for the given account number
$sql = "SELECT t.transaksi_id, t.no_rekening, t.tipe_transaksi, t.jumlah, t.deskripsi, t.tanggal
        FROM transaksi t
        JOIN rekening r ON t.no_rekening = r.id
        WHERE r.no_rekening = ?
        ORDER BY t.tanggal DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $account_number);
$stmt->execute();
$result = $stmt->get_result();

$transactions = array();
while ($row = $result->fetch_assoc()) {
    $transactions[] = $row;
}

echo json_encode($transactions);

$stmt->close();
$conn->close();
?>
