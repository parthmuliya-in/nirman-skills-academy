<?php
session_start();
include "../include/config.php";

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

// Generate CSRF token
if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $csrf     = $_POST['csrf'] ?? '';

    // CSRF check
    if (!hash_equals($_SESSION['csrf'], $csrf)) {
        $message = "Invalid request.";
    } elseif (!$username || !$password) {
        $message = "All fields are required.";
    } else {
        // Fetch admin record
        $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($admin_id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                // Login successful
                session_regenerate_id(true); // prevent session fixation
                $_SESSION['admin_id'] = $admin_id;
                $_SESSION['admin_username'] = $username;
                header("Location: index.php");
                exit;
            } else {
                $message = "Invalid username or password.";
            }
        } else {
            $message = "Invalid username or password.";
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
<title>Admin Login</title>
<style>
body { font-family: Arial; background: #f4f4f4; display:flex; justify-content:center; align-items:center; height:100vh; }
.login-box { background:#fff; padding:30px; border-radius:5px; box-shadow:0 0 10px rgba(0,0,0,0.1); width:300px; }
input { width:100%; padding:10px; margin:10px 0; border:1px solid #ccc; border-radius:3px; }
button { width:100%; padding:10px; background:#4CAF50; color:#fff; border:none; border-radius:3px; cursor:pointer; }
.error { color:red; font-size:14px; }
</style>
</head>
<body>

<div class="login-box">
    <h2>Admin Login</h2>
    <?php if ($message): ?>
        <div class="error"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf']; ?>">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
