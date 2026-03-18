<?php
header("Content-Type: application/json");
session_start();
require_once "../include/config.php"; // mysqli $conn

// ------------------- LOGIN CHECK -------------------
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Login Now"]);  
    exit;
}

// ------------------- READ JSON DATA -------------------
$data = json_decode(file_get_contents("php://input"), true);

// ------------------- CSRF CHECK -------------------
if (!hash_equals($_SESSION['csrf'] ?? '', $data['csrf'] ?? '')) {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit;
}

// ------------------- SANITIZE INPUT -------------------
$user_id = (int) $_SESSION['user_id'];
$name    = trim($data['name'] ?? '');
$email   = trim($data['email'] ?? '');
$phone   = trim($data['phone'] ?? '');
$subject = trim($data['subject'] ?? '');
$message = trim($data['message'] ?? '');

// ------------------- VALIDATION -------------------
if (!$name || !$email || !$phone || !$subject || !$message) {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "message" => "Invalid email"]);
    exit;
}

if (!preg_match('/^[0-9]{10}$/', $phone)) {
    echo json_encode(["status" => "error", "message" => "Invalid phone number"]);
    exit;
}

// ------------------- INSERT INTO DATABASE -------------------
$stmt = $conn->prepare("
    INSERT INTO contact_us 
    (user_id, name, email, phone, subject, message) 
    VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "isssss",
    $user_id,
    $name,
    $email,
    $phone,
    $subject,
    $message
);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Your message has been submitted successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
}

$stmt->close();
$conn->close();
