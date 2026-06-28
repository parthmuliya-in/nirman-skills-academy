<?php
include "../include/config.php";
// session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/phpmailer/phpmailer/src/Exception.php';
require __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require __DIR__ . '/../vendor/phpmailer/phpmailer/src/SMTP.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
/* =========================
SHOW ALERT AFTER REDIRECT
========================= */
if (isset($_SESSION['mail_status'])) {
    if ($_SESSION['mail_status'] == "success") {
        echo "<script>alert('Credentials sent successfully!');</script>";
    } elseif ($_SESSION['mail_status'] == "fail") {
        echo "<script>alert('Email sending failed!');</script>";
    } elseif ($_SESSION['mail_status'] == "already") {
        echo "<script>alert('Credentials already sent!');</script>";
    }
    unset($_SESSION['mail_status']);
}

/* =========================
   SEND CREDENTIALS
========================= */
if (isset($_GET['send'])) {

    // echo "MAIL CODE STARTED<br>";

    $id = (int) $_GET['send'];

    // FETCH STUDENT
    $stmt = $conn->prepare("SELECT student_name, student_email FROM enrollments WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultUser = $stmt->get_result();

    if ($resultUser->num_rows == 0) {
        die("Student not found");
    }

    $user = $resultUser->fetch_assoc();

    // ✅ DEFINE VARIABLES
    $name = $user['student_name'];
    $email = $user['student_email'];

    // ✅ GENERATE VALUES
    $enrollment_no = "ENR" . date("Y") . str_pad($id, 5, "0", STR_PAD_LEFT);
    $plain_password = bin2hex(random_bytes(4));

    $hashed_password = password_hash($plain_password, PASSWORD_BCRYPT);

    $update = $conn->prepare("UPDATE enrollments SET enrollment_no=?, password=?, credentials_sent=1 WHERE id=?");
    $update->bind_param("ssi", $enrollment_no, $hashed_password, $id);

    if (!$update->execute()) {
        die("Update Failed: " . $update->error);
    }


    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = 'parthmuliya02@gmail.com';
        $mail->Password = 'sbdszsstzgzyaqpn';

        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // $mail->setFrom('parthmuliya02@gmail.com', 'Test');
        // $mail->addAddress('parthmuliya02@gmail.com'); // test self
        $mail->setFrom('parthmuliya02@gmail.com', 'Nirman Skills');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Your Course Login Details';

        $mail->Body = "
        <h3>Hello {$name}</h3>
        <p>Your enrollment is confirmed.</p>
        <p><b>Enrollment No:</b> {$enrollment_no}</p>
        <p><b>Password:</b> {$plain_password}</p>
        <p>Please login and change your password.</p>
    ";

        if ($mail->send()) {
            echo "✅ MAIL SENT SUCCESS";
        } else {
            echo "❌ MAIL FAILED: " . $mail->ErrorInfo;
        }

    } catch (Exception $e) {
        echo "❌ EXCEPTION: " . $mail->ErrorInfo;
    }

    exit();
}
// Fetch enrollments
$sql = "SELECT e.*, c.title AS course_name 
            FROM enrollments e 
            LEFT JOIN courses c ON e.course_id = c.id 
            ORDER BY e.enrolled_at DESC";

$result = $conn->query($sql);
include "header.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Enrollments </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css"> <!-- External CSS -->
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }


        /* CONTAINER */
        .container {
            width: 98%;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        /* TITLE */
        h1 {
            color: #ff6600;
            margin-bottom: 20px;
            font-size: 26px;
        }

        /* ALERT */
        .alert {
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
        }

        .alert.success {
            background: #d4edda;
            color: #155724;
        }

        .alert.error {
            background: #f8d7da;
            color: #721c24;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        /* HEADER */
        th {
            background: linear-gradient(135deg, #ff7a00, #ff4d00);
            color: white;
            padding: 12px;
            font-size: 14px;
            text-align: left;
        }

        /* CELLS */
        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        /* ROW HOVER */
        tr:hover {
            background: #f0f8ff;
        }

        /* ACTION ICONS */
        .actions a {
            margin: 0 6px;
            font-size: 18px;
            text-decoration: none;
        }

        .delete-icon {
            color: #dc3545;
        }

        .actions a:hover {
            opacity: 0.7;
        }

        /* NO DATA */
        .no-data {
            text-align: center;
            padding: 40px;
            color: #777;
            font-size: 18px;
        }

        /* ======================
   MOBILE RESPONSIVE TABLE
====================== */
        @media (max-width: 768px) {

            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
                width: 100%;
            }

            thead {
                display: none;
            }

            tr {
                background: #fff;
                margin-bottom: 15px;
                border-radius: 10px;
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
                padding: 10px;
            }

            td {
                border: none;
                padding: 8px 10px;
                position: relative;
                font-size: 13px;
            }

            td::before {
                content: attr(data-label);
                font-weight: bold;
                display: block;
                color: #ff6600;
                margin-bottom: 3px;
            }
        }

        /* MODAL */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            position: relative;
        }

        .close-modal {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 26px;
            cursor: pointer;
            color: #999;
        }

        .close-modal:hover {
            color: #000;
        }

        /* FORM */
        .modal input,
        .modal textarea {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        .modal input:focus,
        .modal textarea:focus {
            outline: none;
            border-color: #ff6600;
        }

        .modal button {
            width: 100%;
            padding: 12px;
            background: #ff6600;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        .modal button:hover {
            background: #e65c00;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Manage Enrollments</h1>
        <?php echo $message; ?>

        <?php if ($result->num_rows == 0): ?>
            <div class="no-data">
                <i class="fas fa-inbox fa-3x" style="color:#ccc;"></i><br><br>
                No enrollments found.
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Course</th>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Message</th>
                        <th>Enrolled At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td data-label="User ID"><?php echo $row['user_id']; ?></td>
                            <td data-label="Course"><?php echo htmlspecialchars($row['course_name'] ?: $row['course_title']); ?></td>
                            <td data-label="Student Name"><?php echo htmlspecialchars($row['student_name']); ?></td>
                            <td data-label="Email"><?php echo htmlspecialchars($row['student_email']); ?></td>
                            <td data-label="Phone"><?php echo htmlspecialchars($row['student_phone']); ?></td>
                            <td data-label="Message"><?php echo htmlspecialchars(substr($row['message'], 0, 50)) . (strlen($row['message']) > 50 ? '...' : ''); ?>
                            </td>
                            <td data-label="Enrolled"><?php echo date("d M Y, h:i A", strtotime($row['enrolled_at'])); ?></td>
                            <td class="actions" data-label="Actions">
                                <a href="enrollments.php?delete=<?php echo $row['id']; ?>" class="delete-icon" title="Delete"
                                    onclick="return confirm('Are you sure you want to delete this enrollment?');">
                                    <i class="fas fa-trash-alt"></i>
                                </a>|<?php
                                if ($row['credentials_sent'] == 1) {
                                    echo " Sent";
                                } else {
                                    echo '<a href="enrollments.php?send=' . $row['id'] . '"><i class="fas fa-paper-plane"></i></a>';
                                } ?>

                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <h3>Edit Enrollment</h3>
            <form method="post">
                <input type="hidden" name="enroll_id" id="edit_id">
                <label>Student Name</label>
                <input type="text" name="student_name" id="edit_name" required>

                <label>Email</label>
                <input type="email" name="student_email" id="edit_email" required>

                <label>Phone</label>
                <input type="text" name="student_phone" id="edit_phone">

                <label>Message</label>
                <textarea name="message" id="edit_message" rows="5"></textarea>

                <br><br>
                <button type="submit" name="update_enrollment">Update Enrollment</button>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(data) {
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_name').value = data.name;
            document.getElementById('edit_email').value = data.email;
            document.getElementById('edit_phone').value = data.phone || '';
            document.getElementById('edit_message').value = data.message || '';
            document.getElementById('editModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Close when clicking outside
        window.onclick = function (event) {
            const modal = document.getElementById('editModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</body>

</html>

<?php $conn->close(); ?>