<?php
header('Content-Type: application/json');
include 'db.php';

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['status' => 'error', 'message' => 'No data received']);
    exit;
}

$slot = $conn->real_escape_string($data['slot']);

// FIX: Convert JS ISO Date (2023-11-23T...) to MySQL Date (2023-11-23 ...)
$entry_time = date("Y-m-d H:i:s", strtotime($data['entry_time']));
$exit_time = date("Y-m-d H:i:s", strtotime($data['exit_time']));

$duration_sec = (int) $data['duration_sec'];
$payment = (float) $data['payment']; // Use float for money

$sql = "INSERT INTO parking_history (slot, entry_time, exit_time, duration_sec, payment)
        VALUES ('$slot','$entry_time','$exit_time',$duration_sec,$payment)";

error_log("SQL: $sql"); // Add this to debug

if ($conn->query($sql)) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $conn->error]);
}


$conn->close();
?>