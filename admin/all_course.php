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

    $whereClauses[] = "(c.title LIKE ? OR c.description LIKE ?)";
    $whereClauses[] = "EXISTS (SELECT 1 FROM course_learn_points lp WHERE lp.course_id = c.id AND lp.point LIKE ?)";
    $whereClauses[] = "EXISTS (SELECT 1 FROM course_benefits b WHERE b.course_id = c.id AND b.benefit LIKE ?)";
    $whereClauses[] = "EXISTS (SELECT 1 FROM course_target_audience ta WHERE ta.course_id = c.id AND ta.point LIKE ?)";
    $whereClauses[] = "EXISTS (SELECT 1 FROM course_modules m WHERE m.course_id = c.id AND (m.title LIKE ? OR m.description LIKE ?))";
    $whereClauses[] = "EXISTS (SELECT 1 FROM course_faqs f WHERE f.course_id = c.id AND (f.question LIKE ? OR f.answer LIKE ?))";

    for ($i = 0; $i < 9; $i++) {
        $params[] = $searchLike;
        $types .= "s";
    }
}

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
    <title>All Courses - NirmanSkills Academy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fc;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        h1 {
            text-align: center;
            color: #ff6600;
            font-size: 2.8rem;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            color: #666;
            font-size: 1.2rem;
            margin-bottom: 40px;
        }

        /* Search Bar */
        .search-bar {
            background: white;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 40px;
            text-align: center;
        }

        .search-bar form {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .search-bar input[type="text"] {
            padding: 15px 25px;
            width: 60%;
            max-width: 600px;
            border: 2px solid #e0e0e0;
            border-radius: 50px;
            font-size: 1.1rem;
            outline: none;
            transition: border 0.3s;
        }

        .search-bar input:focus {
            border-color: #ff6600;
        }

        .search-bar button {
            padding: 15px 30px;
            background: #ff6600;
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.3s;
        }

        .search-bar button:hover {
            background: #e55a00;
        }

        .clear-search {
            color: #ff6600;
            text-decoration: none;
            font-size: 0.95rem;
        }

        .clear-search:hover {
            text-decoration: underline;
        }

        /* Results Info */
        .results-info {
            text-align: center;
            margin: 20px 0 30px;
            font-size: 1.1rem;
            color: #555;
        }

        /* Course Card */
        .course-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 50px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .course-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .course-header {
            position: relative;
        }

        .course-banner {
            width: 100%;
            height: 350px;
            object-fit: cover;
        }

        .course-thumb {
            width: 220px;
            height: 220px;
            object-fit: cover;
            border-radius: 12px;
            border: 4px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            position: absolute;
            bottom: -80px;
            left: 40px;
        }

        .course-content {
            padding: 100px 40px 40px;
        }

        .course-title {
            font-size: 2rem;
            color: #ff6600;
            margin: 0 0 15px;
        }

        .course-desc {
            font-size: 1.1rem;
            line-height: 1.7;
            color: #444;
            margin-bottom: 25px;
        }

        /* Sections */
        .section-title {
            font-size: 1.6rem;
            color: #ff6600;
            margin: 30px 0 15px;
            border-bottom: 3px solid #ff6600;
            padding-bottom: 8px;
            display: inline-block;
        }

        ul {
            padding-left: 25px;
            line-height: 1.8;
        }

        ul li {
            margin: 8px 0;
            color: #555;
        }

        /* Accordion for Modules & FAQs */
        .accordion-item {
            margin-bottom: 10px;
        }

        .accordion-header {
            background: #ff6600;
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background 0.3s;
        }

        .accordion-header:hover {
            background: #e55a00;
        }

        .accordion-header::after {
            content: '+';
            font-size: 1.5rem;
        }

        .accordion-header.active::after {
            content: '−';
        }

        .accordion-body {
            padding: 20px;
            background: #fff9f5;
            border: 1px solid #ffddd1;
            border-radius: 0 0 10px 10px;
            display: none;
            line-height: 1.7;
        }

        .accordion-body.active {
            display: block;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .course-thumb {
                width: 180px;
                height: 180px;
                left: 50%;
                transform: translateX(-50%);
            }

            .course-content {
                padding: 100px 20px 30px;
                text-align: center;
            }
        }

        @media (max-width: 768px) {
            .search-bar form {
                flex-direction: column;
            }

            .search-bar input[type="text"] {
                width: 100%;
            }

            .course-banner {
                height: 250px;
            }

            .course-thumb {
                width: 150px;
                height: 150px;
                bottom: -60px;
            }

            .course-content {
                padding-top: 80px;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>All Courses</h1>
        <p class="subtitle">Explore our complete range of professional skill development courses</p>

        <!-- Search Bar -->
        <div class="search-bar">
            <form method="get" action="">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                    placeholder="Search by title, description, modules, benefits, FAQs..." autofocus>
                <button type="submit"><i class="fas fa-search"></i> Search</button>
                <?php if (!empty($search)): ?>
                    <a href="view_courses.php" class="clear-search">Clear Search</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Results Info -->
        <?php if (!empty($search)): ?>
            <div class="results-info">
                Found <strong><?php echo $result->num_rows; ?></strong>
                course<?php echo $result->num_rows !== 1 ? 's' : ''; ?> for
                "<strong><?php echo htmlspecialchars($search); ?></strong>"
            </div>
        <?php endif; ?>

        <?php if ($result->num_rows == 0): ?>
            <p style="text-align:center; font-size:1.3rem; color:#777; margin:60px 0;">
                <?php echo !empty($search) ? "No courses found matching your search. <a href='view_courses.php' style='color:#ff6600;'>View all courses</a>" : "No courses available yet."; ?>
            </p>
        <?php else: ?>
            <?php while ($course = $result->fetch_assoc()):
                $id = $course['id'];
                ?>
                <div class="course-card">
                    <div class="course-header">
                        <?php if (!empty($course['banner'])): ?>
                            <img src="<?php echo htmlspecialchars($course['banner']); ?>" alt="Course Banner" class="course-banner">
                        <?php endif; ?>
                        <?php if (!empty($course['thumbnail'])): ?>
                            <img src="<?php echo htmlspecialchars($course['thumbnail']); ?>" alt="Course Thumbnail"
                                class="course-thumb">
                        <?php endif; ?>
                    </div>

                    <div class="course-content">
                        <h3 class="course-title"><?php echo htmlspecialchars($course['title']); ?></h3>
                        <p class="course-desc"><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>

                        <?php
                        // Learn Points
                        $learn = $conn->query("SELECT point FROM course_learn_points WHERE course_id = $id");
                        if ($learn->num_rows > 0): ?>
                            <h4 class="section-title">What You Will Learn</h4>
                            <ul>
                                <?php while ($row = $learn->fetch_assoc()): ?>
                                    <li><?php echo htmlspecialchars($row['point']); ?></li>
                                <?php endwhile; ?>
                            </ul>
                        <?php endif; ?>

                        <?php
                        // Benefits
                        $benefits = $conn->query("SELECT benefit FROM course_benefits WHERE course_id = $id");
                        if ($benefits->num_rows > 0): ?>
                            <h4 class="section-title">Benefits</h4>
                            <ul>
                                <?php while ($row = $benefits->fetch_assoc()): ?>
                                    <li><?php echo htmlspecialchars($row['benefit']); ?></li>
                                <?php endwhile; ?>
                            </ul>
                        <?php endif; ?>

                        <?php
                        // Target Audience
                        $audience = $conn->query("SELECT point FROM course_target_audience WHERE course_id = $id");
                        if ($audience->num_rows > 0): ?>
                            <h4 class="section-title">Who Should Join</h4>
                            <ul>
                                <?php while ($row = $audience->fetch_assoc()): ?>
                                    <li><?php echo htmlspecialchars($row['point']); ?></li>
                                <?php endwhile; ?>
                            </ul>
                        <?php endif; ?>

                        <?php
                        // Modules
                        $modules = $conn->query("SELECT * FROM course_modules WHERE course_id = $id");
                        if ($modules->num_rows > 0): ?>
                            <h4 class="section-title">Course Modules</h4>
                            <?php while ($mod = $modules->fetch_assoc()): ?>
                                <div class="accordion-item">
                                    <div class="accordion-header">
                                        <?php echo htmlspecialchars($mod['title']); ?>
                                    </div>
                                    <div class="accordion-body">
                                        <?php echo nl2br(htmlspecialchars($mod['description'])); ?>
                                        <?php if (!empty($mod['resource_file'])): ?>
                                            <br><br>
                                            <a href="<?php echo htmlspecialchars($mod['resource_file']); ?>" target="_blank"
                                                style="color:#ff6600; font-weight:bold;">
                                                <i class="fas fa-download"></i> Download Resource
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php endif; ?>

                        <?php
                        // FAQs
                        $faqs = $conn->query("SELECT question, answer FROM course_faqs WHERE course_id = $id");
                        if ($faqs->num_rows > 0): ?>
                            <h4 class="section-title">Frequently Asked Questions</h4>
                            <?php while ($faq = $faqs->fetch_assoc()): ?>
                                <div class="accordion-item">
                                    <div class="accordion-header">
                                        <?php echo htmlspecialchars($faq['question']); ?>
                                    </div>
                                    <div class="accordion-body">
                                        <?php echo nl2br(htmlspecialchars($faq['answer'])); ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Select all accordion headers
            const headers = document.querySelectorAll('.accordion-header');

            headers.forEach(header => {
                header.addEventListener('click', function () {
                    // Toggle active class on header (for + / − icon)
                    this.classList.toggle('active');

                    // Toggle the next sibling (the body)
                    const body = this.nextElementSibling;
                    if (body && body.classList.contains('accordion-body')) {
                        body.classList.toggle('active');
                    }
                });
            });
        });
    </script>

    <?php
    $stmt->close();
    $conn->close();
    ?>
</body>

</html>