<?php
include "../api/wp_app.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NirmanSkills Academy</title>
    <link rel="icon" href="assets/images/logo.png" type="image/png">

    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/course.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>
    <!-- **************************** HEADER ************************************* -->
    <header class="header">
        <div class="logo">
            <img src="../assets/images/logo.png" alt="Logo-image">
        </div>


        <!-- SEARCH BAR -->
        <div class="search-box">
            <input type="text" placeholder="Search...">
            <button class="search-btn"><i class="fa-solid fa-search"></i></button>
        </div>



        <div class="navbar">
            <ul id="primary-menu" class="nav-menu">
                <li class="menu-item"><a href="../index.php">Home</a></li>
                <li class="menu-item"><a href="../about.php">About Us</a></li>
                <!-- <li class="menu-item"><a href="">Courses</a></li> -->
                <!-- <li class="menu-item"><a href="">Sign Up</a></li> -->
                <!-- COURSES DROPDOWN -->
                <li class="menu-item dropdown">
                    <a href="../courses.php">Courses <i class="fa-solid fa-chevron-down"></i></a>

                    <ul class="dropdown-menu">
                        <li><a href="#">Animation</a></li>
                        <li><a href="#">Graphics</a></li>
                        <li><a href="Ultimatephp.php">PHP</a></li>
                    </ul>
                </li>
            </ul>


            <a href="../signup.php" class="sign-up-btn">Sign Up</a>
            <!-- <button class="get-touch-btn">Get In Touch</button> -->
            <a href="" class="get-touch-btn">
                Get in Touch
                <span class="bottom-line"></span>
                <span class="left-line"></span>
            </a>

            <!-- SEARCH ICON ONLY FOR MOBILE -->
            <button class="search-icon-mobile">
                <i class="fa-solid fa-search"></i>
            </button>

            <!-- Theme Toggle Button -->
            <button id="theme-toggle" class="theme-toggle">
                <i id="theme-icon" class="fa-solid fa-sun"></i>
            </button>

            <button id="menu-toggler" class="hamburger">
                <span class="hamburger-line hamburger-line-top"></span>
                <span class="hamburger-line hamburger-line-middle"></span>
                <span class="hamburger-line hamburger-line-bottom"></span>
            </button>
        </div>
    </header>
    <!-- **************************** HEADER ENDS ************************************* -->


    <!-- ================= COURSE VIDEO SECTION ================= -->
    <main>
        <section class="course-section">

            <div class="video-container">

                <!-- Background Visible Video (Paused/Poster Mode) -->
                <video id="mainVideo" class="bg-video" src="assets/videos/study-video.mp4" muted playsinline
                    preload="metadata"></video>

                <!-- Glass Overlay Box -->
                <div class="glass-box">
                    <h2>Ultimate Core PHP Full Stack Developer Course</h2>
                    <p>This course helps you learn essential skills with practical real-world training.</p>

                    <div class="btn-group">
                        <a href="#" class="btn">Get Consultancy</a>
                        <a href="#" class="btn">Enroll Now</a>
                    </div>
                </div>

            </div>
        </section>


        <!-- ================= POPUP VIDEO ================= -->
        <div id="videoPopup" class="popup">
            <div class="popup-content">
                <span id="closePopup" class="close-btn">&times;</span>
                <video id="popupVideo" controls autoplay>
                    <source src="assets/videos/study-video.mp4" type="video/mp4">
                </video>
            </div>
        </div>
    </main>







    <!-- **************************** section 2 *************************** -->


    <section class="course-info-section">

        <!-- INTERNAL MINI MENU -->
        <div class="course-mini-header" id="courseMiniHeader">
            <ul class="course-mini-menu">
                <li data-target="about" class="active">About</li>
                <li data-target="benefits">Benefits</li>
                <li data-target="modules">Modules</li>
                <!--<li data-target="recommend">Participation</li>-->
            </ul>
        </div>

        <!-- CONTENT AREA -->
        <div class="course-content-box">

            <!-- ABOUT -->
            <div class="course-content active" id="about">

                <h3 class="detail-title">What You Will Learn</h3>
                <p class="detail-para">This course helps you learn essential skills with practical real-world training.
                    Master full-stack web development with this intensive PHP bootcamp! From beginner basics in HTML,
                    CSS, JavaScript & Bootstrap to advanced PHP, OOP, MySQL databases, forms, security & real-world
                    projects like blogs and e-commerce prototypes. Includes Git, deployment & interview prep – build a
                    portfolio in just 13-15 days intensive or 30 days paced. Perfect for beginners aiming for junior
                    developer roles!"
                </p>

                <div class="detail-grid">

                    <div class="detail-card">
                        <div class="detail-icon"><i class="fa-solid fa-check"></i></div>
                        <div class="detail-info">
                            <h4>Industry-Level Training</h4>
                            <p>Learn with real professional workflows.</p>
                        </div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-icon"><i class="fa-solid fa-lightbulb"></i></div>
                        <div class="detail-info">
                            <h4>Creative Skills</h4>
                            <p>Enhance your creativity and concept skills.</p>
                        </div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-icon"><i class="fa-solid fa-layer-group"></i></div>
                        <div class="detail-info">
                            <h4>Multiple Tools</h4>
                            <p>Master all tools step-by-step.</p>
                        </div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-icon"><i class="fa-solid fa-briefcase"></i></div>
                        <div class="detail-info">
                            <h4>Portfolio Projects</h4>
                            <p>Build real-world projects for jobs.</p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- BENEFITS -->
            <div class="course-content" id="benefits">

                <h3 class="detail-title">Benefits You Will Get</h3>
                <p class="detail-para">Joining this course gives you long-term career advantages.</p>

                <div class="detail-grid">

                    <div class="detail-card">
                        <div class="detail-icon"><i class="fa-regular fa-circle-check"></i></div>
                        <div class="detail-info">
                            <!---<h4>Expert Guidance</h4>--->
                            <p>Build a complete professional portfolio with multiple real-world projects, including a
                                fully responsive website, secure login system, dynamic blog with CRUD operations, and a
                                mini e-commerce prototype</p>
                        </div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-icon"><i class="fa-regular fa-circle-check"></i></div>
                        <div class="detail-info">
                            <!--<h4>Certificate</h4>-->
                            <p>Gain job-ready full-stack skills using pure/core PHP – no frameworks needed to start
                                earning as a junior web developer</p>
                        </div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-icon"><i class="fa-regular fa-circle-check"></i></div>
                        <div class="detail-info">
                            <!---<h4>Lifetime Support</h4>--->
                            <p>Learn modern, industry-standard practices from day one: clean code organization, security
                                fundamentals, OOP design, and database optimization</p>
                        </div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-icon"><i class="fa-regular fa-circle-check"></i></div>
                        <div class="detail-info">
                            <!--<h4>Job Assistance</h4>--->
                            <p>Deploy your projects live and showcase them on GitHub to impress recruiters</p>
                        </div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-icon"><i class="fa-regular fa-circle-check"></i></div>
                        <div class="detail-info">
                            <!--<h4>Job Assistance</h4>--->
                            <p>Complete the course intensively in just 13–15 days (or comfortably in 30 days) and go
                                from beginner to confident developer</p>
                        </div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-icon"><i class="fa-regular fa-circle-check"></i></div>
                        <div class="detail-info">
                            <!--<h4>Job Assistance</h4>--->
                            <p>Get prepared for interviews with 100+ common PHP questions, best practices, and
                                confidence in building full-stack applications from scratch</p>
                        </div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-icon"><i class="fa-regular fa-circle-check"></i></div>
                        <div class="detail-info">
                            <!--<h4>Job Assistance</h4>--->
                            <p>Lifetime access to downloadable resources, code files, quizzes path to ready for PHP/web
                                development roles</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODULES -->
            <div class="course-content" id="modules">

                <!-- flush course -->

                <div class="accordion accordion-flush" id="accordionFlushExample">

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" onclick="toggleAccordion(this)">
                                <h3>Introduction and Setup</h3>
                                <span class="hover-detail">Detail View</span>
                            </button>
                        </h2>
                        <div class="accordion-body">
                            <p>
                                Get XAMPP, VS Code, write first PHP script, download materials
                            </p>
                            <br>
                            <!-- <h2>What's included</h2>
                            <br>
                            <br>
                            <div class="link-section">
                                <a href="">7 videos<i class="fa-solid fa-video"></i></a>
                                <a href="">7 readings <i class="fa-solid fa-book-open"></i></a>
                                <a href="">1 assignment <i class="fas fa-file-alt"></i></a>
                                <a href="">1 app item <i class="fas fa-border-all"></i></a>
                            </div>-->
                            <br>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" onclick="toggleAccordion(this)">
                                <h3>Frontend Fundamentals (HTML, CSS, Bootstrap, Basic JS)</h3>
                                <span class="hover-detail">Detail View</span>
                            </button>
                        </h2>
                        <div class="accordion-body">
                            <p>
                                Build beautiful, responsive static websites before adding PHP
                            </p>
                            <br>
                            <h3>What's included</h3>
                            <br>
                            <div class="link-section">
                                <a href=""><i class="fa-solid fa-folder"></i> Resources</a>
                                <a href=""><i class="fas fa-file-alt"></i> Exercise</a>
                            </div>
                            <br>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" onclick="toggleAccordion(this)">
                                <h3>PHP Fundamentals</h3>
                                <span class="hover-detail">Detail View</span>
                            </button>
                        </h2>
                        <div class="accordion-body">
                            <p>
                                Variables, data types, operators, control structures, functions
                            </p>
                            <br>
                            <h3>What's included</h3>
                            <br>
                            <div class="link-section">
                                <a href="">7 videos <i class="fa-solid fa-video"></i></a>
                                <a href="">7 readings <i class="fa-solid fa-book-open"></i></a>
                                <a href="">1 assignment <i class="fas fa-file-alt"></i></a>
                                <a href="">1 app item<i class="fas fa-border-all"></i></a>
                            </div>
                            <br>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" onclick="toggleAccordion(this)">
                                <h3>Loops & Arrays (Indexed, Associative, Multi-dimensional)</h3>
                                <span class="hover-detail">Detail View</span>
                            </button>
                        </h2>
                        <div class="accordion-body">
                            <p>
                                Master data storage and iteration – used in every real project
                            </p>
                            <br>
                            <h3>What's included</h3>
                            <br>
                            <div class="link-section">
                                <a href=""><i class="fas fa-file-alt"></i> Exercise</a>
                            </div>
                            <br>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" onclick="toggleAccordion(this)">
                                <h3>Forms, URL Parameters, Sessions, Cookies & Security</h3>
                                <span class="hover-detail">Detail View</span>
                            </button>
                        </h2>
                        <div class="accordion-body">
                            <p>
                                Handle user input safely (GET, POST, XSS, CSRF basics, sessions) – critical for real
                                apps
                            </p>
                            <br>
                            <h3>What's included</h3>
                            <br>
                            <div class="link-section">
                                <a href="">7 videos <i class="fa-solid fa-video"></i></a>
                                <a href="">7 readings <i class="fa-solid fa-book-open"></i></a>
                                <a href="">1 assignment <i class="fas fa-file-alt"></i></a>
                                <a href="">1 app item<i class="fas fa-border-all"></i></a>
                            </div>
                            <br>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" onclick="toggleAccordion(this)">
                                <h3>Strings, File Handling & Includes</h3>
                                <span class="hover-detail">Detail View</span>
                            </button>
                        </h2>
                        <div class="accordion-body">
                            <p>
                                Manipulate text, include files cleanly, read/write files
                            </p>
                            <br>
                            <h3>What's included</h3>
                            <br>
                            <div class="link-section">
                                <a href=""><i class="fa-solid fa-folder"></i> Resources</a>
                                <a href=""><i class="fas fa-file-alt"></i> Exercise</a>
                            </div>
                            <br>
                        </div>
                    </div>


                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" onclick="toggleAccordion(this)">
                                <h3>Object-Oriented PHP (OOP)</h3>
                                <span class="hover-detail">Detail View</span>
                            </button>
                        </h2>
                        <div class="accordion-body">
                            <p>
                                Classes, objects, inheritance, polymorphism, encapsulation – modern PHP standard
                            </p>
                            <br>
                            <br>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" onclick="toggleAccordion(this)">
                                <h3>MySQL + PHP Integration (mysqli & Prepared statements)</h3>
                                <span class="hover-detail">Detail View</span>
                            </button>
                        </h2>
                        <div class="accordion-body">
                            <p>
                                Full database design, CRUD, joins, prepared statements + 2 mini-projects (Blog +
                                E-commerce prototype)
                            </p>
                            <br>
                            <h3>What's included</h3>
                            <br>
                            <div class="link-section">
                                <a href=""><i class="fa-solid fa-folder"></i> Resources</a>
                                <a href=""><i class="fas fa-file-alt"></i> Exercise</a>
                            </div>
                            <br>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" onclick="toggleAccordion(this)">
                                <h3>Bonus: Git, Best Practices, Deployment, Interview Prep</h3>
                                <span class="hover-detail">Detail View</span>
                            </button>
                        </h2>
                        <div class="accordion-body">
                            <p>
                                Professional habits, Git, SEO, performance, common pitfalls, 100+ interview Q&A
                            </p>
                            <br>
                        </div>
                    </div>
                    <br>
                    <br>
                    <br>
                    <!-- Course end -->


                </div>

                <!-- RECOMMEND -->
                <div class="course-content" id="recommend">

                    <h3 class="detail-title">Who Should Join?</h3>
                    <p class="detail-para">This course is perfect for a wide range of learners.</p>

                    <div class="detail-grid">

                        <div class="detail-card">
                            <div class="detail-icon"><i class="fa-solid fa-user"></i></div>
                            <div class="detail-info">
                                <h4>Students</h4>
                                <p>Boost your knowledge.</p>
                            </div>
                        </div>

                        <div class="detail-card">
                            <div class="detail-icon"><i class="fa-solid fa-person-chalkboard"></i></div>
                            <div class="detail-info">
                                <h4>Beginners</h4>
                                <p>Start from absolute zero.</p>
                            </div>
                        </div>

                        <div class="detail-card">
                            <div class="detail-icon"><i class="fa-solid fa-laptop-code"></i></div>
                            <div class="detail-info">
                                <h4>Freelancers</h4>
                                <p>Improve skills & earn more.</p>
                            </div>
                        </div>

                        <div class="detail-card">
                            <div class="detail-icon"><i class="fa-solid fa-people-arrows"></i></div>
                            <div class="detail-info">
                                <h4>Career Switchers</h4>
                                <p>Enter a professional field.</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
    </section>






    <script>
        // show first frame as preview (no play)
        let mainVid = document.getElementById("mainVideo");
        mainVid.addEventListener("loadedmetadata", () => {
            mainVid.currentTime = 0.2;
            mainVid.pause();
        });

        // open popup + play
        mainVid.addEventListener("click", () => {
            document.getElementById("videoPopup").style.display = "flex";
            document.getElementById("popupVideo").play();
        });

        // close popup + pause
        document.getElementById("closePopup").addEventListener("click", () => {
            document.getElementById("videoPopup").style.display = "none";
            document.getElementById("popupVideo").pause();
        });








        // <!-- **************************** section 2 *************************** -->
        // DISABLE ALL SCRIPT ON MOBILE
        if (window.innerWidth <= 768) {

            console.log("Mobile detected – mini menu disabled.");

        } else {

            /* ============================================
                1) MENU CLICK + SMOOTH SCROLL
            ============================================ */
            const menuItems = document.querySelectorAll(".course-mini-menu li");
            const sections = document.querySelectorAll(".course-content");

            menuItems.forEach(item => {
                item.addEventListener("click", () => {

                    // Active on click
                    menuItems.forEach(li => li.classList.remove("active"));
                    item.classList.add("active");

                    // Smooth Scroll
                    const targetSection = document.getElementById(item.dataset.target);
                    const topPos = targetSection.offsetTop - 120;

                    window.scrollTo({
                        top: topPos,
                        behavior: "smooth"
                    });
                });
            });


            /* ============================================
                2) SCROLL SPY (Auto Active on Scroll)
            ============================================ */
            window.addEventListener("scroll", () => {
                let scrollPos = window.scrollY + 180; // adjust for top gap

                sections.forEach(section => {
                    if (
                        scrollPos >= section.offsetTop &&
                        scrollPos < section.offsetTop + section.offsetHeight
                    ) {
                        let id = section.getAttribute("id");

                        // Remove active from all
                        menuItems.forEach(li => li.classList.remove("active"));

                        // Set active to matching menu
                        document
                            .querySelector(`.course-mini-menu li[data-target="${id}"]`)
                            .classList.add("active");
                    }
                });
            });


            /* ============================================
                3) HEADER HIDE + MINI MENU STICKY
            ============================================ */
            const mainHeader = document.querySelector("header");
            const miniHeader = document.getElementById("courseMiniHeader");

            let miniMenuTimeout;

            window.addEventListener("scroll", () => {

                if (window.scrollY > 200) {

                    // hide main header
                    mainHeader.style.transform = "translateY(-100%)";

                    clearTimeout(miniMenuTimeout);

                    miniMenuTimeout = setTimeout(() => {
                        miniHeader.classList.add("sticky");
                    }, 200);

                } else {
                    mainHeader.style.transform = "translateY(0)";
                    miniHeader.classList.remove("sticky");
                    clearTimeout(miniMenuTimeout);
                }
            });

        }




        // course filter end******************

        // course Flush Accordion
        function toggleAccordion(btn) {
            const item = btn.parentElement.parentElement;
            const allItems = document.querySelectorAll('.accordion-item');

            allItems.forEach(i => {
                if (i !== item) i.classList.remove('activee');
            });

            item.classList.toggle('activee');
        }
        // course Flush Accordion ed



    </script>

    <Script src="../assets/js/script.js"></Script>
</body>

</html>