<?php
session_start();
include '../include/config.php';

// 🔒 Check login
if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch student data
$stmt = $conn->prepare("SELECT * FROM enrollments WHERE id=?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Update profile
if(isset($_POST['update'])){

    $name  = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $update = $conn->prepare("UPDATE enrollments 
                             SET student_name=?, student_email=?, student_phone=? 
                             WHERE id=?");

    $update->bind_param("sssi", $name, $email, $phone, $student_id);
    $update->execute();

    echo "<script>alert('Profile Updated Successfully'); window.location='';</script>";
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Nirman Skill</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Segoe UI", sans-serif;
    }

    body {
      padding: 20px;
    }

    /* ================= PROFILE ================= */

    .profile-banner {
      height: 320px;
      border-radius: 25px;
      background-image: url("https://images.unsplash.com/photo-1557804506-669a67965ba0?auto=format&fit=crop&w=1350&q=80");
      background-size: cover;
      background-position: center;
      max-width: 1000px;
      margin: auto;
      /* left right space */
      margin-top: 17px;

    }

    .profile-card {
      position: relative;
      background: rgba(255, 255, 255, 0.97);
      backdrop-filter: blur(25px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 25px;
      padding: 30px;
      max-width: 700px;
      margin: auto;
      margin-top: -100px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    }

    .profile-top {
      display: flex;
      align-items: center;
      gap: 20px;
      margin-bottom: 30px;
      flex-wrap: wrap;
    }

    .profile-img img {
      width: 110px;
      height: 110px;
      border-radius: 50%;
      border: 4px solid #fff;
    }

    .profile-info {
      color: black;
    }

    .profile-info h3 {
      font-size: 24px;
    }

    .edit-btn {
      position: absolute;
      top: 20px;
      right: 25px;
      padding: 8px 16px;
      border: none;
      border-radius: 8px;
      background: #ff6600;
      color: #fff;
      cursor: pointer;
      font-weight: 600;
    }

    .profile-details {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
    }

    .detail-box label {
      font-size: 14px;
      font-weight: 900;
    }

    .detail-box input {
      width: 100%;
      margin-top: 6px;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid rgba(0, 0, 0, 0.2);
    }

    .detail-box input:disabled {
      background: transparent;
      border: none;
      font-weight: 500;
    }
  </style>
  <script>
    function toggleEdit() {
      let inputs = document.querySelectorAll(".edit-field");
      let btn = document.getElementById("editBtn");

      if (btn.innerText === "Edit") {
        inputs.forEach(i => i.removeAttribute("disabled"));
        btn.innerText = "Save";
      } else {
        document.getElementById("profileForm").submit();
      }
    }
  </script>
</head>

<body>


  <div class="profile-banner"></div>

  <div class="profile-card">
    <form method="POST" id="profileForm">
      <!-- <button class="edit-btn" onclick="toggleEdit()" id="editBtn">Edit</button> -->

      <div class="profile-top">
        <div class="profile-info">
          <h3 id="nameText"><?php echo htmlspecialchars($data['student_name']); ?></h3>
        </div>
      </div>

      <div class="profile-details">
        <div class="detail-box">
          <label>Full Name</label>
          <input type="text" name="name" class="edit-field" value="<?php echo htmlspecialchars($data['student_name']); ?>" disabled>
        </div>

        <div class="detail-box">
          <label>Email</label>
          <input type="text" name="email" class="edit-field" value="<?php echo htmlspecialchars($data['student_email']); ?>" disabled>
        </div>

        <div class="detail-box">
          <label>Phone</label>
          <input type="text" name="phone" class="edit-field" value="<?php echo htmlspecialchars($data['student_phone']); ?>" disabled>
        </div>
      </div>
      <!-- <input type="hidden" name="update" value="1"> -->
    </form>
  </div>
  </div>
  </div>



</body>

</html>




