<?php
// session_start();
include "../include/config.php";
include "header.php";


// ================= SEARCH =================
$search = $_GET['search'] ?? '';

$sql = "SELECT p.id, p.title, p.description, p.status, p.created_at,
               e.student_name, e.enrollment_no
        FROM practices p
        JOIN enrollments e ON p.enrollment_id = e.id
        WHERE e.student_name LIKE ? OR e.enrollment_no LIKE ?
        ORDER BY p.id DESC";

$stmt = $conn->prepare($sql);
$searchTerm = "%$search%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

// ================= UPDATE STATUS =================
if (isset($_POST['update_status'])) {
    $pid = $_POST['practice_id'];
    $status = $_POST['status'];

    $update = $conn->prepare("UPDATE practices SET status=? WHERE id=?");
    $update->bind_param("si", $status, $pid);
    $update->execute();

    header("Location: view_practices.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin - Practices</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Container */
        .search-box {
            width: 100%;
            max-width: 500px;
            margin: 20px auto;
            padding: 0 10px;
        }

        /* Form */
        .search-box form {
            display: flex;
            align-items: center;
            border: 2px solid #ff7a00;
            border-radius: 50px;
            overflow: hidden;
            background: #fff;
        }

        /* Input */
        .search-box input {
            flex: 1;
            padding: 12px 15px;
            border: none;
            outline: none;
            font-size: 16px;
        }

        /* Button */
        .search-box button {
            padding: 12px 20px;
            border: none;
            background: #ff7a00;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        /* Hover effect */
        .search-box button:hover {
            background: #e66a00;
        }

        /* Tablet */
        @media (max-width: 768px) {
            .search-box form {
                border-radius: 40px;
            }

            .search-box input {
                font-size: 14px;
                padding: 10px;
            }

            .search-box button {
                font-size: 14px;
                padding: 10px 15px;
            }
        }

        /* Mobile */
        @media (max-width: 480px) {
            .search-box form {
                flex-direction: column;
                border-radius: 10px;
            }

            .search-box input {
                width: 100%;
                border-bottom: 1px solid #ddd;
            }

            .search-box button {
                width: 100%;
                border-radius: 0 0 10px 10px;
            }
        }
    </style>
</head>

<body>
    <div class="table-perspective">
        <div class="modern-table-wrap">
            <div class="table-header">
                <h2>Manage Practices</h2>
            </div>

            <!-- 🔍 SEARCH -->
            <div class="search-box">
                <form method="GET">
                    <input type="text" name="search" placeholder="Search Name / Enrollment No"
                        value="<?= htmlspecialchars($search); ?>">
                    <button type="submit">Search</button>
                </form>
            </div>
            <div class="table-wrapper">
                <!-- 📊 TABLE -->
                <table class="modern-table" id="dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Enrollment No</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Created Date</th>
                            <th>Update</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <?php
                    $i = 1;
                    while ($row = $result->fetch_assoc()): ?>
                        <tbody>
                            <tr>
                                <td data-label="ID"><?= $i++; ?></td>
                                <td data-label="Name"><?= htmlspecialchars($row['student_name'] ?? ''); ?></td>
                                <td data-label="Email"><?= htmlspecialchars($row['enrollment_no'] ?? ''); ?></td>
                                <td data-label="Last Seen"><?= htmlspecialchars($row['title'] ?? ''); ?></td>
                                <td data-label="Created"><?= htmlspecialchars($row['description'] ?? ''); ?></td>

                                <td data-label="Actions" class="<?= strtolower($row['status']); ?>">
                                    <?= ucfirst($row['status']); ?>
                                </td>

                                <td data-label="Created"><?= date("d M Y", strtotime($row['created_at'])); ?></td>

                                <td data-label="Actions">
                                    <form method="POST">
                                        <input type="hidden" name="practice_id" value="<?= $row['id']; ?>">

                                        <select name="status">
                                            <option value="pending" <?= $row['status'] == 'pending' ? 'selected' : ''; ?>>
                                                Pending</option>
                                            <option value="completed" <?= $row['status'] == 'completed' ? 'selected' : ''; ?>>
                                                Completed
                                            </option>
                                        </select>
                                <td>
                                    <button class="btn" name="update_status">Update</button>
                                </td>
                                </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</body>

</html>