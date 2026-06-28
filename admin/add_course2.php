<?php
include "header.php";
include "../include/config.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create tables (run once, safe to keep)
$conn->query("CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    thumbnail VARCHAR(255),
    banner VARCHAR(255)
)");

$conn->query("CREATE TABLE IF NOT EXISTS course_learn_points (
    id INT AUTO_INCREMENT PRIMARY KEY, course_id INT, point TEXT,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
)");

$conn->query("CREATE TABLE IF NOT EXISTS course_benefits (
    id INT AUTO_INCREMENT PRIMARY KEY, course_id INT, benefit TEXT,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
)");

$conn->query("CREATE TABLE IF NOT EXISTS course_modules (
    id INT AUTO_INCREMENT PRIMARY KEY, course_id INT, title VARCHAR(255), description TEXT, resource_file VARCHAR(255),
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
)");

$conn->query("CREATE TABLE IF NOT EXISTS course_target_audience (
    id INT AUTO_INCREMENT PRIMARY KEY, course_id INT, point TEXT,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
)");

$conn->query("CREATE TABLE IF NOT EXISTS course_faqs (
    id INT AUTO_INCREMENT PRIMARY KEY, course_id INT, question TEXT NOT NULL, answer TEXT NOT NULL,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
)");

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_course'])) {
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);

    $thumbnail = $banner = "";
    if (!empty($_FILES['thumbnail']['name'])) {
        $thumbnail = $uploadDir . time() . "_" . basename($_FILES['thumbnail']['name']);
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnail);
    }
    if (!empty($_FILES['banner']['name'])) {
        $banner = $uploadDir . time() . "_" . basename($_FILES['banner']['name']);
        move_uploaded_file($_FILES['banner']['tmp_name'], $banner);
    }

    $sql = "INSERT INTO courses (title, description, thumbnail, banner) VALUES ('$title', '$description', '$thumbnail', '$banner')";
    if ($conn->query($sql)) {
        $course_id = $conn->insert_id;

        // Helper function
        function insertPoints($conn, $table, $course_id, $input, $col = 'point') {
            if (!empty($input)) {
                $items = array_filter(array_map('trim', explode(',', $input)));
                foreach ($items as $item) {
                    $item = $conn->real_escape_string($item);
                    $col_name = ($table === 'course_benefits') ? 'benefit' : $col;
                    $conn->query("INSERT INTO $table (course_id, $col_name) VALUES ($course_id, '$item')");
                }
            }
        }

        insertPoints($conn, 'course_learn_points', $course_id, $_POST['learn_points']);
        insertPoints($conn, 'course_benefits', $course_id, $_POST['benefits']);
        insertPoints($conn, 'course_target_audience', $course_id, $_POST['target_audience']);

        // Modules
        if (isset($_POST['module_titles'])) {
            for ($i = 0; $i < count($_POST['module_titles']); $i++) {
                $title = $conn->real_escape_string($_POST['module_titles'][$i]);
                $desc  = $conn->real_escape_string($_POST['module_descriptions'][$i]);
                $file  = "";
                if (!empty($_FILES['module_resources']['name'][$i])) {
                    $file = $uploadDir . time() . "_" . basename($_FILES['module_resources']['name'][$i]);
                    move_uploaded_file($_FILES['module_resources']['tmp_name'][$i], $file);
                }
                if (!empty($title)) {
                    $conn->query("INSERT INTO course_modules (course_id, title, description, resource_file) 
                                  VALUES ($course_id, '$title', '$desc', '$file')");
                }
            }
        }

        // FAQs
        if (isset($_POST['faq_questions'])) {
            for ($i = 0; $i < count($_POST['faq_questions']); $i++) {
                $q = $conn->real_escape_string($_POST['faq_questions'][$i]);
                $a = $conn->real_escape_string($_POST['faq_answers'][$i]);
                if (!empty($q) && !empty($a)) {
                    $conn->query("INSERT INTO course_faqs (course_id, question, answer) VALUES ($course_id, '$q', '$a')");
                }
            }
        }

        $message = "<p style='color:green;'>Course added successfully!</p>";
    } else {
        $message = "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Course</title>
    
    <style>
        .container { margin: 2%; margin-top: 20px; background: white; padding: 30px; border-radius: 10px; }
        label { display: block; margin: 15px 0 5px; font-weight: bold; }
        input[type=text], textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        input[type=file] { margin: 10px 0; }
        .module, .faq-item { border: 1px dashed #ff6600; padding: 15px; margin: 15px 0; border-radius: 8px; background: #f8f9fa; }
        button { padding: 10px 15px; background: #ff6600; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #ff6600d2; }
        h2{
            color: #ff6600;
        }
    </style>
    <script>
        function addModule() {
            const container = document.getElementById('modules-container');
            const div = document.createElement('div');
            div.className = 'module';
            div.innerHTML = `
                <input type="text" name="module_titles[]" placeholder="Module Title" required><br><br>
                <textarea name="module_descriptions[]" placeholder="Module Description" required></textarea><br>
                <input type="file" name="module_resources[]"><br>
            `;
            container.appendChild(div);
        }

        function addFAQ() {
            const container = document.getElementById('faqs-container');
            const div = document.createElement('div');
            div.className = 'faq-item';
            div.innerHTML = `
                <input type="text" name="faq_questions[]" placeholder="Question" required><br><br>
                <textarea name="faq_answers[]" placeholder="Answer" required></textarea><br>
            `;
            container.appendChild(div);
        }
    </script>
</head>
<body>
<div class="container">
    <h2>Add New Course</h2>
    <?php echo $message; ?>

    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="add_course" value="1">

        <label>Course Title</label>
        <input type="text" name="title" required>

        <label>Description</label>
        <textarea name="description" rows="5" required></textarea>

        <label>Thumbnail</label>
        <input type="file" name="thumbnail" accept="image/*">

        <label>Banner</label>
        <input type="file" name="banner" accept="image/*">

        <label>What You Will Learn (comma-separated)</label>
        <textarea name="learn_points" rows="3"></textarea>

        <label>Benefits (comma-separated)</label>
        <textarea name="benefits" rows="3"></textarea>

        <label>Who Should Join (comma-separated)</label>
        <textarea name="target_audience" rows="3"></textarea>

        <h3>Course Modules</h3>
        <div id="modules-container">
            <div class="module">
                <input type="text" name="module_titles[]" placeholder="Module Title" required><br><br>
                <textarea name="module_descriptions[]" placeholder="Module Description" required></textarea><br>
                <input type="file" name="module_resources[]">
            </div>
        </div>
        <button type="button" onclick="addModule()">Add Another Module</button>

        <h3 style="margin-top: 30px;">FAQs</h3>
        <div id="faqs-container">
            <div class="faq-item">
                <input type="text" name="faq_questions[]" placeholder="Question" required><br><br>
                <textarea name="faq_answers[]" placeholder="Answer" required></textarea>
            </div>
        </div>
        <button type="button" onclick="addFAQ()">Add Another FAQ</button><br><br>

        <button type="submit" style="padding: 15px 30px; font-size: 16px;">Add Course</button>
    </form>
</div>
</body>
</html>

<?php $conn->close(); ?>