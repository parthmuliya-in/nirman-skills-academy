<?php
session_start();
include '../include/config.php';
// 🔒 Check login
if(!isset($_SESSION['student_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['student_id'];
// Fetch Payment Data with User Name and Course Title

$sql = "
    SELECT 
        p.id AS payment_id,
        p.payment_id AS gateway_payment_id,
        p.amount,
        p.status,
        p.created_at,
        u.name AS user_name,
        e.course_title
    FROM payments p
    LEFT JOIN enrollments e ON p.enrollment_id = e.id
    LEFT JOIN users u ON e.user_id = u.id
    WHERE e.id = '$user_id'
    ORDER BY p.created_at DESC
";


$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        .table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scroll-behavior: smooth;
            user-select: none;
            touch-action: pan-x;
            width: 100%;
        }

        .table-wrapper::-webkit-scrollbar {
            display: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        table th, table td {
            padding: 14px 16px;
            border: 1px solid #ddd;
            text-align: center;
        }

        table th {
            background: #FF6600;
            color: white;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .status-success { color: #4CAF50; font-weight: bold; }
        .status-failed  { color: #e53935; font-weight: bold; }
        .status-pending { color: #ff9800; font-weight: bold; }
    </style>
</head>
<body>

    <div class="container">
        <h2>Payment Details</h2>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>User Name</th>
                        <th>Course</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): 
                            
                            $statusClass = '';
                            $statusIcon = '';
                            $statusText = ucfirst($row['status']);

                            if (strtolower($row['status']) == 'success' || strtolower($row['status']) == 'completed') {
                                $statusClass = 'status-success';
                                $statusIcon = '<i class="fa fa-check-circle"></i> ';
                            } elseif (strtolower($row['status']) == 'failed') {
                                $statusClass = 'status-failed';
                                $statusIcon = '<i class="fa fa-times-circle"></i> ';
                            } else {
                                $statusClass = 'status-pending';
                                $statusIcon = '<i class="fa fa-clock"></i> ';
                            }
                        ?>
                            <tr>
                                <td><?= '#'. htmlspecialchars($row['gateway_payment_id'] ?? $row['payment_id']) ?></td>
                                <td><?= htmlspecialchars($row['user_name'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($row['course_title'] ?? 'N/A') ?></td>
                                <td><strong>₹<?= number_format($row['amount'], 2) ?></strong></td>
                                <td class="<?= $statusClass ?>"><?= $statusIcon . $statusText ?></td>
                                <td><?= date('Y-m-d', strtotime($row['created_at'])) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No payment records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Drag and Touch Scroll Support
        const slider = document.querySelector(".table-wrapper");
        let isDown = false;
        let startX, scrollLeft;

        slider.addEventListener("mousedown", (e) => {
            isDown = true;
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });

        slider.addEventListener("mouseleave", () => isDown = false);
        slider.addEventListener("mouseup", () => isDown = false);

        slider.addEventListener("mousemove", (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 2;
            slider.scrollLeft = scrollLeft - walk;
        });

        // Touch Support for Mobile
        let touchStartX = 0;
        let touchScrollLeft = 0;

        slider.addEventListener("touchstart", (e) => {
            touchStartX = e.touches[0].pageX;
            touchScrollLeft = slider.scrollLeft;
        });

        slider.addEventListener("touchmove", (e) => {
            const x = e.touches[0].pageX;
            const walk = (x - touchStartX) * 2;
            slider.scrollLeft = touchScrollLeft - walk;
        });
    </script>

</body>
</html>

<?php
// Close connection
mysqli_close($conn);
?>