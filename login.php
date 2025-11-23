<?php
session_start();
header('Content-Type: application/json');

// 1. Database Configuration
$host = "localhost";
$dbuser = "root";      // Default XAMPP/WAMP user
$dbpass = "";          // Default XAMPP/WAMP password (usually empty)
$dbname = "smart_parking"; // The database name you created

// 2. Connect to Database
$conn = new mysqli($host, $dbuser, $dbpass, $dbname);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database Connection Failed"]);
    exit();
}

// 3. Receive JSON Data from Frontend
$input = json_decode(file_get_contents("php://input"), true);
$user = $input['username'] ?? '';
$pass = $input['password'] ?? '';

// 4. Verify Credentials (Using Prepared Statements for Security)
$stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Verify the hashed password
    if (password_verify($pass, $row['password'])) {
        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['admin_logged_in'] = true;
        echo json_encode(["success" => true, "message" => "Login Successful!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid password."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "User not found."]);
}

$stmt->close();
$conn->close();
?>