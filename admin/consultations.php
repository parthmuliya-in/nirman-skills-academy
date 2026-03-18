<?php
// session_start();
include "header.php";
include "../include/config.php";

// ------------------ ADMIN LOGIN CHECK ------------------
/*if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}*/

// ------------------ DELETE CONSULTATION ------------------
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM consultations WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_consultations.php");
    exit;
}

// ------------------ UPDATE CONSULTATION ------------------
$msg = '';
if (isset($_POST['update'])) {
    $id = (int) $_POST['id'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $service = trim($_POST['service']);

    if (!$name || !$email || !$contact || !$service) {
        $msg = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Invalid email address";
    } else {
        $stmt = $conn->prepare("UPDATE consultations SET name=?, email=?, contact=?, service=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $email, $contact, $service, $id);
        if ($stmt->execute()) {
            $msg = "Consultation updated successfully";
        } else {
            $msg = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// ------------------ FETCH CONSULTATIONS ------------------
$result = $conn->query("SELECT * FROM consultations ORDER BY created_at DESC");
$edit_id = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Consultations</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <div class="table-perspective">
        <div class="modern-table-wrap">
            <div class="table-header">
                <h2>Manage Consultations</h2>
            </div>
            <?php if ($msg)
                echo "<div class='msg'>{$msg}</div>"; ?>

            <!-- <table> -->
            <table class="modern-table" id="dataTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Service</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td data-label="ID"><?= $row['id']; ?></td>
                            <td data-label="User ID"><?= $row['user_id']; ?></td>
                            <td data-label="Name"> 
                                <?php if ($edit_id === (int) $row['id']): ?>
                                    <form method="POST" action="">
                                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                        <input type="text" name="name" value="<?= htmlspecialchars($row['name']); ?>" required>
                                    <?php else: ?>
                                        <?= htmlspecialchars($row['name']); ?>
                                    <?php endif; ?>
                            </td>
                            <td data-label="Email">
                                <?php if ($edit_id === (int) $row['id']): ?>
                                    <input type="email" name="email" value="<?= htmlspecialchars($row['email']); ?>" required>
                                <?php else: ?>
                                    <?= htmlspecialchars($row['email']); ?>
                                <?php endif; ?>
                            </td>
                            <td data-label="Contact">
                                <?php if ($edit_id === (int) $row['id']): ?>
                                    <input type="text" name="contact" value="<?= htmlspecialchars($row['contact']); ?>"
                                        required>
                                <?php else: ?>
                                    <?= htmlspecialchars($row['contact']); ?>
                                <?php endif; ?>
                            </td>
                            <td data-label="Service">
                                <?php if ($edit_id === (int) $row['id']): ?>
                                    <input type="text" name="service" value="<?= htmlspecialchars($row['service']); ?>"
                                        required>
                                <?php else: ?>
                                    <?= htmlspecialchars($row['service']); ?>
                                <?php endif; ?>
                            </td>
                            <td data-label="Cretated_at"><?= $row['created_at']; ?></td>
                            <td data-label="Action">
                                <?php if ($edit_id === (int) $row['id']): ?>
                                    <button type="submit" name="update"><i class="fa-solid fa-floppy-disk"></i></button>
                                    <a href="consultations.php"><i class="fa-solid fa-xmark"></i></a>
                                    </form>
                                <?php else: ?>
                                    <a href="consultations.php?edit=<?= $row['id']; ?>" class="btn edit-btn"><i
                                            class="fa-solid fa-edit"></i></a>
                                    <a href="consultations.php?delete=<?= $row['id']; ?>"
                                        onclick="return confirm('Are you sure to delete this consultation?');"
                                        class="btn edit-btn"><i class="fa-solid fa-trash"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>


</body>

</html>