<?php
include "header.php";
include '../include/config.php';

// Fetch users & enrollments
$users = $conn->query("SELECT id, name FROM users ORDER BY name ASC");
$enrollments = $conn->query("SELECT id, course_title, enrollment_no FROM enrollments ORDER BY id DESC");

// Insert payment
if (isset($_POST['submit'])) {
    $user_id = $_POST['user_id'];
    $enrollment_id = $_POST['enrollment_id'];
    $payment_id = $_POST['payment_id'];
    $amount = $_POST['amount'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO payments (user_id, enrollment_id, payment_id, amount, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisds", $user_id, $enrollment_id, $payment_id, $amount, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Payment Added Successfully'); window.location='payment.php';</script>";
    } else {
        echo "<script>alert('Error adding payment');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Payment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css"> <!-- External CSS -->

    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body */
        /* body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        } */

        /* Container */
        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        /* Heading */
        h2 {
            text-align: center;
            margin-bottom: 20px;
            /* font-size: 22px; */
            color: #ff6600;
        }

        /* Form */
        .form-group {
            margin-bottom: 15px;
        }

        /* Label */
        label {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
        }

        /* Inputs */
        input,
        select {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background: #fff;
        }

        /* Fix dropdown overflow issue */
        select {
            white-space: normal;
            word-wrap: break-word;
        }

        /* Focus */
        input:focus,
        select:focus {
            border-color: #FF6600;
            outline: none;
        }

        /* Button */
        button {
            width: 100%;
            padding: 12px;
            font-size: 15px;
            background: #FF6600;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        /* Tablet */
        @media (max-width: 768px) {
            .container {
                margin: 15px;
                padding: 15px;
            }

            h2 {
                font-size: 20px;
            }
        }

        /* Mobile Fix (IMPORTANT) */
        @media (max-width: 480px) {

            body {
                padding: 10px;
            }

            .container {
                margin: 0;
                padding: 15px;
                border-radius: 8px;
                box-shadow: none;
                /* remove shadow for small screens */
            }

            h2 {
                font-size: 18px;
            }

            label {
                font-size: 13px;
            }

            input,
            select {
                font-size: 13px;
                padding: 10px;
            }

            button {
                font-size: 14px;
                padding: 11px;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Add Payment</h2>

        <form method="POST">

            <div class="form-group">
                <label>Select User</label>
                <select name="user_id" required>
                    <option value="">-- Select User --</option>
                    <?php while ($u = $users->fetch_assoc()): ?>
                        <option value="<?= $u['id']; ?>">
                            <?= htmlspecialchars($u['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Select Enrollment</label>
                <select name="enrollment_id" required>
                    <option value="">-- Select Enrollment --</option>
                    <?php while ($e = $enrollments->fetch_assoc()): ?>
                        <option value="<?= $e['id']; ?>">
                            <?= htmlspecialchars($e['course_title']); ?> (
                            <?= $e['enrollment_no']; ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Payment ID</label>
                <input type="text" name="payment_id" required>
            </div>

            <div class="form-group">
                <label>Amount</label>
                <input type="number" step="0.01" name="amount" required>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="success">Success</option>
                    <option value="failed">Failed</option>
                    <option value="pending">Pending</option>
                </select>
            </div>

            <button type="submit" name="submit">Add Payment</button>

        </form>
    </div>

</body>

</html>