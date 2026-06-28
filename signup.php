<?php
// session_start();
include "include/config.php";
include "header.php";
include "api/wp_app.php";

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $captcha = trim($_POST['captcha']);

    // Validation
    if ($name == "" || $email == "" || $password == "" || $captcha == "") {
        $message = "All fields are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters";
    } elseif (!isset($_SESSION['captcha_code']) || $captcha !== $_SESSION['captcha_code']) {
        $message = "Incorrect CAPTCHA";
    } else {

        // Check email exists
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $message = "Email already registered";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $insert = mysqli_query(
                $conn,
                "INSERT INTO users (name, email, password, created_at)
                 VALUES ('$name','$email','$hashed', NOW())"
            );

            if ($insert) {
                $message = "Registration successful";
                unset($_SESSION['captcha_code']); // reset captcha
            } else {
                $message = "Registration failed";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NirmanSkills Academy</title>
    <link rel="icon" href="assets/images/logo.png" type="image/png">
    <link rel="stylesheet" href="assets/css/signup.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <!-- <link rel="stylesheet" href="assets/css/d.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .captcha {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }

        .captcha img {
            margin-right: 10px;
        }

        .refresh {
            cursor: pointer;
            color: #fff;
            text-decoration: underline;
        }

        .message {
            color: red;
            margin: 10px 0;
        }

        .success {
            color: green;
        }
    </style>
</head>

<body>
    <!-- **************************** HEADER ************************************* -->
    <!---Header will include on top--->
    <!-- **************************** HEADER ENDS ************************************* -->

    <main>
        <div class="signup-container">
            <div class="left-side">
                <div class="left-logo">
                    <img src="assets/images/logo.png" alt="">
                </div>
                <h1>Design. Code. Rise.</h1>
                <h4>Welcome to the Nirman Skills Academy</h4>
                <p>Where creativity meets real-world skills — we help you master design, coding, and digital tools with
                    expert-led, practical training.</p>
                <ul class="feature-list">
                    <li><i class="fa-solid fa-check"></i>Learn directly from skilled industry professionals.</li>
                    <li><i class="fa-solid fa-check"></i> Study anytime, anywhere with complete flexibility.</li>
                    <li><i class="fa-solid fa-check"></i>Join an active, supportive learner community.</li>
                    <li><i class="fa-solid fa-check"></i> Start from basics and grow to advanced levels smoothly.</li>
                    <li><i class="fa-solid fa-check"></i> Kick-start your digital career with confidence.</li>
                </ul>

            </div>

            <div class="right-side">
                <div class="form-card-glass">
                    <img src="assets/images/logo.png" class="form-logo">
                    <h2>Sign Up</h2>
                    <?php if ($message): ?>
                        <p class="message <?= strpos($message, 'successful') !== false ? 'success' : '' ?>">
                            <?= htmlspecialchars($message) ?>
                        </p>
                    <?php endif; ?>
                    <form method="POST" action="">
                        <div>
                            <label class="signup-label">Name</label>
                            <input type="text" name="name" value="<?= htmlspecialchars($name ?? '') ?>" required
                                placeholder="Enter your name">
                        </div>

                        <div>
                            <label class="signup-label">Email</label>
                            <input type="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required
                                placeholder="Enter your email">
                        </div>

                        <div class="password-field">
                            <label class="signup-label">Password</label>
                            <input type="password" id="signup-password" name="password" required>
                            <i class="fa-solid fa-eye eye-icon" data-toggle="password"
                                data-target="signup-password"></i>
                        </div>
                        <div class="captcha">
                            <img src="captcha.php" alt="CAPTCHA" id="captcha_img">
                            <span class="refresh"
                                onclick="document.getElementById('captcha_img').src='captcha.php?'+Date.now();"><i
                                    class="fa-solid fa-arrows-rotate" style="color: #FFD43B;"></i></span>

                        </div>
                        <label>Enter CAPTCHA code:</label>
                        <input type="text" name="captcha" required autocomplete="off" placeholder="Enter Captcha">
                        <button type="submit" class="signup-btn">Sign Up</button>
                        <span>You have account? <a href="login.php" style="color: #fa8d08ff;">Login Now</a></span>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <?php
    include "footer.php";
    ?>
    <Script src="assets/js/script.js"></Script>
    <Script src="assets/js/signup.js"></Script>
</body>

</html>