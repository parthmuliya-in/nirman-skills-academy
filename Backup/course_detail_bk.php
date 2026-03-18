<?php
session_start();
include "header.php";
include "include/config.php";
include "api/wp_app.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$course_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($course_id <= 0) {
    die("Invalid course ID.");
}

// Check login
if (!isset($_SESSION['user_id'])) { // Change 'user_id' to your session variable name
    $login_url = "login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']);
}

// Fetch course (same as before)
$course_res = $conn->query("SELECT * FROM courses WHERE id = $course_id");
if ($course_res->num_rows == 0) {
    die("Course not found.");
}
$course = $course_res->fetch_assoc();

$banner_path = !empty($course['banner']) ? "admin/" . htmlspecialchars($course['banner']) : "assets/images/default-banner.jpg";

// Fetch related data (same)
$learn_points = $conn->query("SELECT point FROM course_learn_points WHERE course_id = $course_id");
$benefits = $conn->query("SELECT benefit FROM course_benefits WHERE course_id = $course_id");
$audience = $conn->query("SELECT point FROM course_target_audience WHERE course_id = $course_id");
$modules = $conn->query("SELECT * FROM course_modules WHERE course_id = $course_id ORDER BY id");
$faqs = $conn->query("SELECT * FROM course_faqs WHERE course_id = $course_id ORDER BY id");

// Handle enrollment
$enroll_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['enroll_submit'])) {
    if (!isset($_SESSION['user_id'])) {
        $enroll_message = "<p style='color:red;'>You must be logged in to enroll!</p>";
    } else {
        $user_id = (int) $_SESSION['user_id'];

        // Check if already enrolled
        $check = $conn->query("SELECT id FROM enrollments WHERE user_id = $user_id AND course_id = $course_id");
        if ($check->num_rows > 0) {
            $enroll_message = "<p style='color:orange;'>You are already enrolled in this course!</p>";
        } else {
            $name = $conn->real_escape_string($_POST['student_name']);
            $email = $conn->real_escape_string($_POST['student_email']);
            $phone = $conn->real_escape_string($_POST['student_phone']);
            $message = $conn->real_escape_string($_POST['message']);

            $sql = "INSERT INTO enrollments 
                    (user_id, course_id, course_title, student_name, student_email, student_phone, message)
                    VALUES ($user_id, $course_id, '" . $conn->real_escape_string($course['title']) . "', '$name', '$email', '$phone', '$message')";

            if ($conn->query($sql)) {
                $enroll_message = "<p style='color:green; padding:15px; background:#d4edda; border-radius:8px; text-align:center;'>
                    Success! You are now enrolled in <strong>" . htmlspecialchars($course['title']) . "</strong>.<br>
                    We will contact you soon!</p>";
            } else {
                $enroll_message = "<p style='color:red;'>Error: " . $conn->error . "</p>";
            }
        }
    }
}
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

    <style>
        /* Popup Form Styling */
        .enroll-popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .enroll-form-box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            position: relative;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .close-enroll {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 28px;
            cursor: pointer;
            color: #aaa;
        }

        .close-enroll:hover {
            color: #000;
        }

        .enroll-form-box h3 {
            text-align: center;
            color: #007cba;
            margin-bottom: 20px;
        }

        .enroll-form-box input,
        .enroll-form-box textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .enroll-form-box button {
            width: 100%;
            padding: 15px;
            background: #ff6600;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
        }
    </style>
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

                    <!-- ENROLL BUTTON -->
                    <div class="btn-group">
                        <a href="index.php#consultForm" class="btn">Get Consultancy</a>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <?php
                            $already_enrolled = false;
                            if (isset($_SESSION['user_id'])) {
                                $uid = (int) $_SESSION['user_id'];
                                $check = $conn->query("SELECT id FROM enrollments WHERE user_id = $uid AND course_id = $course_id");
                                $already_enrolled = $check->num_rows > 0;
                            }
                            ?>
                            <?php if ($already_enrolled): ?>
                                <a href="#" class="btn" style="background:gray;">Already Enrolled</a>
                            <?php else: ?>
                                <a href="#" class="btn" id="enrollBtn">Enroll Now</a>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="login.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="btn">Login
                                to Enroll</a>
                        <?php endif; ?>
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
                            <!-- <div class="detail-icon"><i class="fa-solid fa-check"></i></div> -->
                            <div class="detail-info">
                                <h4><?php echo "<i class='fa-solid fa-check'></i> " . htmlspecialchars($row['point']); ?>
                                </h4>
                                <!-- <p></p>  Empty paragraph to keep layout same -->
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
                            <!--<div class="detail-icon"><i class="fa-solid fa-award"></i></div>-->
                            <div class="detail-info">
                                <h4><?php echo "<i class='fa-solid fa-award'></i> " . htmlspecialchars($row['benefit']); ?>
                                </h4>
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
                            <!-- <div class="detail-icon"><i class="fa-solid fa-user"></i></div> -->
                            <div class="detail-info">
                                <h4><?php echo "<i class='fa-solid fa-user'></i> " . htmlspecialchars($row['point']); ?>
                                </h4>
                                <!-- <p></p> -->
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

    <!-- ENROLLMENT POPUP -->
    <div id="enrollPopup" class="enroll-popup">
        <div class="enroll-form-box">
            <span class="close-enroll" id="closeEnroll">&times;</span>
            <h3>Enroll in "<?php echo htmlspecialchars($course['title']); ?>"</h3>
            <?php echo $enroll_message; ?>
            <form method="post">
                <input type="hidden" name="enroll_submit" value="1">
                <input type="text" name="student_name" placeholder="Full Name" required>
                <input type="email" name="student_email" placeholder="Email" required>
                <input type="text" name="student_phone" placeholder="Phone Number" required>
                <textarea name="message" rows="4" placeholder="Message (Optional)"></textarea>
                <button type="submit">Enroll Now</button>
            </form>
        </div>
    </div>
    <?php include "footer.php"; ?>
    <!-- Your existing scripts + new popup script -->
    <script>
        const enrollBtn = document.getElementById("enrollBtn");
        const enrollPopup = document.getElementById("enrollPopup");
        const closeEnroll = document.getElementById("closeEnroll");

        if (enrollBtn) {
            enrollBtn.addEventListener("click", (e) => {
                e.preventDefault();
                enrollPopup.style.display = "flex";
            });
        }

        closeEnroll.addEventListener("click", () => {
            enrollPopup.style.display = "none";
        });

        enrollPopup.addEventListener("click", (e) => {
            if (e.target === enrollPopup) enrollPopup.style.display = "none";
        });

    </script>

    <!-- Keep all your original scripts unchanged -->
    <script src="assets/js/script.js"></script>
    <script>
        // FAQ Toggle
        const faqItems = document.querySelectorAll(".faq-item");

        faqItems.forEach(item => {
            item.querySelector(".faq-question").addEventListener("click", () => {
                item.classList.toggle("active");
            });
        });


        /* ========================= ENROLL POPUP ========================= */
        document.addEventListener("DOMContentLoaded", function () {
            const enrollBtn = document.getElementById("enrollBtn");
            const enrollPopup = document.getElementById("enrollPopup");
            const closeEnroll = document.getElementById("closeEnroll");
            if (enrollBtn && enrollPopup) {
                enrollBtn.addEventListener("click", function (e) {
                    e.preventDefault();
                    enrollPopup.style.display = "flex";
                });
            }
            if (closeEnroll && enrollPopup) {
                closeEnroll.addEventListener("click", function () {
                    enrollPopup.style.display = "none";
                });
            }
            if (enrollPopup) {
                enrollPopup.addEventListener("click", function (e) {
                    if (e.target === enrollPopup) {
                        enrollPopup.style.display = "none";
                    }
                });
            }
            /* ========================= FAQ TOGGLE ========================= */
            document.querySelectorAll(".faq-item").forEach(function (item) {
                const question = item.querySelector(".faq-question");
                if (question) {
                    question.addEventListener("click", function () {
                        item.classList.toggle("active");
                    });
                }
            });
                  /* ========================= MODULE ACCORDION ========================= */ window.toggleAccordion = function (btn) {
                if (!btn) return;
                const item = btn.closest(".accordion-item");
                if (!item) return;
                document.querySelectorAll(".accordion-item").forEach(function (i) {
                    if (i !== item) i.classList.remove("activee");
                });
                item.classList.toggle("activee");
            };
            /* ========================= MINI MENU + SCROLL SPY ========================= */
            const menuItems = document.querySelectorAll(".course-mini-menu li");
            const sections = document.querySelectorAll(".course-content");
            const mainHeader = document.querySelector("header");
            const miniHeader = document.getElementById("courseMiniHeader");
            if (menuItems.length && sections.length) {
                // Click scroll
                menuItems.forEach(function (item) {
                    item.addEventListener("click", function () {
                        menuItems.forEach(li => li.classList.remove("active"));
                        item.classList.add("active");
                        const targetId = item.getAttribute("data-target");
                        const targetSection = document.getElementById(targetId);
                        if (targetSection) {
                            window.scrollTo({ top: targetSection.offsetTop - 120, behavior: "smooth" });
                        }
                    });
                });
                // Scroll spy 
                window.addEventListener("scroll", function () {
                    const scrollPos = window.scrollY + 180;
                    sections.forEach(function (section) {
                        if (scrollPos >= section.offsetTop && scrollPos < section.offsetTop + section.offsetHeight) {
                            const id = section.getAttribute("id");
                            menuItems.forEach(li => li.classList.remove("active"));
                            const activeItem = document.querySelector(`.course-mini-menu li[data-target="${id}"]`);
                            if (activeItem) activeItem.classList.add("active");
                        }
                    });
                    // Sticky mini header
                    if (mainHeader && miniHeader) {
                        if (window.scrollY > 200) {
                            mainHeader.style.transform = "translateY(-100%)";
                            miniHeader.classList.add("sticky");
                        } else {
                            mainHeader.style.transform = "translateY(0)"; miniHeader.classList.remove("sticky");
                        }
                    }
                });
            } /* ========================= VIDEO POPUP (SAFE) ========================= */ const mainVideo = document.getElementById("mainVideo"); const videoPopup = document.getElementById("videoPopup"); const popupVideo = document.getElementById("popupVideo"); const closePopup = document.getElementById("closePopup"); if (mainVideo && videoPopup && popupVideo && closePopup) { mainVideo.addEventListener("click", function () { videoPopup.style.display = "flex"; popupVideo.play(); }); closePopup.addEventListener("click", function () { videoPopup.style.display = "none"; popupVideo.pause(); }); videoPopup.addEventListener("click", function (e) { if (e.target === videoPopup) { videoPopup.style.display = "none"; popupVideo.pause(); } }); }
        });
    </script>
</body>

</html>

<?php $conn->close(); ?>