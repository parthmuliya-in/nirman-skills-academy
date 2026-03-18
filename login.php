<?php
include "include/config.php"; // database connection

// Login logic must be before any HTML output
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($email == "" || $password == "") {
        $message = "All fields are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format";
    } else {
        $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $name, $user_email, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                // Login success
                if (session_status() === PHP_SESSION_NONE) {
                    session_start(); // ensure session is active
                }

                $_SESSION['user_id'] = $id;
                $_SESSION['user_email'] = $user_email;
                $_SESSION['user_name'] = $name;

                // Optional: update last seen
                $update = $conn->prepare("UPDATE users SET is_online=1, last_seen=NOW() WHERE id=?");
                $update->bind_param("i", $id);
                $update->execute();
                $update->close();

                // Redirect to index.php
                header("Location: index.php");
                exit; // stop further execution
            } else {
                $message = "Incorrect password";
            }
        } else {
            $message = "Email not registered";
        }

        $stmt->close();
    }
}

// Now include header after login logic
include "header.php";
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <style>
        /* ================= THEME ================= */
        :root {
            --bg: #f4f6fb;
            --card-bg: rgba(255, 255, 255, 0.85);
            --text: #1a1a1a;
            --border: #d0d5dd;

            /* BRAND COLORS */
            --accent: #ff6600;
            /* blue */
            --accent-dark: #ff6600;
            --success: #16a34a;
            --error: #dc2626;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg: #0b0f1a;
                --card-bg: rgb(255 255 255 / 19%);
                --text: #e5e7eb;
                --border: #30363d;

                /* DARK MODE BRAND */
                --accent: #f97316;
                /* orange */
                --accent-dark: #ea580c;
                --success: #22c55e;
                --error: #ef4444;
            }
        }


        /* ================= PAGE WRAPPER ================= */
        .login-page {
            /* background: var(--bg); */
            min-height: calc(100vh - 80px);
            /* header safe */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: var(--text);
        }

        /* ================= CARD ================= */
        .login-container {
            width: 100%;
            max-width: 420px;
            background: var(--card-bg);
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        /* ================= TITLE ================= */
        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: var(--accent);
        }

        /* ================= MESSAGE ================= */
        .message {
            background: rgba(255, 0, 0, 0.1);
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 15px;
            font-size: 14px;
        }

        /* ================= FORM ================= */
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        /* ================= INPUT ================= */
        .input-group label {
            font-size: 14px;
            margin-bottom: 6px;
            display: block;
            color: var(--text);
        }

        .input-group input {
            width: 100%;
            height: 46px;
            padding: 0 14px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text);
            font-size: 14px;
        }

        .input-group input::placeholder {
            color: #999;
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--accent);
        }

        /* ================= BUTTON ================= */
        .login-btn {
            width: 100%;
            height: 46px;
            border-radius: 30px;
            border: none;
            background: linear-gradient(45deg, #ff6600, #ff3d00);
            color: #fff;
            font-size: 15px;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-btn:hover {
            transform: translateY(-1px);
            opacity: 0.95;
        }

        /* ================= FOOTER ================= */
        .login-container p {
            text-align: center;
            font-size: 15px;
            margin-top: 15px;
            color: var(--text);
        }

        .login-container a {
            color: var(--accent);
            text-decoration: none;
        }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 480px) {
            .login-container {
                padding: 22px;
            }
        }

        @media (max-width: 360px) {
            .login-container {
                padding: 18px;
            }
        }
    </style>
</head>

<body>

    <div class="login-page">

        <div class="login-container">

            <h2>Login</h2>

            <?php if ($message): ?>
                <div class="message"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <form method="POST" class="login-form">

                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>

                <button class="login-btn">Login</button>
            </form>

            <p>Don't have an account? <a href="signup.php">Sign Up</a></p>

        </div>

    </div>
    <?php
    include "footer.php";
    ?>

</body>

</html>