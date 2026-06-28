<?php
include "header.php";
include '../include/config.php';

// Search
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query with JOIN
if ($search != '') {
    $stmt = $conn->prepare("
        SELECT p.*, u.name, e.course_title, e.enrollment_no
        FROM payments p
        JOIN users u ON p.user_id = u.id
        JOIN enrollments e ON p.enrollment_id = e.id
        WHERE u.name LIKE ? 
        OR e.enrollment_no LIKE ? 
        OR p.payment_id LIKE ?
        ORDER BY p.id DESC
    ");
    $like = "%$search%";
    $stmt->bind_param("sss", $like, $like, $like);
} else {
    $stmt = $conn->prepare("
        SELECT p.*, u.name, e.course_title, e.enrollment_no
        FROM payments p
        JOIN users u ON p.user_id = u.id
        JOIN enrollments e ON p.enrollment_id = e.id
        ORDER BY p.id DESC
    ");
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Payments</title>

    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> -->
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
                <h2>Payment History</h2>
            </div>

            <!-- Search -->
            <div class="search-box">
                <form method="GET">
                    <input type="text" name="search" placeholder="Search Name / Enrollment / Payment ID"
                        value="<?= htmlspecialchars($search); ?>">
                    <button type="submit">Search</button>
                </form>
            </div>

            <!-- Table -->
            <div class="table-wrapper">
                <!-- 📊 TABLE -->
                <table class="modern-table" id="dataTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Payment ID</th>
                            <th>User Name</th>
                            <th>Course</th>
                            <th>Enrollment No</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td data-label="ID"><?= $row['id']; ?></td>
                                    <td data-label="ID"><?= htmlspecialchars($row['payment_id']); ?></td>
                                    <td data-label="Name"><?= htmlspecialchars($row['name']); ?></td>
                                    <td data-label="Title"><?= htmlspecialchars($row['course_title']); ?></td>
                                    <td data-label="Enrollment"><?= htmlspecialchars($row['enrollment_no']); ?></td>
                                    <td data-label="Price">₹<?= $row['amount']; ?></td>
                                    <td data-label="Actions">
                                        <?php if ($row['status'] == 'success'): ?>
                                            <span class="status-success"><i class="fa fa-check-circle"></i> Success</span>
                                        <?php elseif ($row['status'] == 'failed'): ?>
                                            <span class="status-failed"><i class="fa fa-times-circle"></i> Failed</span>
                                        <?php else: ?>
                                            <span class="status-pending"><i class="fa fa-clock"></i> Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="Created"><?= $row['created_at']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8">No payment records found</td>
                            </tr>
                        <?php endif; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>