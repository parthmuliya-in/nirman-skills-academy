<?php
include "../header.php";
include "../api/wp_app.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArtLab Academy</title>
    <link rel="icon" href="assets/images/logo.png" type="image/png">

    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/course.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>
    <!-- **************************** HEADER ************************************* -->
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
                    <h2>Our Premium Courses</h2>
                    <p>Master Animation, Graphic Designing & PHP Development with industry-level training.</p>

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
                <li data-target="recommend">FAQ</li>
            </ul>
        </div>

        <!-- CONTENT AREA -->
        <div class="course-content-box">

            <!-- ABOUT -->
            <div class="course-content active" id="about">

                <h3 class="detail-title">What You Will Learn</h3>
                <p class="detail-para">This course helps you learn essential skills with practical real-world training.
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
                        <div class="detail-icon"><i class="fa-solid fa-user-tie"></i></div>
                        <div class="detail-info">
                            <h4>Expert Guidance</h4>
                            <p>Learn directly from industry experts.</p>
                        </div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-icon"><i class="fa-solid fa-award"></i></div>
                        <div class="detail-info">
                            <h4>Certificate</h4>
                            <p>Get recognized certification.</p>
                        </div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-icon"><i class="fa-solid fa-headset"></i></div>
                        <div class="detail-info">
                            <h4>Lifetime Support</h4>
                            <p>Always stay connected for help.</p>
                        </div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                        <div class="detail-info">
                            <h4>Job Assistance</h4>
                            <p>Resume, interview & placement help.</p>
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
                                <h3> Difference between After Effects and Premiere Pro</h3>
                                <span class="hover-detail">Detail View</span>
                            </button>
                        </h2>
                        <div class="accordion-body">
                            <p>
                                Adobe Premiere Pro is a timeline-based video editing software focused on cutting,
                                assembling, and polishing footage for films and videos.
                                After Effects excels in motion graphics, visual effects, and compositing, allowing
                                frame-by-frame animation and advanced effects creation.
                            </p>
                            <br>
                            <h2>What's included</h2>
                            <br>
                            <br>
                            <div class="link-section">
                                <a href="">7 videos<i class="fa-solid fa-video"></i></a>
                                <a href="">7 readings <i class="fa-solid fa-book-open"></i></a>
                                <a href="">1 assignment <i class="fas fa-file-alt"></i></a>
                                <a href="">1 app item <i class="fas fa-border-all"></i></a>
                            </div>
                            <br>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" onclick="toggleAccordion(this)">
                                <h3>After Effect Interface</h3>
                                <span class="hover-detail">Detail View</span>
                            </button>
                        </h2>
                        <div class="accordion-body">
                            <p>
                                The After Effects interface features a Composition panel for previewing animations,
                                Timeline for layering and keyframing, and Project panel for managing assets.
                                Tools panel, Effects & Presets, and customizable workspaces enable efficient motion
                                graphics and VFX workflows.
                            </p>
                            <br>
                            <h3>What's included</h3>
                            <br>
                            <div class="link-section">
                                <a href="">7 videos<i class="fa-solid fa-video"></i></a>
                                <a href="">7 readings <i class="fa-solid fa-book-open"></i></a>
                                <a href="">1 assignment<i class="fas fa-file-alt"></i></a>
                                <a href="">1 app item <i class="fas fa-border-all"></i></a>
                            </div>
                            <br>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" onclick="toggleAccordion(this)">
                                <h3>Character Animation</h3>
                                <span class="hover-detail">Detail View</span>
                            </button>
                        </h2>
                        <div class="accordion-body">
                            <p>
                                Character animation in After Effects often uses rigging with tools like Duik or Puppet
                                Pin for posing and animating limbs and expressions.
                                Keyframing position, rotation, and scale brings 2D characters to life, ideal for
                                explainer videos and cartoons.
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
                                <h3>Car Animation</h3>
                                <span class="hover-detail">Detail View</span>
                            </button>
                        </h2>
                        <div class="accordion-body">
                            <p>
                                Car animation in After Effects involves keyframing motion paths, adding wheel rotation,
                                and effects like motion blur for realistic movement.
                                Using null objects for parenting and expressions simplifies creating driving scenes or
                                dynamic vehicle graphics.
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

    <section class="faq-section">
        <div class="faq-container">

            <h2 class="faq-title">Frequently Asked <span>Questions</span> :</h2>

            <div class="faq-item">
                <button class="faq-question">
                    How long does this Animation course take?

                    <span class="arrow">+</span>
                </button>
                <div class="faq-answer">
                    <p>This Animation course offers Fast-Track and Regular batches. The Fast-Track batch lasts 2 months,
                        while the Regular
                        batch lasts 3 months. You can choose online or offline classes and learn at a schedule that
                        suits you.

                        .</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    Which batch should I choose, Fast-Track or Regular?
                    <span class="arrow">+</span>
                </button>
                <div class="faq-answer">
                    <p>
                        It depends on your goal. The Fast-Track batch (2 months) is for those who want to complete the
                        course quickly, while the
                        Regular batch (3 months) is better for detailed learning with more practice time.


                    </p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    Can I practice real projects during the course?
                    <span class="arrow">+</span>
                </button>
                <div class="faq-answer">
                    <p>
                        Yes! You will work on real animation projects in both batches. This helps you gain practical
                        experience and build a strong
                        portfolio, ready for jobs or freelance opportunities.

                    </p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    Can I learn animation online or offline with this course?
                    <span class="arrow">+</span>
                </button>
                <div class="faq-answer">
                    <p>
                        Yes! This professional Animation course offers both online and offline classes, making it
                        flexible and convenient. You will
                        learn animation skills, work on real projects, and build a portfolio that helps you start a
                        career or freelance work in
                        animation.
                    </p>
                </div>
            </div>



        </div>
    </section>

    <?php 
        include "../footer.php";
    ?>
    <script>
        const faqItems = document.querySelectorAll(".faq-item");

        faqItems.forEach(item => {
            item.querySelector(".faq-question").addEventListener("click", () => {
                item.classList.toggle("active");
            });
        });



        //add pop-up
        let popupShown = false;

        window.addEventListener("scroll", function () {
            if (!popupShown && window.scrollY > window.innerHeight * 0.50) {
                document.getElementById("ad-popup").style.display = "block";
                document.getElementById("ad-overlay").style.display = "block";

                setTimeout(() => {
                    document.getElementById("ad-popup").style.transform = "translate(-50%, -50%) scale(1)";
                }, 50);

                popupShown = true;
            }
        });

        /* Close Button */
        document.querySelector(".ad-close").addEventListener("click", function () {
            closePopup();
        });

        document.getElementById("ad-overlay").addEventListener("click", function () {
            closePopup();
        });

        function closePopup() {
            let popup = document.getElementById("ad-popup");
            popup.style.transform = "translate(-50%, -50%) scale(0.5)";
            popup.style.opacity = "0";

            setTimeout(() => {
                popup.style.display = "none";
                document.getElementById("ad-overlay").style.display = "none";
            }, 300);
        }

    </script>




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