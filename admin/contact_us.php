<?php
// session_start();
include "header.php";
require_once "../include/config.php";

/* ================= ADMIN AUTH ================= */
/*if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}*/

/* ================= DELETE ================= */
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM contact_us WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: contact_us.php");
    exit;
}

/* ================= UPDATE ================= */
$msg = '';
if (isset($_POST['update'])) {
    $id = (int) $_POST['id'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (!$name || !$email || !$phone || !$subject || !$message) {
        $msg = "All fields are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Invalid email";
    } else {
        $stmt = $conn->prepare("
            UPDATE contact_us 
            SET name=?, email=?, phone=?, subject=?, message=? 
            WHERE id=?
        ");
        $stmt->bind_param("sssssi", $name, $email, $phone, $subject, $message, $id);

        if ($stmt->execute()) {
            $msg = "Contact updated successfully";
        } else {
            $msg = "Update failed";
        }
        $stmt->close();
    }
}

/* ================= FETCH ================= */
$result = $conn->query("SELECT * FROM contact_us ORDER BY created_at DESC");
$edit_id = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Contact Messages</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <div class="table-perspective">
        <div class="modern-table-wrap">
            <div class="table-header">
                <h2>Manage Contact Messages</h2>
            </div>
            <?php if ($msg)
                echo "<div class='msg'>$msg</div>"; ?>

            <table class="modern-table" id="dataTable">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td data-label="ID"><?= $row['id'] ?></td>
                            <td data-label="Usre ID"><?= $row['user_id'] ?></td>

                            <?php if ($edit_id === (int) $row['id']): ?>
                                <form method="POST">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">

                                    <td data-label="Name"><input name="name" value="<?= htmlspecialchars($row['name']) ?>"></td>
                                    <td data-label="Email"><input name="email" value="<?= htmlspecialchars($row['email']) ?>"></td>
                                    <td data-label="Phone"><input name="phone" value="<?= htmlspecialchars($row['phone']) ?>"></td>
                                    <td data-label="Subject"><input name="subject" value="<?= htmlspecialchars($row['subject']) ?>"></td>
                                    <td data-label="Message"><textarea name="message"><?= htmlspecialchars($row['message']) ?></textarea></td>
                                    <td data-label="Created_at"><?= $row['created_at'] ?></td>

                                    <td data-label="Action" class="action-btn">
                                        <button name="update"><i class="fa-solid fa-floppy-disk"></i></button>
                                        <a href="contact_us.php"><i class="fa-solid fa-xmark"></i></a>
                                    </td>
                                </form>

                            <?php else: ?>
                                <td data-label="Name"><?= htmlspecialchars($row['name']) ?></td>
                                <td data-label="Email"><?= htmlspecialchars($row['email']) ?></td>
                                <td data-label="Phone"><?= htmlspecialchars($row['phone']) ?></td>
                                <td data-label="Subject"><?= htmlspecialchars($row['subject']) ?></td>
                                <td data-label="Message"><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                                <td data-label="Created_at"><?= $row['created_at'] ?></td>

                                <td class="action-btn">
                                    <a href="?edit=<?= $row['id'] ?>" class="btn edit-btn"><i class="fa-solid fa-edit"></i></a>
                                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this message?')" class="btn edit-btn">
                                        <i class="fa-solid fa-trash"></i></a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>