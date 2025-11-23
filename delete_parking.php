<?php
header('Content-Type: application/json');
include 'db.php';

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['slot']) || !isset($data['entry_time'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    exit;
}

$slot = $conn->real_escape_string($data['slot']);
$entry_time = date("Y-m-d H:i:s", strtotime($data['entry_time']));

// Delete the row
$sql = "DELETE FROM parking_history WHERE slot='$slot' AND entry_time='$entry_time' LIMIT 1";

if ($conn->query($sql)) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $conn->error]);
}

$conn->close();
?>