<?php
header('Content-Type: application/json');
include 'db_connect.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['slot'])) {
    $stmt = $conn->prepare("INSERT INTO parking_logs (slot_label, entry_time, exit_time, duration_sec, payment) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssids", $data['slot'], $data['entry_time'], $data['exit_time'], $data['duration_sec'], $data['payment']);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "id" => $conn->insert_id]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
    $stmt->close();
}
$conn->close();
?>