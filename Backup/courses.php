<?php
include "header.php";
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

    <!-- **************************** HEADER ENDS ************************************* -->


    <br>
    <br>
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
            <div class="card">
                <img src="https://cdn.pixabay.com/photo/2020/04/08/15/49/coloring-5017860_960_720.jpg">
                <div class="course-text">
                    <h5>Beginner's Guide to Vector Graphic Design description</h5><br>
                        <p>This course introduces you to Adobe
                            Illustrator,
                            the industry-standard tool for creating scalable vector graphics Link:
                            Allcourses/Graphicdesign.php </p>

                    <a class="getBtn" href="Allcourses/Graphicdesign.php">GET more →</a>
                </div>
            </div>

            <div class="card">
                <img src="https://cdn.pixabay.com/photo/2020/04/08/15/49/coloring-5017860_960_720.jpg">
                <div class="course-text">
                    <h5>Master Motion Graphics & Animation description </h5>
                    <br>
                        <p>
                            Adobe After Effects for Beginners: Master Motion
                            Graphic & Animation link:Allcourses/Animation.php
                            Graphic & Animation link:Allcourses/Animation.php
                        </p>
                    <a class="getBtn" href="Allcourses/course2.html">GET more →</a>
                </div>
            </div>

            <div class="card">
                <img src="https://cdn.pixabay.com/photo/2020/04/08/15/49/coloring-5017860_960_720.jpg">
                <div class="course-text">
                    <h5>Ultimate Core PHP Full Stack Developer Course description</h5>
                    <br>
                        <p>This course helps you learn ess
                            ential skills
                            with...
                            practical real-world training. link:Allcourses/Ultimatephp.php
                        </p>


                    <a class="getBtn" href="Allcourses/course3.html">GET more →</a>
                </div>
            </div>
        </div>
    </div>

    <!-- UI / UX (Full width - 2 card) -->
    <div id="uiuxCourses" class="course-section">
        <div class="cards-row two">
            <div class="card">
                <img src="https://cdn.pixabay.com/photo/2020/04/08/15/49/coloring-5017860_960_720.jpg">
                <div class="course-text">
                    <h3>Master Motion Graphics & Animation </h3>
                    <p>Short description.</p>
                    <a class="getBtn" href="Allcourses/Animation.php">GET more →</a>
                </div>
            </div>
        </div>
    </div>

    <!-- WEB DEV -->
    <div id="webCourses" class="course-section">
        <div class="cards-row two">
            <div class="card">
                <img src="https://cdn.pixabay.com/photo/2020/04/08/15/49/coloring-5017860_960_720.jpg">
                <div class="course-text">
                    <h3>Ultimate Core PHP Full Stack Developer Course</h3>
                    <p>This course helps you learn essential skills with practical real-world training.</p>
                    <a class="getBtn" href="Allcourses/Ultimatephp.php">GET more →</a>
                </div>
            </div>
            <div class="card">
                <img src="https://cdn.pixabay.com/photo/2020/04/08/15/49/coloring-5017860_960_720.jpg">
                <div class="course-text">
                    <h3>Python</h3>
                    <p>Short description.</p>
                    <a class="getBtn" href="#">GET more →</a>
                </div>
            </div>
        </div>
    </div>

    <!-- GRAPHIC -->
    <div id="graphicCourses" class="course-section">
        <div class="cards-row two">
            <div class="card">
                <img src="https://cdn.pixabay.com/photo/2020/04/08/15/49/coloring-5017860_960_720.jpg">
                <div class="course-text">
                    <h3>Graphic Beginners To advanced</h3>
                    <p>Short description.</p>
                    <a class="getBtn" href="Allcourses/Graphicdesign.php">GET more→</a>
                </div>
            </div>

        </div>
    </div>
    <br>
    <br>
    <!-- ****************EXPLORE OUR BEST COURSES end*********************** -->
    <?php
    include "footer.php"
        ?>
    <Script src="assets/js/script.js"></Script>
    <Script src="assets/js/courses.js"></Script>






    <script>
        document.querySelectorAll('.course-text p').forEach(p => {
            const fullText = p.innerText.trim();
            const words = fullText.split(/\s+/);

            if (words.length > 7) {
                p.setAttribute("data-fulltext", fullText); // full text safe
                p.innerText = words.slice(0, 7).join(" ") + "...";
            }
        });


        //*************************************************************************

        //Add Search Icon Toggle + Mobile Dropdown Click

        const searchIconMobile = document.querySelector(".search-icon-mobile");
        const searchBox = document.querySelector(".search-box");
        //  new change
        // searchIconMobile.addEventListener("click", () => {
        //     searchBox.classList.toggle("active");
        // });
        //  new change end


        // MOBILE DROPDOWN CLICK
        document.querySelectorAll(".dropdown > a").forEach(drop => {
            drop.addEventListener("click", (e) => {
                if (window.innerWidth <= 850) {
                    e.preventDefault();
                    drop.parentElement.classList.toggle("open");
                }
            });
        });

        // });

    </script>
</body>

</html>