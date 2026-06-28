<?php
session_start();
include '../include/config.php';

$message = "";

// LOGIN LOGIC
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $enrollment_no = $_POST['enrollment_no'];
  $input_password = $_POST['password'];

  // FETCH USER
  $stmt = $conn->prepare("SELECT * FROM enrollments WHERE enrollment_no=?");
  $stmt->bind_param("s", $enrollment_no);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 1) {

    $row = $result->fetch_assoc();

    // VERIFY PASSWORD
    if (password_verify($input_password, $row['password'])) {

      // ✅ LOGIN SUCCESS
      $_SESSION['student_id'] = $row['id'];
      $_SESSION['student_name'] = $row['student_name'];

      header("Location: dashboard.php"); // create this page
      exit();

    } else {
      $message = "❌ Invalid Password";
    }

  } else {
    $message = "❌ Enrollment Number Not Found";
  }
}
?>
<!doctype html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Segoe UI;
    }

    /* ROW BACKGROUND */
    .row-bg {
      width: 80%;
      margin: auto;
      padding: 50px 30px;
      display: flex;
      justify-content: center;

      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);

      border-radius: 20px;
      border: 1px solid rgba(255, 255, 255, 0.15);

      box-shadow:
        0 8px 32px rgba(0, 0, 0, 0.25),
        inset 0 0 10px rgba(255, 255, 255, 0.05);

      margin-top: 35px;
    }

    /* Parent */
    .parent {
      width: 1100px;
      max-width: 100%;
      background: transparent;
      display: flex;
      justify-content: center;
    }

    /* WRAPPER */
    .wrapper {
      position: relative;
      width: 950px;
      height: 480px;
      border-radius: 20px;
      max-width: 100%;
      overflow: hidden;
    }

    /* LEFT SIDE ROTATING BORDER (Original) */
    .wrapper::before {
      content: "";
      position: absolute;
      width: 1200px;
      height: 1200px;
      background: conic-gradient(#ff66001a, #ff6600, #ff66001a, #ff6600);
      animation: rotate 6s linear infinite;
      filter: blur(40px);
      left: -600px;
      /* left side glow */
      top: -300px;
    }

    .wrapper::after {
      content: "";
      position: absolute;
      inset: 4px;
      /* background:  rgb(223, 243, 243); */
      background-color: white;
      border-radius: 16px;
      z-index: 0;
    }

    @keyframes rotate {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }

    /* CONTAINER */
    .container {
      position: relative;
      z-index: 1;
      width: 100%;
      height: 100%;
      display: flex;
      overflow: hidden;
      border-radius: 18px;
    }

    /* LOGIN */
    .login {
      width: 50%;
      padding: 70px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      clip-path: polygon(0 0, 100% 0, 85% 100%, 0% 100%);
    }

    .login h2 {
      margin-bottom: 25px;
      font-size: 28px;
    }

    /* WELCOME */
    .welcome {
      width: 50%;
      padding: 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      background: #ff66001a;
      align-items: center;
      clip-path: polygon(15% 0%, 100% 0%, 100% 100%, 0% 100%);
      position: relative;
      overflow: hidden;
      /* color: white; */
    }

    /* RIGHT SIDE SAME ROTATING BORDER */
    .welcome::before {
      content: "";
      position: absolute;
      width: 1200px;
      height: 1200px;
      /* background:conic-gradient(cyan,#ff6600,cyan,#ff6600); */
      animation: rotate 6s linear infinite;
      /* background: #ff660034; */
      filter: blur(40px);
      right: -600px;
      /* right side glow */
      top: -300px;
      z-index: -1;
    }

    .welcome::after {
      content: "";
      position: absolute;
      inset: 4px;
      /* background:#ff660034; */
      /* background-color: red; */
      clip-path: polygon(15% 0%, 100% 0%, 100% 100%, 0% 100%);
      z-index: -1;
    }

    .welcome h1 {
      font-size: 32px;
      margin-bottom: 15px;
      text-align: center;
    }

    .welcome-text {
      margin-top: 10px;
      font-size: 16px;
      opacity: 0.9;
      text-align: center;
      max-width: 280px;
      line-height: 1.6;
    }

    /* INPUT */
    .input-box {
      margin: 18px 0;
    }

    .input-box input {
      width: 100%;
      padding: 12px;
      border: none;
      border-bottom: 3px solid black;
      background: transparent;
      outline: none;
      transition: 0.3s;
      font-size: 20px;
    }

    .input-box input:focus {
      border-bottom: 3px solid #ff6600;
    }

    /* BUTTON */
    #loggin-button {
      padding: 12px 35px;
      border: none;
      border-radius: 8px;
      background: #ff6600;
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
      width: 200px;
      margin: auto;
    }

    #loggin-button:hover {
      transform: scale(1.08);
    }

    /* RESPONSIVE */
    @media (max-width: 900px) {
      .wrapper {
        height: auto;
      }

      .container {
        flex-direction: column;
      }

      .login,
      .welcome {
        width: 100%;
        clip-path: none;
      }

      .welcome h1 {
        font-size: 22px;
      }

      .login {
        padding: 40px 25px;
      }

      .welcome {
        padding: 40px 20px;
      }

      #loggin-button {
        width: 100%;
      }
    }
  </style>
</head>

<body>
  <div class="row-bg">
    <div class="parent">
      <div class="wrapper">
        <div class="container">
          <div class="login">
            <h2>Login</h2>
            <?php if ($message != ""): ?>
              <p style="color:red;">
                <?php echo $message; ?>
              </p>
            <?php endif; ?>
            <form method="POST">
              <div class="input-box">
                <input type="text" name="enrollment_no" placeholder="Enrollment no" required />
              </div>

              <div class="input-box">
                <input type="password" name="password" placeholder="Password" required />
              </div>

              <button id="loggin-button" type="submit">Login</button>
            </form>
              
          </div>

          <div class="welcome">
            <h1>WELCOME BACK!</h1>
            <p class="welcome-text">
              Enter your credentials to access your account and continue your
              journey with us.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>