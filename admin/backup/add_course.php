<?php
include "../include/config.php";
//$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create tables if not exists (for demo purposes)
$createCourses = "CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    thumbnail VARCHAR(255),
    banner VARCHAR(255)
)";

$createLearnPoints = "CREATE TABLE IF NOT EXISTS course_learn_points (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    point TEXT NOT NULL,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
)";

$createBenefits = "CREATE TABLE IF NOT EXISTS course_benefits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    benefit TEXT NOT NULL,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
)";

$createModules = "CREATE TABLE IF NOT EXISTS course_modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    resource_file VARCHAR(255),
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
)";

$createTargetAudience = "CREATE TABLE IF NOT EXISTS course_target_audience (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    point TEXT NOT NULL,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
)";

$conn->query($createCourses);
$conn->query($createLearnPoints);
$conn->query($createBenefits);
$conn->query($createModules);
$conn->query($createTargetAudience);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Upload directory
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Course details
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Handle thumbnail upload
    $thumbnail = "";
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
        $thumbnail = $uploadDir . basename($_FILES['thumbnail']['name']);
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnail);
    }

    // Handle banner upload
    $banner = "";
    if (isset($_FILES['banner']) && $_FILES['banner']['error'] == 0) {
        $banner = $uploadDir . basename($_FILES['banner']['name']);
        move_uploaded_file($_FILES['banner']['tmp_name'], $banner);
    }

    // Insert course
    $sql = "INSERT INTO courses (title, description, thumbnail, banner) VALUES ('$title', '$description', '$thumbnail', '$banner')";
    if ($conn->query($sql) === TRUE) {
        $course_id = $conn->insert_id;

        // What you learn points (assuming comma-separated for simplicity)
        if (!empty($_POST['learn_points'])) {
            $learn_points = explode(",", $_POST['learn_points']);
            foreach ($learn_points as $point) {
                $point = trim(mysqli_real_escape_string($conn, $point));
                if (!empty($point)) {
                    $conn->query("INSERT INTO course_learn_points (course_id, point) VALUES ($course_id, '$point')");
                }
            }
        }

        // Benefits (comma-separated)
        if (!empty($_POST['benefits'])) {
            $benefits = explode(",", $_POST['benefits']);
            foreach ($benefits as $benefit) {
                $benefit = trim(mysqli_real_escape_string($conn, $benefit));
                if (!empty($benefit)) {
                    $conn->query("INSERT INTO course_benefits (course_id, benefit) VALUES ($course_id, '$benefit')");
                }
            }
        }

        // Target audience (comma-separated)
        if (!empty($_POST['target_audience'])) {
            $targets = explode(",", $_POST['target_audience']);
            foreach ($targets as $target) {
                $target = trim(mysqli_real_escape_string($conn, $target));
                if (!empty($target)) {
                    $conn->query("INSERT INTO course_target_audience (course_id, point) VALUES ($course_id, '$target')");
                }
            }
        }

        // Modules (multiple, assuming arrays)
        if (isset($_POST['module_titles']) && is_array($_POST['module_titles'])) {
            $titles = $_POST['module_titles'];
            $descs = $_POST['module_descriptions'];
            $files = $_FILES['module_resources'];

            for ($i = 0; $i < count($titles); $i++) {
                $mod_title = mysqli_real_escape_string($conn, $titles[$i]);
                $mod_desc = mysqli_real_escape_string($conn, $descs[$i]);
                $mod_file = "";

                if (isset($files['name'][$i]) && $files['error'][$i] == 0) {
                    $mod_file = $uploadDir . basename($files['name'][$i]);
                    move_uploaded_file($files['tmp_name'][$i], $mod_file);
                }

                if (!empty($mod_title) && !empty($mod_desc)) {
                    $conn->query("INSERT INTO course_modules (course_id, title, description, resource_file) VALUES ($course_id, '$mod_title', '$mod_desc', '$mod_file')");
                }
            }
        }

        echo "Course added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Course</title>
    <script>
        function addModule() {
            const container = document.getElementById('modules-container');
            const div = document.createElement('div');
            div.innerHTML = `
                <input type="text" name="module_titles[]" placeholder="Module Title" required><br>
                <textarea name="module_descriptions[]" placeholder="Module Description" required></textarea><br>
                <input type="file" name="module_resources[]"><br><br>
            `;
            container.appendChild(div);
        }
    </script>
</head>
<body>
    <h2>Add New Course</h2>
    <form method="post" enctype="multipart/form-data">
        <label>Course Title:</label><br>
        <input type="text" name="title" required><br><br>

        <label>Course Description:</label><br>
        <textarea name="description" required></textarea><br><br>

        <label>Course Thumbnail:</label><br>
        <input type="file" name="thumbnail"><br><br>

        <label>Course Banner:</label><br>
        <input type="file" name="banner"><br><br>

        <label>What You Learn (comma-separated):</label><br>
        <textarea name="learn_points" placeholder="Point 1, Point 2, etc."></textarea><br><br>

        <label>Benefits (comma-separated):</label><br>
        <textarea name="benefits" placeholder="Benefit 1, Benefit 2, etc."></textarea><br><br>

        <label>Who Should Join (comma-separated):</label><br>
        <textarea name="target_audience" placeholder="Audience 1, Audience 2, etc."></textarea><br><br>

        <label>Course Modules:</label><br>
        <div id="modules-container">
            <!-- Initial module -->
            <input type="text" name="module_titles[]" placeholder="Module Title" required><br>
            <textarea name="module_descriptions[]" placeholder="Module Description" required></textarea><br>
            <input type="file" name="module_resources[]"><br><br>
        </div>
        <button type="button" onclick="addModule()">Add Another Module</button><br><br>

        <input type="submit" value="Add Course">
    </form>
</body>
</html>