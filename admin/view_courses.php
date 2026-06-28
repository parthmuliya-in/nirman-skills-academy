<?php
include "header.php"; // Make sure this checks for admin session
include "../include/config.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Simple search by title
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Delete course
if (isset($_GET['delete'])) {
    $delete_id = (int) $_GET['delete'];
    $conn->query("DELETE FROM courses WHERE id = $delete_id");
    echo "<script>alert('Course deleted successfully!'); window.location='view_courses.php';</script>";
    exit;
}

// Fetch courses
$sql = "SELECT * FROM courses";
if (!empty($search)) {
    $search_esc = $conn->real_escape_string($search);
    $sql .= " WHERE title LIKE '%$search_esc%'";
}
$sql .= " ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 20px;
        } */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            /* background: linear-gradient(135deg, #ff6600, #ff4500); */
            color: #ff6600;
            padding: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            margin: 0;
            font-size: 2rem;
        }

        .add-btn {
            background: white;
            color: #ff6600;
            padding: 12px 25px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1rem;
            transition: 0.3s;
        }

        .add-btn:hover {
            background: #ffe0cc;
            transform: scale(1.05);
        }

        /* Search */
        .search-bar {
            padding: 25px;
            background: #f9f9f9;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        .search-bar input {
            padding: 14px 25px;
            width: 500px;
            max-width: 90%;
            border: 2px solid #ddd;
            border-radius: 50px;
            font-size: 1.1rem;
            outline: none;
        }

        .search-bar input:focus {
            border-color: #ff6600;
        }

        .search-bar button {
            padding: 14px 30px;
            background: #ff6600;
            color: white;
            border: none;
            border-radius: 50px;
            margin-left: 10px;
            cursor: pointer;
            font-size: 1.1rem;
        }

        .search-bar button:hover {
            background: #e55a00;
        }

        .clear-link {
            color: #ff6600;
            margin-left: 15px;
            text-decoration: none;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #ff6600;
            color: white;
            padding: 18px;
            text-align: left;
            font-weight: 600;
        }

        td {
            padding: 18px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        tr:hover {
            background: #fff8f2;
        }

        .thumb-img {
            width: 100px;
            height: 75px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .no-thumb {
            color: #999;
            font-style: italic;
        }

        .desc-preview {
            max-width: 350px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: #555;
        }

        .count-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .count-badge {
            background: #ff6600;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            min-width: 60px;
            text-align: center;
        }

        .actions a {
            margin: 0 10px;
            font-size: 1.4rem;
            transition: 0.3s;
        }

        .edit-icon {
            color: #28a745;
        }

        .edit-icon:hover {
            color: #218838;
            transform: scale(1.2);
        }

        .delete-icon {
            color: #dc3545;
        }

        .delete-icon:hover {
            color: #c82333;
            transform: scale(1.2);
        }

        .no-courses {
            text-align: center;
            padding: 80px 20px;
            color: #777;
            font-size: 1.3rem;
        }

        .no-courses a {
            color: #ff6600;
            font-size: 1.4rem;
            text-decoration: none;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .search-bar input {
                width: 70%;
                margin-bottom: 10px;
            }

            .search-bar button {
                width: 70%;
            }
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
            }

            thead tr {
                display: none;
            }

            tr {
                border: 1px solid #ddd;
                border-radius: 12px;
                margin: 15px 0;
                padding: 15px;
                background: #fcfcfc;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            }

            td {
                border: none;
                padding: 10px 0;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            td:before {
                content: attr(data-label);
                font-weight: bold;
                color: #ff6600;
                min-width: 120px;
            }

            .actions {
                justify-content: center !important;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h2><i class="fas fa-book"></i> Manage Courses</h2>
            <a href="add_course2.php" class="add-btn">
                <i class="fas fa-plus"></i> Add New Course
            </a>
        </div>

        <!-- Search -->
        <div class="search-bar">
            <form method="get">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                    placeholder="Search by course title...">
                <button type="submit"><i class="fas fa-search"></i> Search</button>
                <?php if (!empty($search)): ?>
                    <a href="view_courses.php" class="clear-link">Clear Search</a>
                <?php endif; ?>
            </form>
        </div>

        <?php if ($result->num_rows == 0): ?>
            <div class="no-courses">
                <i class="fas fa-folder-open" style="font-size:4rem; color:#ddd;"></i><br><br>
                <?php echo !empty($search) ? "No courses found for '<strong>" . htmlspecialchars($search) . "</strong>'." : "No courses have been added yet."; ?>
                <br><br>
                <a href="add_course2.php"><i class="fas fa-plus-circle"></i> Add Your First Course</a>
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

                        // Count related content
                        $learn_count = $conn->query("SELECT COUNT(*) FROM course_learn_points WHERE course_id = $id")->fetch_row()[0];
                        $benefit_count = $conn->query("SELECT COUNT(*) FROM course_benefits WHERE course_id = $id")->fetch_row()[0];
                        $audience_count = $conn->query("SELECT COUNT(*) FROM course_target_audience WHERE course_id = $id")->fetch_row()[0];
                        $module_count = $conn->query("SELECT COUNT(*) FROM course_modules WHERE course_id = $id")->fetch_row()[0];
                        $faq_count = $conn->query("SELECT COUNT(*) FROM course_faqs WHERE course_id = $id")->fetch_row()[0];
                        ?>
                        <tr>
                            <td data-label="ID"><?php echo $id; ?></td>
                            <td data-label="Thumbnail">
                                <?php if (!empty($course['thumbnail'])): ?>
                                    <img src="<?php echo htmlspecialchars($course['thumbnail']); ?>" alt="Thumbnail"
                                        class="thumb-img">
                                <?php else: ?>
                                    <span class="no-thumb">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td data-label="Title"><strong><?php echo htmlspecialchars($course['title']); ?></strong></td>
                            <td data-label="Description" class="desc-preview">
                                <?php echo htmlspecialchars(substr($course['description'], 0, 120)) . (strlen($course['description']) > 120 ? '...' : ''); ?>
                            </td>
                            <td data-label="Content Count" class="count-badges">
                                <span class="count-badge">Learn: <?php echo $learn_count; ?></span>
                                <span class="count-badge">Ben: <?php echo $benefit_count; ?></span>
                                <span class="count-badge">Aud: <?php echo $audience_count; ?></span>
                                <span class="count-badge">Mod: <?php echo $module_count; ?></span>
                                <span class="count-badge">FAQ: <?php echo $faq_count; ?></span>
                            </td>
                            <td data-label="Actions" class="actions">
                                <a href="edit_course.php?id=<?php echo $id; ?>" class="edit-icon" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="view_courses.php?delete=<?php echo $id; ?>" class="delete-icon" title="Delete"
                                    onclick="return confirm('Delete this course permanently? All related data will be removed.');">
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