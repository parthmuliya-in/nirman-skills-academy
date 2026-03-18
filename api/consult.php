<?php
session_start();
include "../include/config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../signup.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_id = $_SESSION['user_id'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $service = trim($_POST['service']);

    // Basic validation
    if (empty($name) || empty($email) || empty($contact) || empty($service)) {
        die("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address.");
    }

    // Prepare and insert into database
    $stmt = $conn->prepare("INSERT INTO consultations (user_id, name, email, contact, service) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $name, $email, $contact, $service);

    if ($stmt->execute()) {
        echo "<script>alert('Consultation booked successfully!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Something went wrong. Please try again.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../../index.php");
    // header("Location: ../index.php");
    exit;
}
