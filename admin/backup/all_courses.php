<?php
include "header.php";
include "../include/config.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle search query
$search = "";
$whereClauses = [];
$params = [];
$types = "";

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = trim($_GET['search']);
    $searchLike = "%" . $conn->real_escape_string($search) . "%";

    // Search in main course fields
    $whereClauses[] = "(c.title LIKE ? OR c.description LIKE ?)";

    // Search in learn points, benefits, target audience
    $whereClauses[] = "EXISTS (SELECT 1 FROM course_learn_points lp WHERE lp.course_id = c.id AND lp.point LIKE ?)";
    $whereClauses[] = "EXISTS (SELECT 1 FROM course_benefits b WHERE b.course_id = c.id AND b.benefit LIKE ?)";
    $whereClauses[] = "EXISTS (SELECT 1 FROM course_target_audience ta WHERE ta.course_id = c.id AND ta.point LIKE ?)";

    // Search in modules
    $whereClauses[] = "EXISTS (SELECT 1 FROM course_modules m WHERE m.course_id = c.id AND (m.title LIKE ? OR m.description LIKE ?))";

    // Search in FAQs
    $whereClauses[] = "EXISTS (SELECT 1 FROM course_faqs f WHERE f.course_id = c.id AND (f.question LIKE ? OR f.answer LIKE ?))";

    // Prepare parameters (9 times the search term)
    for ($i = 0; $i < 9; $i++) {
        $params[] = $searchLike;
        $types .= "s";
    }
}

// Build final query
$sql = "SELECT * FROM courses c";
if (!empty($whereClauses)) {
    $sql .= " WHERE " . implode(" OR ", $whereClauses);
}
$sql .= " ORDER BY c.id DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Courses</title>
    <style>
        /* body { font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 20px; } */
        .container { max-width: 1200px; margin: auto; }
        .search-bar { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); margin-bottom: 30px; text-align: center; }
        .search-bar input[type="text"] { padding: 12px 20px; width: 70%; max-width: 500px; border: 2px solid #ddd; border-radius: 30px; font-size: 16px; outline: none; }
        .search-bar input[type="text"]:focus { border-color: #ff6600; }
        .search-bar button { padding: 12px 25px; background: #ff6600; color: white; border: none; border-radius: 30px; cursor: pointer; font-size: 16px; margin-left: 10px; }
        .search-bar button:hover { background: #005a87; }
        .results-info { margin: 20px 0; font-size: 18px; color: #555; text-align: center; }
        .course-card { background: white; margin: 30px 0; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .course-thumb { max-width: 250px; border-radius: 10px; float: left; margin: 0 20px 20px 0; }
        .course-banner { width: 100%; max-height: 400px; object-fit: cover; border-radius: 10px; margin: 15px 0; }
        h3 { color: #ff6600; margin-top: 0; }
        .faq-question { background: #ff6600; color: white; padding: 15px; margin: 8px 0; border-radius: 8px; cursor: pointer; }
        .faq-answer { padding: 15px; background: #f0f8ff; border: 1px solid #ff6600; border-radius: 8px; display: none; margin-bottom: 10px; }
        .faq-answer.active { display: block; }
        ul { padding-left: 20px; }
        a { color: #ff6600; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .clear-search { color: #ff6600; font-size: 14px; margin-left: 15px; }
        @media (max-width: 768px) { 
            .search-bar input[type="text"] { width: 100%; margin-bottom: 10px; }
            .search-bar button { width: 100%; }
            .course-thumb { float: none; display: block; margin: 0 auto 15px; }
        }
    </style>
    <script>
        function toggleFAQ(el) {
            const answer = el.nextElementSibling;
            answer.classList.toggle('active');
        }
    </script>
</head>
<body>
<div class="container">
    <h1 style="text-align:center; color:#ff6600;">All Courses</h1>

    <!-- Search Bar -->
    <div class="search-bar">
        <form method="get" action="">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search courses by title, description, modules, FAQs, benefits..." autofocus>
            <button type="submit">Search</button>
            <?php if (!empty($search)): ?>
                <a href="view_courses.php" class="clear-search">Clear Search</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Results Info -->
    <?php if (!empty($search)): ?>
        <div class="results-info">
            Showing results for: <strong>"<?php echo htmlspecialchars($search); ?>"</strong> 
            (<?php echo $result->num_rows; ?> course<?php echo $result->num_rows !== 1 ? 's' : ''; ?> found)
        </div>
    <?php endif; ?>

    <?php
    if ($result->num_rows == 0) {
        if (!empty($search)) {
            echo "<p style='text-align:center; font-size:18px;'>No courses found matching your search.<br><a href='view_courses.php'>View all courses</a></p>";
        } else {
            echo "<p style='text-align:center; font-size:18px;'>No courses available yet.</p>";
        }
    } else {
        while ($course = $result->fetch_assoc()) {
            $id = $course['id'];
            echo "<div class='course-card'>";
            echo "<h3>" . htmlspecialchars($course['title']) . "</h3>";

            if (!empty($course['thumbnail'])) {
                echo "<img src='" . htmlspecialchars($course['thumbnail']) . "' alt='Thumbnail' class='course-thumb'>";
            }
            if (!empty($course['banner'])) {
                echo "<img src='" . htmlspecialchars($course['banner']) . "' alt='Banner' class='course-banner'>";
            }

            echo "<p><strong>Description:</strong><br>" . nl2br(htmlspecialchars($course['description'])) . "</p>";

            // Learn Points
            $learn = $conn->query("SELECT point FROM course_learn_points WHERE course_id = $id");
            if ($learn->num_rows > 0) {
                echo "<h4>What You Will Learn:</h4><ul>";
                while ($row = $learn->fetch_assoc()) {
                    echo "<li>" . htmlspecialchars($row['point']) . "</li>";
                }
                echo "</ul>";
            }

            // Benefits
            $benefits = $conn->query("SELECT benefit FROM course_benefits WHERE course_id = $id");
            if ($benefits->num_rows > 0) {
                echo "<h4>Benefits:</h4><ul>";
                while ($row = $benefits->fetch_assoc()) echo "<li>" . htmlspecialchars($row['benefit']) . "</li>";
                echo "</ul>";
            }

            // Target Audience
            $audience = $conn->query("SELECT point FROM course_target_audience WHERE course_id = $id");
            if ($audience->num_rows > 0) {
                echo "<h4>Who Should Join:</h4><ul>";
                while ($row = $audience->fetch_assoc()) echo "<li>" . htmlspecialchars($row['point']) . "</li>";
                echo "</ul>";
            }

            // Modules
            $modules = $conn->query("SELECT * FROM course_modules WHERE course_id = $id");
            if ($modules->num_rows > 0) {
                echo "<h4>Course Modules:</h4>";
                while ($mod = $modules->fetch_assoc()) {
                    // echo "<div style='margin:15px 0; padding-left:15px; border-left:4px solid #ff6600;'>";
                    echo "<h5 class='faq-question' onclick='toggleFAQ(this)'>" . htmlspecialchars($mod['title']) . "</h5>";
                    echo "<p class='faq-answer'>" . nl2br(htmlspecialchars($mod['description'])) . "</p>";
                    if (!empty($mod['resource_file'])) {
                        echo "<p><a href='" . htmlspecialchars($mod['resource_file']) . "' target='_blank'>Download Resource</a></p>";
                    }
                    echo "</div>";
                }
            }

            // FAQs
            $faqs = $conn->query("SELECT question, answer FROM course_faqs WHERE course_id = $id");
            if ($faqs->num_rows > 0) {
                echo "<h4>Frequently Asked Questions</h4>";
                while ($faq = $faqs->fetch_assoc()) {
                    echo "<div class='faq-question' onclick='toggleFAQ(this)'>" . htmlspecialchars($faq['question']) . "</div>";
                    echo "<div class='faq-answer'>" . nl2br(htmlspecialchars($faq['answer'])) . "</div>";
                }
            }

            echo "<hr style='margin:30px 0; border:1px dashed #eee;'>";
            echo "</div>"; // end course-card
        }
    }
    ?>
</div>
</body>
</html>

<?php 
$stmt->close();
$conn->close(); 
?>