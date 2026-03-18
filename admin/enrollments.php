<?php
include "header.php";
include "../include/config.php";
// session_start();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete
if (isset($_GET['delete'])) {
    $delete_id = (int) $_GET['delete'];
    $conn->query("DELETE FROM enrollments WHERE id = $delete_id");
    header("Location: enrollments.php");
    exit();
}

// Update
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_enrollment'])) {
    $id = (int) $_POST['enroll_id'];
    $name = $conn->real_escape_string($_POST['student_name']);
    $email = $conn->real_escape_string($_POST['student_email']);
    $phone = $conn->real_escape_string($_POST['student_phone']);
    $msg = $conn->real_escape_string($_POST['message']);

    $sql = "UPDATE enrollments SET 
            student_name = '$name',
            student_email = '$email',
            student_phone = '$phone',
            message = '$msg'
            WHERE id = $id";

    if ($conn->query($sql)) {
        $message = "<div class='alert success'>Enrollment updated successfully!</div>";
    } else {
        $message = "<div class='alert error'>Error: " . $conn->error . "</div>";
    }
}

// Fetch enrollments
$sql = "SELECT e.*, c.title AS course_name 
        FROM enrollments e 
        LEFT JOIN courses c ON e.course_id = c.id 
        ORDER BY e.enrolled_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Enrollments </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .container {
            max-width: 1400px;
            margin-top: 50px;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            /* text-align: center; */
            color: #ff6600;
            margin-bottom: 30px;
        }

        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
        }

        .alert.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: linear-gradient(135deg, #ff7a00, #ff4d00);
            color: white;
        }

        tr:hover {
            background: #f8fbff;
        }

        .actions a {
            margin: 0 8px;
            font-size: 18px;
            cursor: pointer;
        }

        .edit-icon {
            color: #28a745;
        }

        .delete-icon {
            color: #dc3545;
        }

        .no-data {
            text-align: center;
            padding: 50px;
            color: #666;
            font-size: 18px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            position: relative;
        }

        .close-modal {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 28px;
            cursor: pointer;
            color: #aaa;
        }

        .close-modal:hover {
            color: #000;
        }

        .modal input,
        .modal textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .modal button {
            padding: 12px 25px;
            background: #007cba;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
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
                        <th>ID</th>
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
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['user_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['course_name'] ?: $row['course_title']); ?></td>
                            <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['student_email']); ?></td>
                            <td><?php echo htmlspecialchars($row['student_phone']); ?></td>
                            <td><?php echo htmlspecialchars(substr($row['message'], 0, 50)) . (strlen($row['message']) > 50 ? '...' : ''); ?>
                            </td>
                            <td><?php echo date("d M Y, h:i A", strtotime($row['enrolled_at'])); ?></td>
                            <td class="actions">
                                <a href="enrollments.php?delete=<?php echo $row['id']; ?>" class="delete-icon" title="Delete"
                                    onclick="return confirm('Are you sure you want to delete this enrollment?');">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
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