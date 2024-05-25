<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include 'db.php';

$no_rekening = 550001;
$sql = "SELECT saldo FROM rekening WHERE no_rekening = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die(json_encode(array('error' => $conn->error)));
}

$stmt->bind_param("i", $no_rekening);
if (!$stmt->execute()) {
    die(json_encode(array('error' => $stmt->error)));
}

$stmt->bind_result($saldo);
$stmt->fetch();
if (is_null($saldo)) {
    echo json_encode(array('error' => 'No balance found for the account number'));
} else {
    echo json_encode(array('saldo' => $saldo));
}

$stmt->close();
$conn->close();
?>
