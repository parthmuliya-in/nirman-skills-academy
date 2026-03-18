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

// Fetch course
$course_res = $conn->query("SELECT * FROM courses WHERE id = $course_id");
if ($course_res->num_rows == 0) {
    die("Course not found.");
}
$course = $course_res->fetch_assoc();

$banner_path = !empty($course['banner']) ? "admin/" . htmlspecialchars($course['banner']) : "assets/images/default-banner.jpg";

// Fetch related data
$learn_points = $conn->query("SELECT point FROM course_learn_points WHERE course_id = $course_id");
$benefits = $conn->query("SELECT benefit FROM course_benefits WHERE course_id = $course_id");
$audience = $conn->query("SELECT point FROM course_target_audience WHERE course_id = $course_id");
$modules = $conn->query("SELECT * FROM course_modules WHERE course_id = $course_id ORDER BY id");
$faqs = $conn->query("SELECT * FROM course_faqs WHERE course_id = $course_id ORDER BY id");

// Handle enrollment
$enroll_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['enroll_submit'])) {
    if (!isset($_SESSION['user_id'])) {
        $enroll_message = "<p style='color:red; text-align:center;'>You must be logged in to enroll!</p>";
    } else {
        $user_id = (int) $_SESSION['user_id'];

        // Check duplicate
        $check = $conn->query("SELECT id FROM enrollments WHERE user_id = $user_id AND course_id = $course_id");
        if ($check->num_rows > 0) {
            $enroll_message = "<p style='color:orange; text-align:center;'>You are already enrolled!</p>";
        } else {
            $name = $conn->real_escape_string($_POST['student_name']);
            $email = $conn->real_escape_string($_POST['student_email']);
            $phone = $conn->real_escape_string($_POST['student_phone']);
            $message = $conn->real_escape_string($_POST['message']);

            $sql = "INSERT INTO enrollments 
                    (user_id, course_id, course_title, student_name, student_email, student_phone, message)
                    VALUES ($user_id, $course_id, '" . $conn->real_escape_string($course['title']) . "', '$name', '$email', '$phone', '$message')";

            if ($conn->query($sql)) {
                $enroll_message = "<p style='color:green; background:#d4edda; padding:15px; border-radius:8px; text-align:center;'>
                    Success! You are enrolled in <strong>" . htmlspecialchars($course['title']) . "</strong>!</p>";
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
        /* Enrollment Popup */
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

    <!-- Course Video/Banner Section -->
    <main>
        <section class="course-section">
            <div class="video-container">
                <div class="bg-video" style="background-image: url('<?php echo $banner_path; ?>');"></div>
                <div class="glass-box">
                    <h2><?php echo htmlspecialchars($course['title']); ?></h2>
                    <p><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>

                    <div class="btn-group">
                        <a href="index.php#consultForm" class="btn">Get Consultancy</a>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <?php
                            $already_enrolled = false;
                            $uid = (int) $_SESSION['user_id'];
                            $check = $conn->query("SELECT id FROM enrollments WHERE user_id = $uid AND course_id = $course_id");
                            $already_enrolled = $check->num_rows > 0;
                            ?>
                            <?php if ($already_enrolled): ?>
                                <a href="#" class="btn" style="background:gray; cursor:not-allowed;">Already Enrolled</a>
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

        <!-- Video Popup -->
        <div id="videoPopup" class="popup">
            <div class="popup-content">
                <span id="closePopup" class="close-btn">&times;</span>
                <video id="popupVideo" controls autoplay>
                    <source src="assets/videos/study-video.mp4" type="video/mp4">
                </video>
            </div>
        </div>
    </main>

    <!-- Course Info Section -->
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
            <!-- About -->
            <div class="course-content active" id="about">
                <h3 class="detail-title">What You Will Learn</h3>
                <p class="detail-para"><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
                <div class="detail-grid">
                    <?php while ($row = $learn_points->fetch_assoc()): ?>
                        <div class="detail-card">
                            <div class="detail-info">
                                <h4><i class="fa-solid fa-check"></i> <?php echo htmlspecialchars($row['point']); ?></h4>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Benefits -->
            <div class="course-content" id="benefits">
                <h3 class="detail-title">Benefits You Will Get</h3>
                <div class="detail-grid">
                    <?php while ($row = $benefits->fetch_assoc()): ?>
                        <div class="detail-card">
                            <div class="detail-info">
                                <h4><i class="fa-solid fa-award"></i> <?php echo htmlspecialchars($row['benefit']); ?></h4>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Modules -->
            <div class="course-content" id="modules">
                <div class="accordion accordion-flush">
                    <?php while ($mod = $modules->fetch_assoc()): ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" onclick="toggleAccordion(this)">
                                    <h3><?php echo htmlspecialchars($mod['title']); ?></h3>
                                    <span class="hover-detail">Detail View</span>
                                </button>
                            </h2>
                            <div class="accordion-body">
                                <p><?php echo nl2br(htmlspecialchars($mod['description'])); ?></p>
                                <br>
                                <!-- <h2>What's included</h2> -->
                                <br>
                                <div class="link-section">
                                    <?php if (!empty($mod['resource_file'])): ?>
                                        <a href="<?php echo htmlspecialchars($mod['resource_file']); ?>" target="_blank">
                                            Download Resource <i class="fas fa-download"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Who Should Join -->
            <div class="course-content" id="recommend">
                <h3 class="detail-title">Who Should Join?</h3>
                <div class="detail-grid">
                    <?php while ($row = $audience->fetch_assoc()): ?>
                        <div class="detail-card">
                            <div class="detail-info">
                                <h4><i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($row['point']); ?></h4>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="faq-container">
            <h2 class="faq-title">Frequently Asked <span>Questions</span>:</h2>
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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // === ENROLLMENT POPUP ===
            const enrollBtn = document.getElementById("enrollBtn");
            const enrollPopup = document.getElementById("enrollPopup");
            const closeEnroll = document.getElementById("closeEnroll");

            // Only attach listener if button exists (i.e., user is logged in and not enrolled)
            if (enrollBtn) {
                enrollBtn.addEventListener("click", function (e) {
                    e.preventDefault();
                    if (enrollPopup) {
                        enrollPopup.style.display = "flex";
                    }
                });
            }

            // Close buttons
            if (closeEnroll) {
                closeEnroll.addEventListener("click", function () {
                    enrollPopup.style.display = "none";
                });
            }

            // Click outside to close
            if (enrollPopup) {
                enrollPopup.addEventListener("click", function (e) {
                    if (e.target === enrollPopup) {
                        enrollPopup.style.display = "none";
                    }
                });
            }

            // === FAQ TOGGLE ===
            document.querySelectorAll(".faq-item").forEach(function (item) {
                const btn = item.querySelector(".faq-question");
                if (btn) {
                    btn.addEventListener("click", function () {
                        item.classList.toggle("active");
                    });
                }
            });

            // === MODULE ACCORDION ===
            window.toggleAccordion = function (btn) {
                const item = btn.closest(".accordion-item");
                if (!item) return;
                document.querySelectorAll(".accordion-item").forEach(function (i) {
                    if (i !== item) i.classList.remove("activee");
                });
                item.classList.toggle("activee");
            };

            // === MINI MENU SCROLL SPY ===
            const menuItems = document.querySelectorAll(".course-mini-menu li");
            const sections = document.querySelectorAll(".course-content");
            const mainHeader = document.querySelector("header");
            const miniHeader = document.getElementById("courseMiniHeader");

            if (menuItems.length > 0) {
                menuItems.forEach(function (item) {
                    item.addEventListener("click", function () {
                        menuItems.forEach(li => li.classList.remove("active"));
                        item.classList.add("active");
                        const target = document.getElementById(item.dataset.target);
                        if (target) {
                            window.scrollTo({ top: target.offsetTop - 120, behavior: "smooth" });
                        }
                    });
                });

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

                    if (mainHeader && miniHeader) {
                        if (window.scrollY > 200) {
                            mainHeader.style.transform = "translateY(-100%)";
                            miniHeader.classList.add("sticky");
                        } else {
                            mainHeader.style.transform = "translateY(0)";
                            miniHeader.classList.remove("sticky");
                        }
                    }
                });
            }

            // === VIDEO POPUP ===
            const mainVideo = document.querySelector(".bg-video");
            const videoPopup = document.getElementById("videoPopup");
            const popupVideo = document.getElementById("popupVideo");
            const closePopup = document.getElementById("closePopup");

            if (mainVideo && videoPopup) {
                mainVideo.addEventListener("click", function () {
                    videoPopup.style.display = "flex";
                    if (popupVideo) popupVideo.play();
                });
            }

            if (closePopup) {
                closePopup.addEventListener("click", function () {
                    videoPopup.style.display = "none";
                    if (popupVideo) popupVideo.pause();
                });
            }

            if (videoPopup) {
                videoPopup.addEventListener("click", function (e) {
                    if (e.target === videoPopup) {
                        videoPopup.style.display = "none";
                        if (popupVideo) popupVideo.pause();
                    }
                });
            }
        });
    </script>
    <script src="assets/js/script.js"></script>

    <!--<script>
        // All scripts in one block
        document.addEventListener("DOMContentLoaded", function () {
            // Enrollment Popup
            const enrollBtn = document.getElementById("enrollBtn");
            const enrollPopup = document.getElementById("enrollPopup");
            const closeEnroll = document.getElementById("closeEnroll");

            if (enrollBtn && enrollPopup) {
                enrollBtn.addEventListener("click", function (e) {
                    e.preventDefault();
                    enrollPopup.style.display = "flex";
                });
            }

            if (closeEnroll) {
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

            // FAQ Toggle
            document.querySelectorAll(".faq-item").forEach(function (item) {
                const btn = item.querySelector(".faq-question");
                if (btn) {
                    btn.addEventListener("click", function () {
                        item.classList.toggle("active");
                    });
                }
            });

            // Module Accordion
            window.toggleAccordion = function (btn) {
                const item = btn.closest(".accordion-item");
                document.querySelectorAll(".accordion-item").forEach(function (i) {
                    if (i !== item) i.classList.remove("activee");
                });
                item.classList.toggle("activee");
            };

            // Mini Menu + Scroll Spy
            const menuItems = document.querySelectorAll(".course-mini-menu li");
            const sections = document.querySelectorAll(".course-content");
            const mainHeader = document.querySelector("header");
            const miniHeader = document.getElementById("courseMiniHeader");

            if (menuItems.length > 0) {
                menuItems.forEach(function (item) {
                    item.addEventListener("click", function () {
                        menuItems.forEach(li => li.classList.remove("active"));
                        item.classList.add("active");
                        const target = document.getElementById(item.dataset.target);
                        if (target) {
                            window.scrollTo({ top: target.offsetTop - 120, behavior: "smooth" });
                        }
                    });
                });

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

                    if (mainHeader && miniHeader) {
                        if (window.scrollY > 200) {
                            mainHeader.style.transform = "translateY(-100%)";
                            miniHeader.classList.add("sticky");
                        } else {
                            mainHeader.style.transform = "translateY(0)";
                            miniHeader.classList.remove("sticky");
                        }
                    }
                });
            }

            // Video Popup
            const mainVideo = document.querySelector(".bg-video");
            const videoPopup = document.getElementById("videoPopup");
            const popupVideo = document.getElementById("popupVideo");
            const closePopup = document.getElementById("closePopup");

            if (mainVideo && videoPopup) {
                mainVideo.addEventListener("click", function () {
                    videoPopup.style.display = "flex";
                    if (popupVideo) popupVideo.play();
                });
            }

            if (closePopup) {
                closePopup.addEventListener("click", function () {
                    videoPopup.style.display = "none";
                    if (popupVideo) popupVideo.pause();
                });
            }

            if (videoPopup) {
                videoPopup.addEventListener("click", function (e) {
                    if (e.target === videoPopup) {
                        videoPopup.style.display = "none";
                        if (popupVideo) popupVideo.pause();
                    }
                });
            }
        });
    </script>-->
</body>

</html>

<?php $conn->close(); ?>