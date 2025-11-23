<?php
header("Content-Type: application/json");
include "db.php"; // your DB connection

$sql = "SELECT * FROM parking_logs ORDER BY id DESC";
$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode([
    "status" => "success",
    "records" => $data
]);
?>