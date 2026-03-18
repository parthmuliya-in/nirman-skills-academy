<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/images/logo.png" type="image/png">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <header class="header">
        <div class="logo">
            <img src="assets/images/logo.png" alt="Logo-image">
        </div>

        <!-- SEARCH BAR -->

        <div class="navbar">
            <ul id="primary-menu" class="nav-menu">
                <li class="menu-item"><a href="index.php">Home</a></li>
                <li class="menu-item"><a href="about.php">About Us</a></li>
                <!-- COURSES DROPDOWN -->
                <li class="menu-item dropdown">
                    <a href="courses.php">Courses</a>

                    <!-- <ul class="dropdown-menu">
                        <li><a href="#">Animation</a></li>
                        <li><a href="#">Graphics</a></li>
                    </ul> -->
                </li>
                <li><a href="contact.php">Contact</a></li>
            </ul>


            <!-- <a href="signup.html" class="sign-up-btn">Sign Up</a> -->
            <!-- <button class="get-touch-btn">Get In Touch</button> -->
            <!-- <a href="index.html#consultancy-form" class="get-touch-btn" id="bg">
                <p> Get in Touch</p>
                <span class="bottom-line"></span>
                <span class="left-line"></span>
            </a> -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- USER MENU inside nav -->
                <li class="menu-item dropdown user-menu" style="list-style: none;">
                    <a href="javascript:void(0);" class="user-btn">
                        <span class="sign-up-btn"><i class="fa-solid fa-user"></i>
                            <?= htmlspecialchars($_SESSION['user_name']) ?>
                            <i class="fa-solid fa-chevron-down"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- <li><a href="profile.php"><i class="fa-solid fa-id-badge"></i> Profile</a></li> -->
                        <li><a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <!-- <li class="menu-item"><a href="login.php">Login</a></li> -->
                <a href="signup.php" class="sign-up-btn">Sign Up</a>
            <?php endif; ?>
            <a href="index.php#consultForm" class="get-touch-btn" id="bg">
                <p> Get in Touch</p>
                <span class="bottom-line"></span>
                <span class="left-line"></span>
            </a>
            <!-- SEARCH ICON ONLY FOR MOBILE -->
            <button class="search-icon-mobile">
                <i class="fa-solid fa-search"></i>
            </button>
            <div class="search-box">
                <input type="text" placeholder="Search...">
                <button class="search-btn"><i class="fa-solid fa-search"></i></button>
            </div>
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


</body>
<script>

    // Mobile Menu Toggle
    document.addEventListener("DOMContentLoaded", function () {
        const menuToggler = document.getElementById("menu-toggler");
        const navMenu = document.getElementById("primary-menu");


        menuToggler.addEventListener("click", () => {
            menuToggler.classList.toggle("active");
            navMenu.classList.toggle("active");
        });

        // change
        const searchBo = document.querySelector('.search-box');
        const searchBtn = document.querySelector('.search-btn');

        searchBtn.addEventListener('click', () => {
            searchBox.classList.toggle('active');
            if (searchBo.classList.contains('active')) {    
                searchBo.querySelector('input').focus(); // input pe focus
            }
        });

        // change

        // Theme Toggle + Save State
        const toggleBtn = document.getElementById("theme-toggle");
        const themeIcon = document.getElementById("theme-icon");

        // STEP 1: Apply Saved Theme on Load
        if (localStorage.getItem("theme") === "dark") {
            document.body.classList.add("dark-mode");
            // themeIcon.classList.remove("fa-moon");
            // themeIcon.classList.add("fa-sun");
            document.body.style.background = "#111";
            document.body.style.color = "#fff";
        }

        // STEP 2: Toggle Theme Button
        toggleBtn.addEventListener("click", () => {
            document.body.classList.toggle("dark-mode");

            if (document.body.classList.contains("dark-mode")) {
                // themeIcon.classList.remove("fa-moon");
                // themeIcon.classList.add("fa-sun");
                document.body.style.background = "#111";
                document.body.style.color = "#fff";

                localStorage.setItem("theme", "dark"); // SAVE
            } else {
                // themeIcon.classList.remove("fa-sun");
                // themeIcon.classList.add("fa-moon");
                document.body.style.background = "#fff";
                document.body.style.color = "#000";

                localStorage.setItem("theme", "light"); // SAVE
            }
        });
        function moveButtonForMobile() {
            const navMenu = document.getElementById("primary-menu");
            const getBtn = document.querySelector(".get-touch-btn");
            const signBtn = document.querySelector(".sign-up-btn");
            const navbar = document.querySelector(".navbar");

            // MOBILE VIEW — move buttons inside menu
            if (window.innerWidth <= 850) {

                if (!navMenu.contains(signBtn)) {
                    navMenu.appendChild(signBtn);
                }
                if (!navMenu.contains(getBtn)) {
                    navMenu.appendChild(getBtn);
                }

            } else {
                // DESKTOP VIEW — move buttons back to navbar
                if (!navbar.contains(signBtn)) {
                    navbar.insertBefore(signBtn, getBtn);
                }
                if (!navbar.contains(getBtn)) {
                    navbar.insertBefore(getBtn, document.getElementById("theme-toggle"));
                }
            }
        }

        // Run on load + resize
        window.addEventListener("load", moveButtonForMobile);
        window.addEventListener("resize", moveButtonForMobile);



        //*************************************************************************

        //Add Search Icon Toggle + Mobile Dropdown Click

        const searchIconMobile = document.querySelector(".search-icon-mobile");
        const searchBox = document.querySelector(".search-box");

        searchIconMobile.addEventListener("click", () => {
            searchBox.classList.toggle("active");
        });

        // MOBILE DROPDOWN CLICK
        document.querySelectorAll(".dropdown > a").forEach(drop => {
            drop.addEventListener("click", (e) => {
                if (window.innerWidth <= 850) {
                    e.preventDefault();
                    drop.parentElement.classList.toggle("open");
                }
            });
        });

    });

</script>

</html>