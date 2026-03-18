<?php
header("Content-Type: application/json");
session_start();
require_once "../include/config.php"; // mysqli connection

// Only allow logged-in users
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "You must be logged in to subscribe."]);
    exit;
}

// Read JSON Data
$data = json_decode(file_get_contents("php://input"), true);
$email = trim($data['email'] ?? '');

// Validate email
if (empty($email)) {
    echo json_encode(["status" => "error", "message" => "Email is required."]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "message" => "Invalid email format."]);
    exit;
}

// Use logged-in user's email to prevent someone subscribing with another email
$user_id = $_SESSION['user_id'];
$result = mysqli_query($conn, "SELECT email FROM users WHERE id='$user_id' LIMIT 1");
if (!$result || mysqli_num_rows($result) == 0) {
    echo json_encode(["status" => "error", "message" => "User not found."]);
    exit;
}

$row = mysqli_fetch_assoc($result);
$email = $row['email']; // override email input with user's email

// Check duplicate
$stmt = $conn->prepare("SELECT id FROM subscribers WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "You are already subscribed."]);
    $stmt->close();
    exit;
}
$stmt->close();

// Insert subscriber
$stmt = $conn->prepare("INSERT INTO subscribers (email, created_at) VALUES (?, NOW())");
$stmt->bind_param("s", $email);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Subscription successful!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Database error. Try again."]);
}

$stmt->close();
$conn->close();
?>
