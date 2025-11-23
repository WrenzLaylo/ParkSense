<?php
header('Content-Type: application/json');

// --- CONFIGURATION ---
$ADMIN_SECRET_KEY = "LocaPark2025"; // <--- CHANGE THIS TO YOUR PREFERRED CODE
$host = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "smart_parking";

// 1. Database Connection
$conn = new mysqli($host, $dbuser, $dbpass, $dbname);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB Connection Failed"]);
    exit();
}

// 2. Get Data
$input = json_decode(file_get_contents("php://input"), true);
$user = $input['username'] ?? '';
$pass = $input['password'] ?? '';
$key = $input['secret_key'] ?? ''; // <--- Get the key from frontend

// 3. Validate Secret Key FIRST
if ($key !== $ADMIN_SECRET_KEY) {
    echo json_encode(["success" => false, "message" => "Invalid Admin Secret Key."]);
    exit();
}

// 4. Validate User Inputs
if (empty($user) || empty($pass)) {
    echo json_encode(["success" => false, "message" => "Please fill in all fields."]);
    exit();
}

// 5. Check if Username Exists
$checkStmt = $conn->prepare("SELECT id FROM admins WHERE username = ?");
$checkStmt->bind_param("s", $user);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Username already taken."]);
    exit();
}
$checkStmt->close();

// 6. Hash & Insert
$hashed_password = password_hash($pass, PASSWORD_DEFAULT);

$insertStmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
$insertStmt->bind_param("ss", $user, $hashed_password);

if ($insertStmt->execute()) {
    echo json_encode(["success" => true, "message" => "Registration successful! Please login."]);
} else {
    echo json_encode(["success" => false, "message" => "Error registering user."]);
}

$insertStmt->close();
$conn->close();
?>