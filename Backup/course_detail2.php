<?php
include "header.php";
include "include/config.php";
include "api/wp_app.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$course_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($course_id <= 0) {
    die("Invalid course ID.");
}

// Fetch course
$course_res = $conn->query("SELECT * FROM courses WHERE id = $course_id");
if ($course_res->num_rows == 0) {
    die("Course not found.");
}
$course = $course_res->fetch_assoc();

// Fix banner & thumbnail path (from admin/uploads)
$banner_path = !empty($course['banner']) ? "admin/" . $course['banner'] : "assets/images/default-banner.jpg";
$thumbnail_path = !empty($course['thumbnail']) ? "admin/" . $course['thumbnail'] : "assets/images/default-course.jpg";

// Fetch related data
$learn_points = $conn->query("SELECT point FROM course_learn_points WHERE course_id = $course_id ORDER BY id");
$benefits     = $conn->query("SELECT benefit FROM course_benefits WHERE course_id = $course_id ORDER BY id");
$audience     = $conn->query("SELECT point FROM course_target_audience WHERE course_id = $course_id ORDER BY id");
$modules      = $conn->query("SELECT * FROM course_modules WHERE course_id = $course_id ORDER BY id");
$faqs         = $conn->query("SELECT * FROM course_faqs WHERE course_id = $course_id ORDER BY id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['title']); ?> - ArtLab Academy</title>
    <link rel="icon" href="assets/images/logo.png" type="image/png">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="Allcourses/assets/css/course.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <!-- ================= COURSE VIDEO SECTION ================= -->
    <main>
        <section class="course-section">
            <div class="video-container">
                <!--<video id="mainVideo" class="bg-video" src="assets/videos/study-video.mp4" muted playsinline preload="metadata"></video>-->
                 <!-- Use banner as background image (perfect design match) -->
                <div class="bg-video" style="background-image: url('<?php echo $banner_path; ?>'); "></div>
                <div class="glass-box">
                    <h2><?php echo htmlspecialchars($course['title']); ?></h2>
                    <p><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>

                    <div class="btn-group">
                        <a href="index.php#consultForm" class="btn">Get Consultancy</a>
                        <a href="#" class="btn">Enroll Now</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- POPUP VIDEO -->
        <div id="videoPopup" class="popup">
            <div class="popup-content">
                <span id="closePopup" class="close-btn">&times;</span>
                <video id="popupVideo" controls autoplay>
                    <source src="assets/videos/study-video.mp4" type="video/mp4">
                </video>
            </div>
        </div>
    </main>

    <!-- ================= COURSE INFO SECTION ================= -->
    <section class="course-info-section">
        <div class="course-mini-header" id="courseMiniHeader">
            <ul class="course-mini-menu">
                <li data-target="about" class="active">About</li>
                <li data-target="benefits">Benefits</li>
                <li data-target="modules">Modules</li>
                <li data-target="recommend">FAQ</li>
            </ul>
        </div>

        <div class="course-content-box">

            <!-- ABOUT - What You Will Learn -->
            <div class="course-content active" id="about">
                <h3 class="detail-title">What You Will Learn</h3>
                <p class="detail-para"><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>

                <div class="detail-grid">
                    <?php while ($row = $learn_points->fetch_assoc()): ?>
                        <div class="detail-card">
                            <div class="detail-icon"><i class="fa-solid fa-check"></i></div>
                            <div class="detail-info">
                                <h4><?php echo htmlspecialchars($row['point']); ?></h4>
                                <p></p> <!-- Empty paragraph to keep layout same -->
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- BENEFITS -->
            <div class="course-content" id="benefits">
                <h3 class="detail-title">Benefits You Will Get</h3>
                <p class="detail-para">Joining this course gives you long-term career advantages.</p>

                <div class="detail-grid">
                    <?php while ($row = $benefits->fetch_assoc()): ?>
                        <div class="detail-card">
                            <div class="detail-icon"><i class="fa-solid fa-award"></i></div>
                            <div class="detail-info">
                                <h4><?php echo htmlspecialchars($row['benefit']); ?></h4>
                                <p></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- MODULES (Accordion) -->
            <div class="course-content" id="modules">
                <div class="accordion accordion-flush" id="accordionFlushExample">
                    <?php while ($mod = $modules->fetch_assoc()): ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" onclick="toggleAccordion(this)">
                                    <h3><?php echo htmlspecialchars($mod['title']); ?></h3>
                                    <span class="hover-detail">Detail View</span>
                                </button>
                            </h2>
                            <div class="accordion-body">
                                <p><?php echo nl2br(htmlspecialchars($mod['description'])); ?></p>
                                <br>
                                <h2>What's included</h2>
                                <br><br>
                                <div class="link-section">
                                    <?php if (!empty($mod['resource_file'])): ?>
                                        <a href="<?php echo htmlspecialchars($mod['resource_file']); ?>" target="_blank">
                                            Download Resource <i class="fas fa-download"></i>
                                        </a>
                                    <?php else: ?>
                                        <span>No resource attached</span>
                                    <?php endif; ?>
                                </div>
                                <br>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- WHO SHOULD JOIN (recommend tab) -->
            <div class="course-content" id="recommend">
                <h3 class="detail-title">Who Should Join?</h3>
                <p class="detail-para">This course is perfect for a wide range of learners.</p>

                <div class="detail-grid">
                    <?php while ($row = $audience->fetch_assoc()): ?>
                        <div class="detail-card">
                            <div class="detail-icon"><i class="fa-solid fa-user"></i></div>
                            <div class="detail-info">
                                <h4><?php echo htmlspecialchars($row['point']); ?></h4>
                                <p></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

        </div>
    </section>

    <!-- ================= FAQ SECTION ================= -->
    <section class="faq-section">
        <div class="faq-container">
            <h2 class="faq-title">Frequently Asked <span>Questions</span> :</h2>

            <?php while ($faq = $faqs->fetch_assoc()): ?>
                <div class="faq-item">
                    <button class="faq-question">
                        <?php echo htmlspecialchars($faq['question']); ?>
                        <span class="arrow">+</span>
                    </button>
                    <div class="faq-answer">
                        <p><?php echo nl2br(htmlspecialchars($faq['answer'])); ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <?php include "footer.php"; ?>

    <!-- Keep all your original scripts unchanged -->
    <script src="assets/js/script.js"></script>
    <script>
        // FAQ Toggle
        document.querySelectorAll(".faq-item").forEach(item => {
            item.querySelector(".faq-question").addEventListener("click", () => {
                item.classList.toggle("active");
            });
        });

        // Video popup logic (exactly same as yours)
        let mainVid = document.getElementById("mainVideo");
        mainVid.addEventListener("loadedmetadata", () => {
            mainVid.currentTime = 0.2;
            mainVid.pause();
        });
        mainVid.addEventListener("click", () => {
            document.getElementById("videoPopup").style.display = "flex";
            document.getElementById("popupVideo").play();
        });
        document.getElementById("closePopup").addEventListener("click", () => {
            document.getElementById("videoPopup").style.display = "none";
            document.getElementById("popupVideo").pause();
        });

        // Accordion for modules
        function toggleAccordion(btn) {
            const item = btn.parentElement.parentElement;
            const allItems = document.querySelectorAll('.accordion-item');
            allItems.forEach(i => { if (i !== item) i.classList.remove('activee'); });
            item.classList.toggle('activee');
        }

        // Mini menu scroll spy (desktop only) - exactly your original code
        if (window.innerWidth > 768) {
            const menuItems = document.querySelectorAll(".course-mini-menu li");
            const sections = document.querySelectorAll(".course-content");

            menuItems.forEach(item => {
                item.addEventListener("click", () => {
                    menuItems.forEach(li => li.classList.remove("active"));
                    item.classList.add("active");
                    const target = document.getElementById(item.dataset.target);
                    window.scrollTo({ top: target.offsetTop - 120, behavior: "smooth" });
                });
            });

            window.addEventListener("scroll", () => {
                let scrollPos = window.scrollY + 180;
                sections.forEach(section => {
                    if (scrollPos >= section.offsetTop && scrollPos < section.offsetTop + section.offsetHeight) {
                        let id = section.getAttribute("id");
                        menuItems.forEach(li => li.classList.remove("active"));
                        document.querySelector(`.course-mini-menu li[data-target="${id}"]`).classList.add("active");
                    }
                });

                const mainHeader = document.querySelector("header");
                const miniHeader = document.getElementById("courseMiniHeader");
                if (window.scrollY > 200) {
                    mainHeader.style.transform = "translateY(-100%)";
                    miniHeader.classList.add("sticky");
                } else {
                    mainHeader.style.transform = "translateY(0)";
                    miniHeader.classList.remove("sticky");
                }
            });
        }
    </script>
</body>
</html>

<?php $conn->close(); ?>