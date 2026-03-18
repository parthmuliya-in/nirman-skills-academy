<?php
// it will in  root directory
include "../header.php";
include "../../include/config.php"; // Assuming this sets up $conn

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Existing tables (unchanged)
$conn->query("CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    thumbnail VARCHAR(255),
    banner VARCHAR(255)
)");

$conn->query("CREATE TABLE IF NOT EXISTS course_learn_points (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    point TEXT,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
)");

$conn->query("CREATE TABLE IF NOT EXISTS course_benefits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    benefit TEXT,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
)");

$conn->query("CREATE TABLE IF NOT EXISTS course_modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    title VARCHAR(255),
    description TEXT,
    resource_file VARCHAR(255),
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
)");

$conn->query("CREATE TABLE IF NOT EXISTS course_target_audience (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    point TEXT,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
)");

// NEW TABLE: FAQs
$conn->query("CREATE TABLE IF NOT EXISTS course_faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
)");

// Handle form submission
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_course'])) {
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);

    $thumbnail = $banner = "";
    if (!empty($_FILES['thumbnail']['name'])) {
        $thumbnail = $uploadDir . basename($_FILES['thumbnail']['name']);
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnail);
    }
    if (!empty($_FILES['banner']['name'])) {
        $banner = $uploadDir . basename($_FILES['banner']['name']);
        move_uploaded_file($_FILES['banner']['tmp_name'], $banner);
    }

    $sql = "INSERT INTO courses (title, description, thumbnail, banner) 
            VALUES ('$title', '$description', '$thumbnail', '$banner')";
    
    if ($conn->query($sql)) {
        $course_id = $conn->insert_id;

        // Helper function for comma-separated points
        function insertPoints($conn, $table, $course_id, $input, $column = 'point') {
            if (!empty($input)) {
                $points = array_map('trim', explode(',', $input));
                foreach ($points as $p) {
                    if (!empty($p)) {
                        $p = $conn->real_escape_string($p);
                        $col = ($table === 'course_benefits') ? 'benefit' : $column;
                        $conn->query("INSERT INTO $table (course_id, $col) VALUES ($course_id, '$p')");
                    }
                }
            }
        }

        insertPoints($conn, 'course_learn_points', $course_id, $_POST['learn_points']);
        insertPoints($conn, 'course_benefits', $course_id, $_POST['benefits']);
        insertPoints($conn, 'course_target_audience', $course_id, $_POST['target_audience']);

        // Modules
        if (isset($_POST['module_titles'])) {
            for ($i = 0; $i < count($_POST['module_titles']); $i++) {
                $mod_title = $conn->real_escape_string($_POST['module_titles'][$i]);
                $mod_desc  = $conn->real_escape_string($_POST['module_descriptions'][$i]);
                $mod_file  = "";

                if (!empty($_FILES['module_resources']['name'][$i])) {
                    $mod_file = $uploadDir . basename($_FILES['module_resources']['name'][$i]);
                    move_uploaded_file($_FILES['module_resources']['tmp_name'][$i], $mod_file);
                }

                if (!empty($mod_title)) {
                    $conn->query("INSERT INTO course_modules (course_id, title, description, resource_file) 
                                  VALUES ($course_id, '$mod_title', '$mod_desc', '$mod_file')");
                }
            }
        }

        // NEW: FAQs
        if (isset($_POST['faq_questions']) && is_array($_POST['faq_questions'])) {
            for ($i = 0; $i < count($_POST['faq_questions']); $i++) {
                $question = $conn->real_escape_string($_POST['faq_questions'][$i]);
                $answer   = $conn->real_escape_string($_POST['faq_answers'][$i]);

                if (!empty($question) && !empty($answer)) {
                    $conn->query("INSERT INTO course_faqs (course_id, question, answer) 
                                  VALUES ($course_id, '$question', '$answer')");
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
    <title>Course Management</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f9f9f9; }
        .container { max-width: 1200px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-section, .display-section { margin-bottom: 50px; }
        label { display: block; margin: 15px 0 5px; font-weight: bold; }
        input[type=text], textarea { width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 5px; }
        input[type=file] { margin: 10px 0; }
        .module, .faq-item { border: 1px dashed #aaa; padding: 15px; margin: 15px 0; border-radius: 8px; background: #f5f5f5; }
        button { padding: 10px 15px; background: #007cba; color: white; border: none; cursor: pointer; border-radius: 5px; }
        button:hover { background: #005a87; }
        .course-card { border: 1px solid #ddd; margin: 30px 0; padding: 25px; border-radius: 10px; background: #fff; }
        .course-thumb { max-width: 250px; border-radius: 8px; }
        .course-banner { width: 100%; max-height: 350px; object-fit: cover; border-radius: 8px; margin: 15px 0; }
        .faq-accordion { margin: 10px 0; }
        .faq-question { background: #007cba; color: white; padding: 12px; cursor: pointer; border-radius: 5px; margin: 5px 0; }
        .faq-answer { padding: 15px; background: #f0f0f0; display: none; border-radius: 5px; }
        .faq-answer.active { display: block; }
    </style>
    <script>
        function addModule() {
            const container = document.getElementById('modules-container');
            const div = document.createElement('div');
            div.className = 'module';
            div.innerHTML = `
                <input type="text" name="module_titles[]" placeholder="Module Title" required><br>
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
                <input type="text" name="faq_questions[]" placeholder="Question" required><br>
                <textarea name="faq_answers[]" placeholder="Answer" required></textarea><br>
            `;
            container.appendChild(div);
        }

        // Accordion for FAQs in display
        function toggleFAQ(element) {
            const answer = element.nextElementSibling;
            answer.classList.toggle('active');
        }
    </script>
</head>
<body>
<div class="container">

    <!-- Add Course Form -->
    <div class="form-section">
        <h2>Add New Course</h2>
        <?php echo $message; ?>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="add_course" value="1">

            <label>Course Title:</label>
            <input type="text" name="title" required>

            <label>Course Description:</label>
            <textarea name="description" rows="4" required></textarea>

            <label>Thumbnail:</label>
            <input type="file" name="thumbnail" accept="image/*">

            <label>Banner:</label>
            <input type="file" name="banner" accept="image/*">

            <label>What You Will Learn (comma-separated):</label>
            <textarea name="learn_points" rows="3"></textarea>

            <label>Benefits (comma-separated):</label>
            <textarea name="benefits" rows="3"></textarea>

            <label>Who Should Join (comma-separated):</label>
            <textarea name="target_audience" rows="3"></textarea>

            <h3>Course Modules</h3>
            <div id="modules-container">
                <div class="module">
                    <input type="text" name="module_titles[]" placeholder="Module Title" required>
                    <textarea name="module_descriptions[]" placeholder="Module Description" required></textarea>
                    <input type="file" name="module_resources[]">
                </div>
            </div>
            <button type="button" onclick="addModule()">Add Another Module</button>

            <!-- NEW: FAQs Section -->
            <h3 style="margin-top: 30px;">Frequently Asked Questions (FAQs)</h3>
            <div id="faqs-container">
                <div class="faq-item">
                    <input type="text" name="faq_questions[]" placeholder="Enter Question" required>
                    <textarea name="faq_answers[]" placeholder="Enter Answer" required></textarea>
                </div>
            </div>
            <button type="button" onclick="addFAQ()">Add Another FAQ</button><br><br>

            <input type="submit" value="Add Course" style="padding: 12px 30px; font-size: 16px;">
        </form>
    </div>

    <!-- Display All Courses -->
    <div class="display-section">
        <h2>All Courses</h2>
        <?php
        $courses_result = $conn->query("SELECT * FROM courses ORDER BY id DESC");
        if ($courses_result->num_rows == 0) {
            echo "<p>No courses added yet.</p>";
        } else {
            while ($course = $courses_result->fetch_assoc()) {
                $course_id = $course['id'];
                echo "<div class='course-card'>";
                echo "<h3>" . htmlspecialchars($course['title']) . "</h3>";

                if (!empty($course['thumbnail'])) {
                    echo "<img src='" . htmlspecialchars($course['thumbnail']) . "' alt='Thumbnail' class='course-thumb'>";
                }
                if (!empty($course['banner'])) {
                    echo "<img src='" . htmlspecialchars($course['banner']) . "' alt='Banner' class='course-banner'>";
                }

                echo "<p><strong>Description:</strong> " . nl2br(htmlspecialchars($course['description'])) . "</p>";

                // What you learn
                $learn = $conn->query("SELECT point FROM course_learn_points WHERE course_id = $course_id");
                if ($learn->num_rows > 0) {
                    echo "<h4>What You Will Learn:</h4><ul>";
                    while ($row = $learn->fetch_assoc()) echo "<li>" . htmlspecialchars($row['point']) . "</li>";
                    echo "</ul>";
                }

                // Benefits
                $benefits = $conn->query("SELECT benefit FROM course_benefits WHERE course_id = $course_id");
                if ($benefits->num_rows > 0) {
                    echo "<h4>Benefits:</h4><ul>";
                    while ($row = $benefits->fetch_assoc()) echo "<li>" . htmlspecialchars($row['benefit']) . "</li>";
                    echo "</ul>";
                }

                // Target Audience
                $audience = $conn->query("SELECT point FROM course_target_audience WHERE course_id = $course_id");
                if ($audience->num_rows > 0) {
                    echo "<h4>Who Should Join:</h4><ul>";
                    while ($row = $audience->fetch_assoc()) echo "<li>" . htmlspecialchars($row['point']) . "</li>";
                    echo "</ul>";
                }

                // Modules
                $modules = $conn->query("SELECT * FROM course_modules WHERE course_id = $course_id");
                if ($modules->num_rows > 0) {
                    echo "<h4>Course Modules:</h4>";
                    while ($mod = $modules->fetch_assoc()) {
                        echo "<div style='border-left: 4px solid #007cba; padding-left: 15px; margin: 15px 0;'>";
                        echo "<h5>" . htmlspecialchars($mod['title']) . "</h5>";
                        echo "<p>" . nl2br(htmlspecialchars($mod['description'])) . "</p>";
                        if (!empty($mod['resource_file'])) {
                            echo "<p><a href='" . htmlspecialchars($mod['resource_file']) . "' target='_blank'>Download Resource</a></p>";
                        }
                        echo "</div>";
                    }
                }

                // NEW: Display FAQs
                $faqs = $conn->query("SELECT question, answer FROM course_faqs WHERE course_id = $course_id");
                if ($faqs->num_rows > 0) {
                    echo "<h4>Frequently Asked Questions</h4><div class='faq-accordion'>";
                    while ($faq = $faqs->fetch_assoc()) {
                        echo "<div class='faq-question' onclick='toggleFAQ(this)'>" 
                           . htmlspecialchars($faq['question']) . "</div>";
                        echo "<div class='faq-answer'>" 
                           . nl2br(htmlspecialchars($faq['answer'])) . "</div>";
                    }
                    echo "</div>";
                }

                echo "</div>"; // end course-card
            }
        }
        ?>
    </div>

</div>
</body>
</html>

<?php $conn->close(); ?>