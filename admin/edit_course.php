<?php
// ini_set("display_errors", 1);
// error_reporting(E_ALL);
// Start session if needed (before any output)
// session_start(); // Add this if you use sessions
error_reporting(0);
include "header.php";
include "../include/config.php";


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$course_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($course_id <= 0) {
    die("Invalid course ID.");
}

// Fetch main course early (we need it for update)
$course_stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
$course_stmt->bind_param("i", $course_id);
$course_stmt->execute();
$course_result = $course_stmt->get_result();
$course = $course_result->fetch_assoc();

if (!$course) {
    die("Course not found.");
}

// Handle POST update FIRST (before any HTML output)
$message = "";
$updated = isset($_GET['updated']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir))
        mkdir($uploadDir, 0777, true);

    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);

    $thumbnail = $course['thumbnail'];
    if (!empty($_FILES['thumbnail']['name'])) {
        $thumbnail = $uploadDir . time() . "_" . basename($_FILES['thumbnail']['name']);
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnail);
    }

    $banner = $course['banner'];
    if (!empty($_FILES['banner']['name'])) {
        $banner = $uploadDir . time() . "_" . basename($_FILES['banner']['name']);
        move_uploaded_file($_FILES['banner']['tmp_name'], $banner);
    }

    $update_sql = "UPDATE courses SET title='$title', description='$description', thumbnail='$thumbnail', banner='$banner' WHERE id=$course_id";

    if ($conn->query($update_sql)) {
        // Helper function
        function updatePoints($conn, $table, $course_id, $array, $col = 'point')
        {
            $conn->query("DELETE FROM $table WHERE course_id = $course_id");
            foreach ($array as $item) {
                if (!empty(trim($item))) {
                    $item = $conn->real_escape_string(trim($item));
                    $col_name = ($table === 'course_benefits') ? 'benefit' : $col;
                    $conn->query("INSERT INTO $table (course_id, $col_name) VALUES ($course_id, '$item')");
                }
            }
        }

        updatePoints($conn, 'course_learn_points', $course_id, explode(',', $_POST['learn_points']));
        updatePoints($conn, 'course_benefits', $course_id, explode(',', $_POST['benefits']));
        updatePoints($conn, 'course_target_audience', $course_id, explode(',', $_POST['target_audience']));

        // Modules
        $conn->query("DELETE FROM course_modules WHERE course_id = $course_id");
        if (isset($_POST['module_titles'])) {
            for ($i = 0; $i < count($_POST['module_titles']); $i++) {
                $title = $conn->real_escape_string($_POST['module_titles'][$i]);
                $desc = $conn->real_escape_string($_POST['module_descriptions'][$i]);
                $file = "";
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
        $conn->query("DELETE FROM course_faqs WHERE course_id = $course_id");
        if (isset($_POST['faq_questions'])) {
            for ($i = 0; $i < count($_POST['faq_questions']); $i++) {
                $q = $conn->real_escape_string($_POST['faq_questions'][$i]);
                $a = $conn->real_escape_string($_POST['faq_answers'][$i]);
                if (!empty($q) && !empty($a)) {
                    $conn->query("INSERT INTO course_faqs (course_id, question, answer) VALUES ($course_id, '$q', '$a')");
                }
            }
        }

        header("Location: view_courses.php?");
        //exit();
        // Redirect to prevent resubmission and refresh data
        // header("Location: edit_course.php?id=$course_id&updated=1");
        // exit();
    } else {
        $message = "<p style='color:red;'>Error updating course: " . $conn->error . "</p>";
    }
}

// Now fetch fresh data for display (after possible update)
$course_stmt->execute(); // re-execute to get latest
$course_result = $course_stmt->get_result();
$course = $course_result->fetch_assoc();

$learn_points = [];
$learn_res = $conn->query("SELECT point FROM course_learn_points WHERE course_id = $course_id");
while ($row = $learn_res->fetch_assoc())
    $learn_points[] = $row['point'];

$benefits = [];
$ben_res = $conn->query("SELECT benefit FROM course_benefits WHERE course_id = $course_id");
while ($row = $ben_res->fetch_assoc())
    $benefits[] = $row['benefit'];

$audience = [];
$aud_res = $conn->query("SELECT point FROM course_target_audience WHERE course_id = $course_id");
while ($row = $aud_res->fetch_assoc())
    $audience[] = $row['point'];

$modules = $conn->query("SELECT * FROM course_modules WHERE course_id = $course_id");
$faqs = $conn->query("SELECT * FROM course_faqs WHERE course_id = $course_id");

if ($updated) {
    $message = "<p style='color:green; text-align:center; padding:10px; background:#d4edda; border:1px solid #c3e6cb; border-radius:5px;'>Course updated successfully!</p>";
}
?>

<!-- <?php //include "header.php"; ?>  Now safe to include header (HTML output starts here) ?> -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
    <style>
        /* body { font-family: Arial, sans-serif; background: #f4f6f9; padding: 20px; } */
        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin: 15px 0 5px;
            font-weight: bold;
        }

        input[type=text],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type=file] {
            margin: 10px 0;
        }

        .module,
        .faq-item {
            border: 1px dashed #007cba;
            padding: 20px;
            margin: 20px 0;
            background: #f8fbff;
            border-radius: 8px;
        }

        .remove-btn {
            background: #dc3545;
            color: white;
            padding: 8px 12px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }

        .current-img {
            max-width: 200px;
            border-radius: 8px;
            margin: 10px 0;
            display: block;
        }

        button[type=submit] {
            padding: 15px 40px;
            background: #007cba;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
    <script>
        function addModule() {
            const container = document.getElementById('modules-container');
            const div = document.createElement('div');
            div.className = 'module';
            div.innerHTML = `
                <input type="text" name="module_titles[]" placeholder="Module Title" required><br><br>
                <textarea name="module_descriptions[]" rows="3" placeholder="Module Description" required></textarea><br><br>
                <input type="file" name="module_resources[]"><br><br>
                <button type="button" class="remove-btn" onclick="this.parentNode.remove()">Remove Module</button>
            `;
            container.appendChild(div);
        }

        function addFAQ() {
            const container = document.getElementById('faqs-container');
            const div = document.createElement('div');
            div.className = 'faq-item';
            div.innerHTML = `
                <input type="text" name="faq_questions[]" placeholder="Question" required><br><br>
                <textarea name="faq_answers[]" rows="3" placeholder="Answer" required></textarea><br><br>
                <button type="button" class="remove-btn" onclick="this.parentNode.remove()">Remove FAQ</button>
            `;
            container.appendChild(div);
        }
    </script>
</head>

<body>

    <div class="container">
        <h2 style="text-align:center; color:#007cba;">Edit Course: <?php echo htmlspecialchars($course['title']); ?>
        </h2>

        <?php echo $message; ?>

        <form method="post" enctype="multipart/form-data">
            <label>Course Title</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($course['title']); ?>" required>

            <label>Description</label>
            <textarea name="description" rows="5"
                required><?php echo htmlspecialchars($course['description']); ?></textarea>

            <label>Current Thumbnail</label>
            <?php if (!empty($course['thumbnail'])): ?>
                <img src="<?php echo htmlspecialchars($course['thumbnail']); ?>" class="current-img" alt="Thumbnail">
            <?php endif; ?>
            <input type="file" name="thumbnail" accept="image/*">

            <label>Current Banner</label>
            <?php if (!empty($course['banner'])): ?>
                <img src="<?php echo htmlspecialchars($course['banner']); ?>" class="current-img" alt="Banner">
            <?php endif; ?>
            <input type="file" name="banner" accept="image/*">

            <label>What You Will Learn (comma-separated)</label>
            <textarea name="learn_points"
                rows="3"><?php echo htmlspecialchars(implode(", ", $learn_points)); ?></textarea>

            <label>Benefits (comma-separated)</label>
            <textarea name="benefits" rows="3"><?php echo htmlspecialchars(implode(", ", $benefits)); ?></textarea>

            <label>Who Should Join (comma-separated)</label>
            <textarea name="target_audience"
                rows="3"><?php echo htmlspecialchars(implode(", ", $audience)); ?></textarea>

            <h3>Course Modules</h3>
            <div id="modules-container">
                <?php while ($mod = $modules->fetch_assoc()): ?>
                    <div class="module">
                        <input type="text" name="module_titles[]" value="<?php echo htmlspecialchars($mod['title']); ?>"
                            required><br><br>
                        <textarea name="module_descriptions[]" rows="3"
                            required><?php echo htmlspecialchars($mod['description']); ?></textarea><br><br>
                        <input type="file" name="module_resources[]">
                        <?php if (!empty($mod['resource_file'])): ?>
                            <p>Current file: <a href="<?php echo htmlspecialchars($mod['resource_file']); ?>"
                                    target="_blank">View/Download</a></p>
                        <?php endif; ?>
                        <button type="button" class="remove-btn" onclick="this.parentNode.remove()">Remove Module</button>
                    </div>
                <?php endwhile; ?>
            </div>
            <button type="button" onclick="addModule()">+ Add Module</button>

            <h3 style="margin-top:40px;">FAQs</h3>
            <div id="faqs-container">
                <?php while ($faq = $faqs->fetch_assoc()): ?>
                    <div class="faq-item">
                        <input type="text" name="faq_questions[]" value="<?php echo htmlspecialchars($faq['question']); ?>"
                            required><br><br>
                        <textarea name="faq_answers[]" rows="3"
                            required><?php echo htmlspecialchars($faq['answer']); ?></textarea><br><br>
                        <button type="button" class="remove-btn" onclick="this.parentNode.remove()">Remove FAQ</button>
                    </div>
                <?php endwhile; ?>
            </div>
            <button type="button" onclick="addFAQ()" style="color:#007cba;">+ Add FAQ</button><br><br>

            <div style="text-align:center; margin:40px 0;">
                <button type="submit">Update Course</button>
            </div>
        </form>

        <div style="text-align:center;">
            <a href="view_courses.php" style="color:#007cba;">← Back to Course List</a>
        </div>
    </div>
</body>

</html>

<?php
$course_stmt->close();
$conn->close();
?>