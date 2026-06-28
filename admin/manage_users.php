<?php
// session_start();
// include "header.php";
include "../include/config.php";

// Admin login check (uncomment when ready)
/*
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
*/

// Handle DELETE request
if (isset($_GET['delete'])) {
    $delete_id = (int) $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_users.php");
    exit;
}

// Handle EDIT request
if (isset($_POST['edit_user'])) {
    $edit_id = (int) $_POST['user_id'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    // ✅ Check duplicate email
    $check = $conn->prepare("SELECT id FROM users WHERE email=? AND id != ?");
    $check->bind_param("si", $email, $edit_id);
    $check->execute();
    $resultCheck = $check->get_result();

    if ($resultCheck->num_rows > 0) {
        echo "<script>alert('Email already exists!'); window.location='manage_users.php';</script>";
        exit;
    }

    // ✅ Correct update
    $stmt = $conn->prepare("UPDATE users SET name=?, email=?, updated_at=NOW() WHERE id=?");
    $stmt->bind_param("ssi", $name, $email, $edit_id);

    $stmt->execute();
    $stmt->close();

    header("Location: manage_users.php");
    exit;
}
// if (isset($_POST['edit_user'])) {
//     $edit_id = (int) $_POST['user_id'];
//     $name = trim($_POST['name']);
//     $email = trim($_POST['email']); 

//     // $password = trim($_POST['password']);
//     if (!empty($email)) {
//         // $hashed_password = password_hash($password, PASSWORD_DEFAULT);
//         $stmt = $conn->prepare("UPDATE users SET name=?, email=?, updated_at=NOW() WHERE id=?");
//         $stmt->bind_param("ssi", $name, $email, $edit_id);
//     } else {
//         $stmt = $conn->prepare("UPDATE users SET name=?, email=?, updated_at=NOW() WHERE id=?");
//         $stmt->bind_param("ssi", $name, $email, $edit_id);
//     }
//     $stmt->execute();
//     $stmt->close();
//     header("Location: manage_users.php");
//     exit;
// }

// Fetch all users
$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
include "header.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css"> <!-- External CSS -->
</head>

<body>
    <div class="table-perspective">
        <div class="modern-table-wrap">
            <div class="table-header">
                <h2>User Management</h2>
                <!-- <input type="text" id="searchInput" placeholder="Search users..." /> -->
            </div>
            <div class="table-wrapper">
                <?php if ($result->num_rows == 0): ?>
                    <div class="no-data">
                        <p>No users found in the system.</p>
                    </div>
                <?php else: ?>
                    <table class="modern-table" id="dataTable">

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Last Seen</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($user = $result->fetch_assoc()): ?>
                                <tr>
                                    <form method="POST">
                                        <td data-label="ID">
                                            <?= $user['id']; ?>
                                            <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                                        </td>
                                        <td data-label="Name">
                                            <input type="text" name="name" value="<?= htmlspecialchars($user['name']); ?>"
                                                required>
                                        </td>
                                        <td data-label="Email">
                                            <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>"
                                                required>
                                        </td>
                                        <td data-label="Last Seen"><?= htmlspecialchars($user['last_seen'] ?? 'Never'); ?></td>
                                        <td data-label="Created"><?= date("d M Y", strtotime($user['created_at'])); ?></td>
                                        <td data-label="Updated">
                                            <?= $user['updated_at'] ? date("d M Y", strtotime($user['updated_at'])) : '-'; ?>
                                        </td>
                                        <td data-label="Actions" class="actions">
                                            <!-- <input type="password" name="password" placeholder="New password (optional)" class="password-input"> -->
                                            <button type="submit" name="edit_user" class="btn edit-btn" title="Update User">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <a href="?delete=<?= $user['id']; ?>" class="btn delete-btn"
                                                onclick="return confirm('Are you sure you want to delete this user?');"
                                                title="Delete User">
                                                <i class="fa-solid fa-trash" style="color: red;"></i>
                                            </a>
                                        </td>
                                    </form>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
</body>

</html>