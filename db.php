<?php
$host = "localhost";
$user = "root";       // Default XAMPP user
$pass = "";           // Default XAMPP password is empty
$db = "smart_parking";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}
?>