<?php
session_start();
include "../include/config.php";

// Generate CSRF token
if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm  = trim($_POST['confirm_password'] ?? '');
    $csrf     = $_POST['csrf'] ?? '';

    // CSRF check
    if (!hash_equals($_SESSION['csrf'], $csrf)) {
        $message = "Invalid request.";
    } elseif (!$username || !$password || !$confirm) {
        $message = "All fields are required.";
    } elseif ($password !== $confirm) {
        $message = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters.";
    } else {
        // Check if username exists
        $stmt = $conn->prepare("SELECT id FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Username already exists.";
        } else {
            // Insert new admin
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
            $insert->bind_param("ss", $username, $hashed_password);

            if ($insert->execute()) {
                $message = "Admin registered successfully. <a href='admin_login.php'>Login here</a>";
            } else {
                $message = "Database error: " . $conn->error;
            }
            $insert->close();
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Register</title>
<style>
body { font-family: Arial; background: #f4f4f4; display:flex; justify-content:center; align-items:center; height:100vh; }
.register-box { background:#fff; padding:30px; border-radius:5px; box-shadow:0 0 10px rgba(0,0,0,0.1); width:300px; }
input { width:100%; padding:10px; margin:10px 0; border:1px solid #ccc; border-radius:3px; }
button { width:100%; padding:10px; background:#007BFF; color:#fff; border:none; border-radius:3px; cursor:pointer; }
.error { color:red; font-size:14px; }
.success { color:green; font-size:14px; }
</style>
</head>
<body>

<div class="register-box">
    <h2>Admin Registration</h2>
    <?php if ($message): ?>
        <div class="<?= strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>">
            <?= $message; ?>
        </div>
    <?php endif; ?>
    <form method="POST" action="">
        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf']; ?>">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit">Register</button>
    </form>
</div>

</body>
</html>
