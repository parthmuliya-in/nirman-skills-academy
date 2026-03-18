<?php
// register.php - User Registration with Simple CAPTCHA

session_start();

// Database connection (update with your details)
$host = 'localhost';
$dbname = 'nirman';  // Change to your database name
$username = 'root';         // Change if needed
$password = '';             // Change if needed

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';
    $captcha = $_POST['captcha'] ?? '';

    // Basic validation
    if (empty($name) || empty($email) || empty($pass) || empty($captcha)) {
        $message = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Invalid email format.';
    } elseif (strlen($pass) < 6) {
        $message = 'Password must be at least 6 characters.';
    } elseif ($captcha !== ($_SESSION['captcha_code'] ?? '')) {
        $message = 'Incorrect CAPTCHA code.';
        // Regenerate CAPTCHA on failure
        generateCaptcha();
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $message = 'Email already registered.';
        } else {
            // Hash password and insert user
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$name, $email, $hashed_pass])) {
                $message = 'Registration successful!';
                // Regenerate CAPTCHA after success
                generateCaptcha();
                // Clear form fields if needed
                $name = $email = $pass = '';
            } else {
                $message = 'Registration failed. Try again.';
            }
        }
    }
}

// Function to generate CAPTCHA
function generateCaptcha() {
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $code = substr(str_shuffle($chars), 0, 6);
    $_SESSION['captcha_code'] = $code;
}
generateCaptcha();  // Always generate on page load
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 50px auto; }
        form { border: 1px solid #ccc; padding: 20px; border-radius: 8px; }
        input[type=text], input[type=email], input[type=password] {
            width: 100%; padding: 10px; margin: 8px 0; box-sizing: border-box;
        }
        input[type=submit] { padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; }
        .captcha { display: flex; align-items: center; margin: 10px 0; }
        .captcha img { margin-right: 10px; }
        .refresh { cursor: pointer; color: blue; text-decoration: underline; }
        .message { color: red; margin: 10px 0; }
        .success { color: green; }
    </style>
</head>
<body>

<h2>User Registration</h2>

<?php if ($message): ?>
    <p class="message <?= strpos($message, 'successful') !== false ? 'success' : '' ?>"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label>Name:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($name ?? '') ?>" required>

    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>

    <label>Password:</label>
    <input type="password" name="password" required>

    <div class="captcha">
        <img src="captcha.php" alt="CAPTCHA" id="captcha_img">
        <span class="refresh" onclick="document.getElementById('captcha_img').src='captcha.php?'+Date.now();">Refresh</span>
    </div>
    <label>Enter CAPTCHA code:</label>
    <input type="text" name="captcha" required autocomplete="off">

    <input type="submit" value="Register">
</form>

</body>
</html>