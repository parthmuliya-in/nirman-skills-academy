<?php
include "header.php";
include "include/config.php"; // Your DB connection file
include "api/wp_app.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NirmanSkills Academy</title>
    <link rel="icon" href="assets/images/logo.png" type="image/png">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/courses.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <!-- **************************** HEADER ************************************* -->
    <!-- (Your header content here) -->

    <br><br>

    <!-- ****************EXPLORE OUR BEST COURSES*********************** -->
    <div class="btns-filter">
        <a class="btn-box active" data-target="allCourses">ALL COURSE</a>
        <a class="btn-box" data-target="graphicCourses">GRAPHIC</a>
        <a class="btn-box" data-target="uiuxCourses">Animation</a>
        <a class="btn-box" data-target="webCourses">WEB DEV</a>
    </div>

    <!-- ALL COURSE (Full width row) -->
    <div id="allCourses" class="course-section show">
        <div class="cards-row">
            <?php
            // Fetch all courses from database
            $result = $conn->query("SELECT * FROM courses ORDER BY id DESC");
            if ($result->num_rows > 0) {
                while ($course = $result->fetch_assoc()) {
                    $id = $course['id'];
                    $thumbnail = !empty($course['thumbnail']) ? $course['thumbnail'] : 'https://cdn.pixabay.com/photo/2020/04/08/15/49/coloring-5017860_960_720.jpg';

                    // Get short description (first 100 chars)
                    $short_desc = htmlspecialchars(substr($course['description'], 0, 100));
                    if (strlen($course['description']) > 100) $short_desc .= '...';

                    // Get related data (e.g., first learn point as teaser)
                    $learn_res = $conn->query("SELECT point FROM course_learn_points WHERE course_id = $id LIMIT 1");
                    $learn_teaser = $learn_res->num_rows > 0 ? htmlspecialchars($learn_res->fetch_assoc()['point']) : 'Key skills included...';

                    echo '
                    <div class="card">
                        <img src="admin/' . htmlspecialchars($thumbnail) . '" alt="' . htmlspecialchars($course['title']) . '">
                        <div class="course-text">
                            <h3>' . htmlspecialchars($course['title']) . '</h3><br>
                            <p>' . $short_desc . '</p>
                            <a class="getBtn" href="course_detail.php?id=' . $id . '">GET more →</a>
                        </div>
                    </div>';
                }
            } else {
                echo '<p style="text-align:center; font-size:18px;">No courses available yet.</p>';
            }
            ?>
        </div>
    </div>

    <!-- GRAPHIC -->
    <div id="graphicCourses" class="course-section">
        <div class="cards-row two">
            <?php
            // Example filter by category (adjust based on your data, e.g., add category column or keyword in title)
            $graphic_result = $conn->query("SELECT * FROM courses WHERE title LIKE '%Graphic%' ORDER BY id DESC");
            if ($graphic_result->num_rows > 0) {
                while ($course = $graphic_result->fetch_assoc()) {
                    $id = $course['id'];
                    $thumbnail = !empty($course['thumbnail']) ? $course['thumbnail'] : 'https://cdn.pixabay.com/photo/2020/04/08/15/49/coloring-5017860_960_720.jpg';
                    $short_desc = htmlspecialchars(substr($course['description'], 0, 100)) . '...';
                    echo '
                    <div class="card">
                        <img src="admin/' . htmlspecialchars($thumbnail) . '" alt="' . htmlspecialchars($course['title']) . '">
                        <div class="course-text">
                            <h3>' . htmlspecialchars($course['title']) . '</h3>
                            <p>' . $short_desc . '</p>
                            <a class="getBtn" href="course_detail.php?id=' . $id . '">GET more →</a>
                        </div>
                    </div>';
                }
            } else {
                echo '<p style="text-align:center;">No Graphic courses available.</p>';
            }
            ?>
        </div>
    </div>

    <!-- Animation (UI/UX) -->
    <div id="uiuxCourses" class="course-section">
        <div class="cards-row two">
            <?php
            // Filter by title keyword (e.g., Animation)
            $animation_result = $conn->query("SELECT * FROM courses WHERE title LIKE '%Animation%' ORDER BY id DESC");
            if ($animation_result->num_rows > 0) {
                while ($course = $animation_result->fetch_assoc()) {
                    $id = $course['id'];
                    $thumbnail = !empty($course['thumbnail']) ? $course['thumbnail'] : 'https://cdn.pixabay.com/photo/2020/04/08/15/49/coloring-5017860_960_720.jpg';
                    $short_desc = htmlspecialchars(substr($course['description'], 0, 100)) . '...';
                    echo '
                    <div class="card">
                        <img src="admin/' . htmlspecialchars($thumbnail) . '" alt="' . htmlspecialchars($course['title']) . '">
                        <div class="course-text">
                            <h3>' . htmlspecialchars($course['title']) . '</h3>
                            <p>' . $short_desc . '</p>
                            <a class="getBtn" href="course_detail.php?id=' . $id . '">GET more →</a>
                        </div>
                    </div>';
                }
            } else {
                echo '<p style="text-align:center;">No Animation courses available.</p>';
            }
            ?>
        </div>
    </div>

    <!-- WEB DEV -->
    <div id="webCourses" class="course-section">
        <div class="cards-row two">
            <?php
            // Filter by title keyword (e.g., PHP, Web)
            $web_result = $conn->query("SELECT * FROM courses WHERE title LIKE '%PHP%' OR title LIKE '%Web%' ORDER BY id DESC");
            if ($web_result->num_rows > 0) {
                while ($course = $web_result->fetch_assoc()) {
                    $id = $course['id'];
                    $thumbnail = !empty($course['thumbnail']) ? $course['thumbnail'] : 'https://cdn.pixabay.com/photo/2020/04/08/15/49/coloring-5017860_960_720.jpg';
                    $short_desc = htmlspecialchars(substr($course['description'], 0, 100)) . '...';
                    echo '
                    <div class="card">
                        <img src="admin/' . htmlspecialchars($thumbnail) . '" alt="' . htmlspecialchars($course['title']) . '">
                        <div class="course-text">
                            <h3>' . htmlspecialchars($course['title']) . '</h3>
                            <p>' . $short_desc . '</p>
                            <a class="getBtn" href="course_detail.php?id=' . $id . '">GET more →</a>
                        </div>
                    </div>';
                }
            } else {
                echo '<p style="text-align:center;">No Web Development courses available.</p>';
            }
            ?>
        </div>
    </div>

    <br><br>

    <!-- ****************EXPLORE OUR BEST COURSES end*********************** -->

    <?php include "footer.php"; ?>

    <script src="assets/js/script.js"></script>
    <script src="assets/js/courses.js"></script>

    <script>
        // Your existing script for text truncation
        document.querySelectorAll('.course-text p').forEach(p => {
            const fullText = p.innerText.trim();
            const words = fullText.split(/\s+/);
            if (words.length > 7) {
                p.setAttribute("data-fulltext", fullText);
                p.innerText = words.slice(0, 7).join(" ") + "...";
            }
        });

        // Add Search Icon Toggle + Mobile Dropdown Click
        const searchIconMobile = document.querySelector(".search-icon-mobile");
        const searchBox = document.querySelector(".search-box");
        // searchIconMobile.addEventListener("click", () => {
        //     searchBox.classList.toggle("active");
        // });

        document.querySelectorAll(".dropdown > a").forEach(drop => {
            drop.addEventListener("click", (e) => {
                if (window.innerWidth <= 850) {
                    e.preventDefault();
                    drop.parentElement.classList.toggle("open");
                }
            });
        });
    </script>
</body>

</html>

<?php $conn->close(); ?>