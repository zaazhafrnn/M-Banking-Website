<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php';

// Get the account number from the session
if (!isset($_SESSION['account_number'])) {
    echo json_encode(['error' => 'Account number not set in session']);
    exit;
}

$account_number = $_SESSION['account_number'];

// Prepare SQL statement to fetch transactions for the account number
$sql = "
    SELECT t.id, t.transaksi_id, t.no_rekening, t.tipe_transaksi, t.jumlah, t.deskripsi, t.tanggal
    FROM transaksi t
    JOIN rekening r ON t.no_rekening = r.id
    WHERE r.no_rekening = ?
    ORDER BY t.tanggal DESC
    LIMIT 10";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode(['error' => 'Failed to prepare statement']);
    exit;
}
$stmt->bind_param("i", $account_number);
$stmt->execute();
$result = $stmt->get_result();

// Fetch all transactions
$transactions = $result->fetch_all(MYSQLI_ASSOC);

// Close statement and connection
$stmt->close();
$conn->close();

// Return transactions as JSON
header('Content-Type: application/json');
echo json_encode($transactions);
?>
