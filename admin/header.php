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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f8f9fa;
        }

        .admin-header {
            background: #fff;
            border-bottom: 1px solid #e0e0e0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
        }

        /* Logo */
        .admin-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 24px;
            font-weight: 700;
            color: #ff6600;
            text-decoration: none;
        }

        .admin-logo img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        /* Desktop Menu */
        .admin-menu {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .admin-menu a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .admin-menu a:hover {
            background: #f2f5f7ff;
            color: #ff6600;
        }

        /* Dropdown */
        .menu-dropdown {
            position: relative;
        }

        .menu-btn {
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.3s;
            color: #333;
            font-weight: 500;
        }

        .menu-btn:hover {
            background: #f0f8ff;
            color: #ff6600;
        }

        .menu-list {
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            min-width: 220px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s ease;
            margin-top: 8px;
            overflow: hidden;
        }

        .menu-dropdown.active .menu-list {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .menu-list a {
            display: block;
            padding: 14px 20px;
            color: #333;
            text-decoration: none;
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.3s;
        }

        .menu-list a:hover {
            background: #f0f8ff;
            color: #ff6600;
        }

        .menu-list a:last-child {
            border-bottom: none;
        }

        /* Icons & Toggle */
        .admin-icons {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logout-btn {
            color: #dc3545;
            font-size: 20px;
            transition: color 0.3s;
        }

        .logout-btn:hover {
            color: #c82333;
        }

        .menu-toggle {
            display: none;
            font-size: 24px;
            cursor: pointer;
            color: #333;
        }

        /* Mobile Styles */
        @media (max-width: 992px) {
            .admin-menu {
                gap: 20px;
            }
        }

        @media (max-width: 768px) {
            .admin-container {
                height: 60px;
                padding: 0 15px;
            }

            .admin-logo {
                font-size: 20px;
            }

            .admin-logo img {
                width: 35px;
                height: 35px;
            }

            .menu-toggle {
                display: block;
            }

            .admin-menu {
                position: fixed;
                top: 60px;
                right: -100%;
                width: 280px;
                height: calc(100vh - 60px);
                background: white;
                flex-direction: column;
                align-items: flex-start;
                padding: 20px;
                box-shadow: -10px 0 30px rgba(0,0,0,0.1);
                transition: right 0.4s ease;
                overflow-y: auto;
            }

            .admin-menu.active {
                right: 0;
            }

            .admin-menu a,
            .menu-btn {
                width: 100%;
                justify-content: flex-start;
                padding: 15px;
                border-radius: 10px;
            }

            .menu-list {
                position: static;
                opacity: 1;
                visibility: visible;
                transform: none;
                box-shadow: none;
                margin-top: 10px;
                width: 100%;
                display: none;
            }

            .menu-dropdown.active .menu-list {
                display: block;
            }

            .menu-list a {
                padding-left: 40px;
                background: #f8f9fa;
            }
        }

        @media (max-width: 480px) {
            .admin-container {
                padding: 0 10px;
            }

            .admin-logo span {
                display: none;
            }
        }
    </style>
</head>
<body>

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
                    <i class="fa-solid fa-chart-line"></i> Dashboard
                </a>

                <!-- Users Dropdown -->
                <div class="menu-dropdown">
                    <div class="menu-btn">
                        <i class="fa-solid fa-users"></i> Users
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="menu-list">
                        <a href="manage_users.php">All Users</a>
                        <a href="consultations.php">Consultations</a>
                        <a href="contact_us.php">Contact Messages</a>
                        <a href="subscribers.php">Subscribers</a>
                    </div>
                </div>

                <a href="enrollments.php">
                    <i class="fa-solid fa-user-check"></i> Enrollments
                </a>

                <!-- Courses Dropdown -->
                <div class="menu-dropdown">
                    <div class="menu-btn">
                        <i class="fa-solid fa-book"></i> Courses
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="menu-list">
                        <a href="add_course2.php">Add New Course</a>
                        <a href="view_courses.php">Manage Courses</a>
                        <a href="all_course.php">View All Courses</a>
                    </div>
                </div>
            </nav>

            <!-- Right Icons -->
            <div class="admin-icons">
                <a href="admin_logout.php" class="logout-btn" title="Logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </a>
                <i class="fa-solid fa-bars menu-toggle" id="menuToggle"></i>
            </div>
        </div>
    </header>

    <script>
        // Mobile menu toggle
        const menuToggle = document.getElementById('menuToggle');
        const adminMenu = document.getElementById('adminMenu');

        menuToggle.addEventListener('click', () => {
            adminMenu.classList.toggle('active');
        });

        // Dropdown functionality (works on both desktop & mobile)
        document.querySelectorAll('.menu-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const parent = this.parentElement;
                
                // Close other dropdowns
                document.querySelectorAll('.menu-dropdown').forEach(dropdown => {
                    if (dropdown !== parent) {
                        dropdown.classList.remove('active');
                    }
                });
                
                parent.classList.toggle('active');
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', () => {
            document.querySelectorAll('.menu-dropdown').forEach(dropdown => {
                dropdown.classList.remove('active');
            });
        });

        // Prevent menu close when clicking inside dropdown
        document.querySelectorAll('.menu-dropdown').forEach(dropdown => {
            dropdown.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        });
    </script>

</body>
</html>