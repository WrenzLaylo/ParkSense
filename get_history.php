<?php
header('Content-Type: application/json');
include 'db.php';

$sql = "SELECT * FROM parking_history ORDER BY id DESC";
$result = $conn->query($sql);

$history = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $history[] = $row;
    }
}

echo json_encode($history);
$conn->close();
?>