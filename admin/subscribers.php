<?php
// session_start();
include "header.php";
include "../include/config.php";

// Check admin login
/*if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}*/

// -------------------- DELETE Subscriber --------------------
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM subscribers WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_subscribers.php");
    exit;
}

// -------------------- UPDATE Subscriber --------------------
$msg = '';
if (isset($_POST['update'])) {
    $id = (int) $_POST['id'];
    $email = trim($_POST['email']);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare("UPDATE subscribers SET email = ? WHERE id = ?");
        $stmt->bind_param("si", $email, $id);
        if ($stmt->execute()) {
            $msg = "Subscriber updated successfully";
        } else {
            $msg = "Error updating subscriber: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $msg = "Invalid email address";
    }
}

// -------------------- FETCH ALL SUBSCRIBERS --------------------
$result = $conn->query("SELECT * FROM subscribers ORDER BY created_at DESC");

$edit_id = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Subscribers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <div class="table-perspective">
        <div class="modern-table-wrap">
            <div class="table-header">
                <h2>Manage Subscribers</h2>
            </div>
            <?php if ($msg)
                echo "<div class='msg'>{$msg}</div>"; ?>

            <table class="modern-table" id="dataTable">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td data-label="ID"><?= $row['id']; ?></td>
                            <td data-label="Email">
                                <?php if ($edit_id === (int) $row['id']): ?>
                                    <form method="POST" action="">
                                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                        <input type="email" name="email" value="<?= htmlspecialchars($row['email']); ?>"
                                            required>
                                        <button type="submit" name="update"><i class="fa-solid fa-floppy-disk"></i></button>
                                        <a href="subscribers.php"><i class="fa-solid fa-xmark"></i></a>
                                    </form>
                                <?php else: ?>
                                    <?= htmlspecialchars($row['email']); ?>
                                <?php endif; ?>
                            </td>
                            <td data-label="Create_at"><?= $row['created_at']; ?></td>
                            <td>
                                <a href="subscribers.php?edit=<?= $row['id']; ?>" class="btn edit-btn"><i class="fa-solid fa-edit"></i></a>
                                <a href="subscribers.php?delete=<?= $row['id']; ?>"
                                    onclick="return confirm('Are you sure to delete this subscriber?');" class="btn edit-btn"><i
                                        class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>


</body>

</html>