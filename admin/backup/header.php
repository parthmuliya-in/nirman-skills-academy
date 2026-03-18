<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }

        .admin-header {
            background: #fff;
            border-bottom: 1px solid #eaeaea;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .admin-container {
            max-width: 1300px;
            margin: auto;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* LOGO */
        .admin-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: bold;
        }

        .admin-logo img {
            width: 38px;
        }

        /* MENU */
        .admin-menu {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .admin-menu a,
        .menu-btn {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* DROPDOWN */
        .menu-dropdown {
            position: relative;
        }

        .menu-list {
            position: absolute;
            top: 38px;
            left: 0;
            background: #fff;
            min-width: 210px;
            border-radius: 10px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
            display: none;
            flex-direction: column;
            overflow: hidden;
        }

        .menu-list a {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            white-space: nowrap;
        }

        .menu-list a:last-child {
            border-bottom: none;
        }

        /* 🔥 IMPORTANT FIX (DESKTOP + MOBILE) */
        .menu-dropdown.active .menu-list {
            display: flex;
        }

        /* ICONS */
        .admin-icons {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logout-btn {
            color: #e63946;
            font-size: 18px;
        }

        .menu-toggle {
            display: none;
            font-size: 22px;
            cursor: pointer;
        }

        .menu-dropdown.active .fa-chevron-down {
            transform: rotate(180deg);
            transition: 0.3s;
        }

        /* ---------------- MOBILE ---------------- */
        @media (max-width: 768px) {

            .menu-toggle {
                display: block;
            }

            .admin-menu {
                position: absolute;
                top: 70px;
                right: 20px;
                background: #fff;
                flex-direction: column;
                width: 260px;
                padding: 15px;
                border-radius: 12px;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
                display: none;
            }

            .admin-menu.active {
                display: flex;
            }

            .menu-list {
                position: static;
                box-shadow: none;
                border-radius: 0;
                margin-top: 8px;
            }
        }
    </style>
</head>

<body>

    <header class="admin-header">
        <div class="admin-container">

            <!-- Logo -->
            <div class="admin-logo">
                <img src="assets/images/logo.png" alt="Logo">
                <span>NSA</span>
            </div>

            <!-- Menu -->
            <nav class="admin-menu" id="adminMenu">

                <a href="index.php">
                    <i class="fa-solid fa-chart-line"></i> Dashboard
                </a>

                <!-- USERS DROPDOWN -->
                <div class="menu-dropdown">
                    <span class="menu-btn">
                        <i class="fa-solid fa-users"></i> Users
                        <i class="fa-solid fa-chevron-down"></i>
                    </span>
                    <div class="menu-list">
                        <a href="manage_users.php">Users</a>
                        <a href="consultations.php">Consultants</a>
                        <a href="contact_us.php">Contacts</a>
                        <a href="subscribers.php">Subscribers</a>
                    </div>
                </div>

                <a href="enrollments.php">
                    <i class="fa-solid fa-user-check"></i> Enroll
                </a>

                <!-- COURSES DROPDOWN -->
                <div class="menu-dropdown">
                    <span class="menu-btn">
                        <i class="fa-solid fa-book"></i> Courses
                        <i class="fa-solid fa-chevron-down"></i>
                    </span>
                    <div class="menu-list">
                        <a href="add_course2.php">Add Courses</a>
                        <a href="view_courses.php">Manage courses</a>
                        <a href="all_course.php">All Courses</a>
                    </div>
                </div>

            </nav>

            <!-- Right Icons -->
            <div class="admin-icons">
                <a href="logout.php" class="logout-btn" title="Logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </a>
                <i class="fa-solid fa-bars menu-toggle" onclick="toggleMenu()"></i>
            </div>

        </div>
    </header>

    <script>
        function toggleMenu() {
            document.getElementById("adminMenu").classList.toggle("active");
        }

        document.querySelectorAll(".menu-btn").forEach(btn => {
            btn.addEventListener("click", function (e) {
                e.stopPropagation();

                let parent = this.parentElement;

                document.querySelectorAll(".menu-dropdown").forEach(drop => {
                    if (drop !== parent) drop.classList.remove("active");
                });

                parent.classList.toggle("active");
            });
        });

        document.addEventListener("click", function () {
            document.querySelectorAll(".menu-dropdown").forEach(drop => {
                drop.classList.remove("active");
            });
        });
    </script>

</body>
</html>
