<?php
session_start();
include "../include/config.php";

// Secure Admin Access Check
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="icon" href="image/logo.png" type="image/png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* RESET */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* BODY */
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f8f9fa;
        }

        /* HEADER */
        .admin-header {
            background: #fff;
            border-bottom: 1px solid #eee;
            position: sticky;
            top: 0;
            z-index: 9999;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        /* CONTAINER */
        .admin-container {
            max-width: 1400px;
            margin: auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            height: 65px;
        }

        /* LOGO */
        .admin-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 22px;
            font-weight: 600;
            color: #ff6600;
            text-decoration: none;
        }

        .admin-logo img {
            width: 36px;
        }

        /* MENU */
        .admin-menu {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 18px;
        }

        /* LINKS */
        .admin-menu a,
        .menu-btn {
            color: #000;
            text-decoration: none;
            padding: 8px 10px;
            border-radius: 6px;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: 0.3s;
            cursor: pointer;
        }

        /* HOVER */
        .admin-menu a:hover,
        .menu-btn:hover {
            color: #ff6600;
        }

        /* DROPDOWN */
        .menu-dropdown {
            position: relative;
        }

        .menu-list {
            position: absolute;
            top: 120%;
            left: 0;
            background: #fff;
            min-width: 210px;
            border-radius: 10px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            display: none;
            opacity: 0;
            transform: translateY(10px);
            transition: 0.3s;
            overflow: hidden;
        }

        .menu-dropdown.active .menu-list {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        .menu-list a {
            display: block;
            padding: 12px 15px;
            font-size: 14px;
            border-bottom: 1px solid #f1f1f1;
        }

        .menu-list a:hover {
            background: #fff3e6;
            color: #ff6600;
        }

        /* ICONS */
        .admin-icons {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-left: 15px;
        }

        .logout-btn {
            color: #ff3b3b;
            font-size: 18px;
        }

        /* TOGGLE */
        .menu-toggle {
            display: none;
            font-size: 22px;
            cursor: pointer;
            color: #000;
        }

        /* OVERLAY */
        .menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.45);
            opacity: 0;
            visibility: hidden;
            transition: 0.3s;
            z-index: 9998;
        }

        .menu-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* HIDE BY DEFAULT (DESKTOP) */
        .menu-toggle {
            display: none;
            font-size: 22px;
            cursor: pointer;
            color: #000;
        }

        /* SHOW ONLY ON TABLET & MOBILE */
        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }
        }

        /* ================= MOBILE ================= */
        @media (max-width:768px) {

            .menu-toggle {
                display: block;
            }

            .admin-container {
                height: 60px;
            }

            .admin-menu {
                position: fixed;
                top: 0;
                right: -100%;
                width: 270px;
                height: 100%;
                background: #fff;
                flex-direction: column;
                align-items: flex-start;
                padding: 20px;
                gap: 8px;
                transition: 0.4s ease;
                z-index: 9999;
                overflow-y: auto;
            }

            .admin-menu.active {
                right: 0;
            }

            .admin-menu a,
            .menu-btn {
                width: 100%;
                padding: 12px;
                font-size: 15px;
            }

            .menu-list {
                position: static;
                width: 100%;
                box-shadow: none;
                display: none;
                opacity: 1;
                transform: none;
            }

            .menu-dropdown.active .menu-list {
                display: block;
            }

            .menu-list a {
                padding-left: 30px;
                background: #f9f9f9;
            }
        }

        /* SMALL MOBILE */
        @media (max-width:480px) {

            .admin-logo span {
                display: none;
            }

            .admin-menu {
                width: 230px;
            }
        }
    </style>
</head>

<body>
    <div class="menu-overlay" id="menuOverlay"></div>
    <header class="admin-header">
        <div class="admin-container">
            <!-- Logo -->
            <a href="index.php" class="admin-logo">
                <img src="image/logo.png" alt="Logo">
                <span>NSA Admin</span>
            </a>

            <!-- Desktop Menu -->
            <nav class="admin-menu" id="adminMenu">
                <a href="index.php">
                    Dashboard
                    <!-- <i class="fa-solid fa-chart-line"></i> Dashboard -->
                </a>

                <!-- Users Dropdown -->
                <div class="menu-dropdown">
                    <div class="menu-btn">Users
                        <!-- <i class="fa-solid fa-users"></i> Users -->
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="menu-list">
                        <a href="manage_users.php">All Users</a>
                        <a href="consultations.php">Consultations</a>
                        <a href="contact_us.php">Contact Messages</a>
                        <a href="subscribers.php">Subscribers</a>
                    </div>
                </div>

                <!-- <a href="enrollments.php">
                    <i class="fa-solid fa-user-check"></i> Enrollments
                </a> -->

                <!-- Courses Dropdown -->
                <div class="menu-dropdown">
                    <div class="menu-btn">Courses
                        <!-- <i class="fa-solid fa-book"></i> Courses -->
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="menu-list">
                        <a href="add_course2.php">Add New Course</a>
                        <a href="view_courses.php">Manage Courses</a>
                        <a href="all_course.php">View All Courses</a>
                    </div>
                </div>
                <div class="menu-dropdown">
                    <div class="menu-btn">Practices
                        <!-- <i class="fa-solid fa-file-lines"></i> Practices -->
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="menu-list">
                        <a href="practices.php">Add Practices</a>
                        <a href="view_practices.php">View Practices</a>
                    </div>
                </div>
                <div class="menu-dropdown">
                    <div class="menu-btn">Payment
                        <!-- <i class="fa-solid fa-file-lines"></i> Payment -->
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="menu-list">
                        <a href="payment.php">Add Payment</a>
                        <a href="payment_history.php">View Payment</a>
                    </div>
                </div>
                <div class="menu-dropdown">
                    <div class="menu-btn">Settings
                        <!-- <i class="fa-solid fa-file-lines"></i> Settings -->
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="menu-list">
                        <a href="enrollments.php">Enrollments</a>
                        <a href="add_certificate.php">Certificate</a>
                    </div>
                </div>
                <a href="admin_logout.php" class="logout-btn" title="Logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </a>
            </nav>
            <!-- Right Icons -->
            <div class="admin-icons">
                <!-- <a href="admin_logout.php" class="logout-btn" title="Logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </a> -->
                <i class="fa-solid fa-bars menu-toggle" id="menuToggle"></i>
            </div>
        </div>
    </header>

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const adminMenu = document.getElementById('adminMenu');
        const overlay = document.getElementById('menuOverlay');

        // TOGGLE MENU
        menuToggle.addEventListener('click', () => {
            adminMenu.classList.toggle('active');
            overlay.classList.toggle('active');
        });

        // CLOSE MENU
        overlay.addEventListener('click', () => {
            adminMenu.classList.remove('active');
            overlay.classList.remove('active');
        });

        // AUTO CLOSE ON DESKTOP
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                adminMenu.classList.remove('active');
                overlay.classList.remove('active');
            }
        });

        // DROPDOWN
        document.querySelectorAll('.menu-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();

                const parent = btn.parentElement;

                document.querySelectorAll('.menu-dropdown').forEach(d => {
                    if (d !== parent) d.classList.remove('active');
                });

                parent.classList.toggle('active');
            });
        });

        // CLOSE MENU ON LINK CLICK (MOBILE)
        document.querySelectorAll('.admin-menu a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    adminMenu.classList.remove('active');
                    overlay.classList.remove('active');
                }
            });
        });
    </script>

</body>

</html>