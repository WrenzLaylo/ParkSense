<?php
header('Content-Type: application/json');
include 'db.php';

$sql = "SELECT SUM(payment) as total FROM parking_history";
$result = $conn->query($sql);
$total = 0;

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total = $row['total'] ?? 0;
}

echo json_encode(['total' => $total]);
$conn->close();
?>