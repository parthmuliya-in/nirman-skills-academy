<?php
include "header.php"; // Assuming this has admin session check if needed
include "../include/config.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Simple search for title only (you can extend it)
$search = "";
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = $conn->real_escape_string(trim($_GET['search']));
}   

// Delete functionality
if (isset($_GET['delete'])) {
    $delete_id = (int) $_GET['delete'];
    // Cascading delete handled by FOREIGN KEY ON DELETE CASCADE
    $conn->query("DELETE FROM courses WHERE id = $delete_id");
    echo "<script>alert('Course deleted successfully!'); window.location='view_courses.php';</script>";
}

// Fetch courses
$sql = "SELECT * FROM courses";
if (!empty($search)) {
    $sql .= " WHERE title LIKE '%$search%'";
}
$sql .= " ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    
        .container {
            margin-top: 20px;
            max-width: 1300px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            /* text-align: center; */
            color: #ff6600;
        }

        .search-bar {
            margin: 20px 0;
            text-align: center;
        }

        .search-bar input {
            padding: 12px 20px;
            width: 400px;
            border: 2px solid #ddd;
            border-radius: 30px;
            font-size: 16px;
        }

        .search-bar button {
            padding: 12px 25px;
            background: #ff6600;
            color: white;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            margin-left: 10px;
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
            border-bottom: 1px solid #eee;
        }

        th {
            background: linear-gradient(135deg, #ff7a00, #ff4d00);
            color: white;
        }

        tr:hover {
            background: #f8fbff;
        }

        .thumb-img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .desc-preview {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .actions a {
            margin: 0 8px;
            font-size: 18px;
        }

        .edit-icon {
            color: #28a745;
        }

        .delete-icon {
            color: #dc3545;
        }

        .count-badge {
            background: #ff6600;
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
        }

        .no-courses {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 18px;
        }

        @media (max-width: 768px) {

            table,
            thead,
            tbody,
            th,
            td,
            tr {
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
            }

            td {
                border: none;
                position: relative;
                padding-left: 50%;
            }

            td:before {
                position: absolute;
                left: 15px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: bold;
            }

            td:nth-of-type(1):before {
                content: "ID";
            }

            td:nth-of-type(2):before {
                content: "Thumbnail";
            }

            td:nth-of-type(3):before {
                content: "Title";
            }

            td:nth-of-type(4):before {
                content: "Description";
            }

            td:nth-of-type(5):before {
                content: "Counts";
            }

            td:nth-of-type(6):before {
                content: "Actions";
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Manage Courses</h1>
        <div style="text-align:right; margin-top:30px;">
            <a href="add_course2.php"
                style="padding:10px 30px; background:#ff6600; color:white; text-decoration:none; border-radius:30px; font-size:18px;">
                <i class="fas fa-plus"></i> Add New Course
            </a>
        </div>

        <!-- Search Bar -->
        <div class="search-bar">
            <form method="get">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                    placeholder="Search by course title...">
                <button type="submit">Search</button>
                <?php if (!empty($search)): ?>
                    <a href="view_courses.php" style="margin-left:15px; color:#ff6600;">Clear</a>
                <?php endif; ?>
            </form>
        </div>

        <?php if ($result->num_rows == 0): ?>
            <div class="no-courses">
                <?php echo !empty($search) ? "No courses found for '<strong>$search</strong>'." : "No courses added yet."; ?>
                <br><br>
                <a href="add_course.php" style="color:#ff6600; font-size:20px;">+ Add New Course</a>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Thumbnail</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Content Count</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($course = $result->fetch_assoc()):
                        $id = $course['id'];

                        // Count related items
                        $learn_count = $conn->query("SELECT COUNT(*) FROM course_learn_points WHERE course_id = $id")->fetch_row()[0];
                        $benefit_count = $conn->query("SELECT COUNT(*) FROM course_benefits WHERE course_id = $id")->fetch_row()[0];
                        $audience_count = $conn->query("SELECT COUNT(*) FROM course_target_audience WHERE course_id = $id")->fetch_row()[0];
                        $module_count = $conn->query("SELECT COUNT(*) FROM course_modules WHERE course_id = $id")->fetch_row()[0];
                        $faq_count = $conn->query("SELECT COUNT(*) FROM course_faqs WHERE course_id = $id")->fetch_row()[0];
                        ?>
                        <tr>
                            <td><?php echo $id; ?></td>
                            <td>
                                <?php if (!empty($course['thumbnail'])): ?>
                                    <img src="<?php echo htmlspecialchars($course['thumbnail']); ?>" alt="Thumb" class="thumb-img">
                                <?php else: ?>
                                    <span>No Image</span>
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo htmlspecialchars($course['title']); ?></strong></td>
                            <td class="desc-preview">
                                <?php echo htmlspecialchars(substr($course['description'], 0, 100)) . (strlen($course['description']) > 100 ? '...' : ''); ?>
                            </td>
                            <td>
                                <span class="count-badge">Learn: <?php echo $learn_count; ?></span>
                                <span class="count-badge">Benefits: <?php echo $benefit_count; ?></span>
                                <span class="count-badge">Audience: <?php echo $audience_count; ?></span>
                                <span class="count-badge">Modules: <?php echo $module_count; ?></span>
                                <span class="count-badge">FAQs: <?php echo $faq_count; ?></span>
                            </td>
                            <td class="actions">
                                <a href="edit_course.php?id=<?php echo $id; ?>" class="edit-icon" title="Edit Course">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="view_courses.php?delete=<?php echo $id; ?>" class="delete-icon" title="Delete Course"
                                    onclick="return confirm('Are you sure you want to delete this course? This action cannot be undone.');">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>

        
    </div>
</body>

</html>

<?php $conn->close(); ?>