<?php
// session_start();
include "header.php";
include "../include/config.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Admin Login Check
/*if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}*/

// Fetch Counts for Dashboard Cards
$total_courses = $conn->query("SELECT COUNT(*) FROM courses")->fetch_row()[0];
$total_enrollments = $conn->query("SELECT COUNT(*) FROM enrollments")->fetch_row()[0];
$total_consultations = $conn->query("SELECT COUNT(*) FROM consultations")->fetch_row()[0];
$total_contacts = $conn->query("SELECT COUNT(*) FROM contact_us")->fetch_row()[0];
$total_subscribers = $conn->query("SELECT COUNT(*) FROM subscribers")->fetch_row()[0];
$total_users = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];

// Recent Enrollments
$recent_enrollments = $conn->query("
    SELECT e.*, c.title AS course_name, u.name AS user_name 
    FROM enrollments e 
    LEFT JOIN courses c ON e.course_id = c.id 
    LEFT JOIN users u ON e.user_id = u.id 
    ORDER BY e.enrolled_at DESC LIMIT 10
");

// Recent Consultations
$recent_consults = $conn->query("
    SELECT co.*, u.name AS user_name 
    FROM consultations co 
    LEFT JOIN users u ON co.user_id = u.id 
    ORDER BY co.created_at DESC LIMIT 10
");

// Recent Contact Messages
$recent_contacts = $conn->query("
    SELECT ct.*, u.name AS user_name 
    FROM contact_us ct 
    LEFT JOIN users u ON ct.user_id = u.id 
    ORDER BY ct.created_at DESC LIMIT 10
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: linear-gradient(135deg, #ff7a00, #ff4d00);
            --success: #28a745;
            --info: #17a2b8;
            --warning: #ffc107;
            --danger: #dc3545;
            --dark: #343a40;
            --light: #f8f9fa;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f0f4f8;
            color: #333;
        }

        .dashboard {
            display: grid;
            grid-template-rows: auto 1fr;
            min-height: 100vh;
        }

        header {
            background: var(--primary);
            color: white;
            padding: 8px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        header h1 {
            font-size: 28px;
        }

        .logout {
            position: absolute;
            top: 20px;
            right: 20px;
            color: white;
            text-decoration: none;
            font-size: 16px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            padding: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card i {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .stat-card.courses i { color: var(--primary); }
        .stat-card.enrollments i { color: var(--success); }
        .stat-card.consultations i { color: var(--info); }
        .stat-card.contacts i { color: var(--warning); }
        .stat-card.subscribers i { color: var(--dark); }
        .stat-card.users i { color: var(--danger); }

        .stat-card h3 {
            font-size: 32px;
            margin: 10px 0;
        }

        .stat-card p {
            color: #666;
            font-size: 16px;
        }

        .tables-section {
            padding: 0 30px 30px;
        }

        .section-title {
            font-size: 22px;
            margin: 30px 0 15px;
            color: var(--primary);
            border-bottom: 2px solid var(--primary);
            padding-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        th {
            background: var(--primary);
            color: white;
            padding: 15px;
            text-align: left;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }

        tr:hover {
            background: #f8fbff;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            tr {
                border: 1px solid #ccc;
                margin-bottom: 15px;
                border-radius: 8px;
                padding: 10px;
            }

            td {
                border: none;
                position: relative;
                padding-left: 50%;
                text-align: right;
            }

            td:before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                width: 45%;
                font-weight: bold;
                text-align: left;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <!-- <header> -->
            <!-- <h1><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h1> -->
            <a href="admin_logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        <!-- </header> -->

        <div class="stats-grid">
            <div class="stat-card courses">
                <i class="fas fa-book-open"></i>
                <h3><?php echo $total_courses; ?></h3>
                <p>Total Courses</p>
            </div>
            <div class="stat-card enrollments">
                <i class="fas fa-user-graduate"></i>
                <h3><?php echo $total_enrollments; ?></h3>
                <p>Enrollments</p>
            </div>
            <div class="stat-card consultations">
                <i class="fas fa-phone"></i>
                <h3><?php echo $total_consultations; ?></h3>
                <p>Consultations</p>
            </div>
            <div class="stat-card contacts">
                <i class="fas fa-envelope"></i>
                <h3><?php echo $total_contacts; ?></h3>
                <p>Contact Messages</p>
            </div>
            <div class="stat-card subscribers">
                <i class="fas fa-bell"></i>
                <h3><?php echo $total_subscribers; ?></h3>
                <p>Subscribers</p>
            </div>
            <div class="stat-card users">
                <i class="fas fa-users"></i>
                <h3><?php echo $total_users; ?></h3>
                <p>Registered Users</p>
            </div>
        </div>

        <div class="tables-section">
            <h2 class="section-title"><i class="fas fa-list-alt"></i> Recent Enrollments</h2>
            <?php if ($recent_enrollments->num_rows == 0): ?>
                <div class="no-data">No enrollments yet.</div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Enrolled At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $recent_enrollments->fetch_assoc()): ?>
                            <tr>
                                <td data-label="ID"><?php echo $row['id']; ?></td>
                                <td data-label="Student"><?php echo htmlspecialchars($row['user_name'] ?: $row['student_name']); ?></td>
                                <td data-label="Course"><?php echo htmlspecialchars($row['course_name'] ?: $row['course_title']); ?></td>
                                <td data-label="Email"><?php echo htmlspecialchars($row['student_email']); ?></td>
                                <td data-label="Phone"><?php echo htmlspecialchars($row['student_phone']); ?></td>
                                <td data-label="Date"><?php echo date("d M Y, h:i A", strtotime($row['enrolled_at'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <h2 class="section-title"><i class="fas fa-headset"></i> Recent Consultations</h2>
            <?php if ($recent_consults->num_rows == 0): ?>
                <div class="no-data">No consultation requests.</div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Service</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $recent_consults->fetch_assoc()): ?>
                            <tr>
                                <td data-label="Name"><?php echo htmlspecialchars($row['user_name'] ?: $row['name']); ?></td>
                                <td data-label="Email"><?php echo htmlspecialchars($row['email']); ?></td>
                                <td data-label="Contact"><?php echo htmlspecialchars($row['contact']); ?></td>
                                <td data-label="Service"><?php echo htmlspecialchars($row['service']); ?></td>
                                <td data-label="Date"><?php echo date("d M Y", strtotime($row['created_at'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <h2 class="section-title"><i class="fas fa-envelope-open-text"></i> Recent Contact Messages</h2>
            <?php if ($recent_contacts->num_rows == 0): ?>
                <div class="no-data">No contact messages.</div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $recent_contacts->fetch_assoc()): ?>
                            <tr>
                                <td data-label="Name"><?php echo htmlspecialchars($row['user_name'] ?: $row['name']); ?></td>
                                <td data-label="Email"><?php echo htmlspecialchars($row['email']); ?></td>
                                <td data-label="Subject"><?php echo htmlspecialchars($row['subject']); ?></td>
                                <td data-label="Message"><?php echo htmlspecialchars(substr($row['message'], 0, 80)) . '...'; ?></td>
                                <td data-label="Date"><?php echo date("d M Y", strtotime($row['created_at'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>