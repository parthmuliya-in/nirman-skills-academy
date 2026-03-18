<?php
// if (session_status() === PHP_SESSION_NONE) {
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

        <div class="navbar">
            <ul id="primary-menu" class="nav-menu">
                <li class="menu-item"><a href="index.php">Home</a></li>
                <li class="menu-item"><a href="about.php">About Us</a></li>
                <li class="menu-item dropdown">
                    <a href="courses.php">Courses</a>
                </li>
                <li class="menu-item"><a href="contact.php">Contact</a></li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- USER MENU inside nav -->
                    <li class="menu-item dropdown user-menu">
                        <a href="javascript:void(0);" class="user-btn">
                            <i class="fa-solid fa-user"></i>
                            <span class="user-name"><?= htmlspecialchars($_SESSION['user_name']) ?></span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- <li><a href="profile.php"><i class="fa-solid fa-id-badge"></i> Profile</a></li> -->
                            <li><a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <!-- <li class="menu-item"><a href="login.php">Login</a></li> -->
                    <li class="menu-item"><a href="signup.php">Sign Up</a></li>
                <?php endif; ?>
                <li>
                    <a href="index.php#consultForm" class="get-touch-btn">Get in Touch </a>
                </li>
            </ul>

            <!-- Desktop Buttons -->
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

            <!-- Mobile Hamburger -->
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

    // USER DROPDOWN TOGGLE
    const userBtn = document.querySelector(".user-btn");
    const userMenu = document.querySelector(".user-menu");

    if (userBtn) {
        userBtn.addEventListener("click", () => {
            userMenu.classList.toggle("active");
        });

        // Close when clicking outside
        document.addEventListener("click", (e) => {
            if (!userMenu.contains(e.target)) {
                userMenu.classList.remove("active");
            }
        });
    }
    function convertH2ToButton(h2Id) {
        const h2 = document.getElementById(h2Id);
        if (!h2) return; // agar id nahi milti, skip

        const btn = document.createElement("a");
        btn.className = "get-touch-btn";
        btn.href = "#";
        btn.style.display = "inline-block";
        btn.style.padding = "10px 10px";
        btn.style.lineHeight = "1.2";

        btn.innerHTML = `
        ${h2.textContent}
        <span class="bottom-line"></span>
        <span class="left-line"></span>
    `;

        h2.textContent = "";
        h2.appendChild(btn);
    }

    // ---- USE IT FOR ALL H2 IDs ----
    convertH2ToButton("tagline-id");
    convertH2ToButton("animated-text");
    convertH2ToButton("animated-text2");

</script>

</html>