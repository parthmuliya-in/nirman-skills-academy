<?php
include "../include/config.php";
include "header.php";
$message = "";  

// FETCH STUDENTS
$students = $conn->query("SELECT id, student_name, enrollment_no FROM enrollments ORDER BY student_name ASC");

// INSERT PRACTICE
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $enrollment_id = $_POST['enrollment_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO practices (enrollment_id, title, description, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $enrollment_id, $title, $description, $status);

    if ($stmt->execute()) {
        $message = "✅ Practice added successfully";
    } else {
        $message = "❌ Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Practice</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css"> <!-- External CSS -->

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }


        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #ff6600;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 12px;
            margin-top: 6px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #ff6600;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #ff6600;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #e65c00;
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }

        /* SEARCH BOX */
        #studentList {
            border: 1px solid #ccc;
            max-height: 150px;
            overflow-y: auto;
            display: none;
            background: #fff;
            position: absolute;
            width: 100%;
            border-radius: 8px;
            z-index: 1000;
        }

        #studentList div {
            padding: 10px;
            cursor: pointer;
        }

        #studentList div:hover {
            background: #f0f8ff;
            color: #ff6600;
        }

        .search-box {
            position: relative;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .container {
                margin: 20px;
                padding: 20px;
            }

            h2 {
                font-size: 22px;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <h2>Add Practice</h2>

        <?php if ($message)
            echo "<div class='message'>$message</div>"; ?>

        <form method="POST">

            <!-- SEARCH STUDENT -->
            <label>Search Student</label>
            <div class="search-box">
                <input type="text" id="searchStudent" placeholder="Type name or enrollment no">
                <div id="studentList">
                    <?php while ($row = $students->fetch_assoc()): ?>
                        <div
                            onclick="selectStudent('<?= $row['id']; ?>','<?= $row['student_name']; ?> (<?= $row['enrollment_no']; ?>)')">
                            <?= $row['student_name']; ?> (<?= $row['enrollment_no']; ?>)
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <input type="hidden" name="enrollment_id" id="selectedStudent" required>

            <!-- TITLE -->
            <label>Practice Title</label>
            <input type="text" name="title" required>

            <!-- DESCRIPTION -->
            <label>Description</label>
            <textarea name="description" rows="4"></textarea>

            <!-- STATUS -->
            <label>Status</label>
            <select name="status">
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
            </select>

            <button type="submit">Add Practice</button>

        </form>
    </div>

    <script>
        const searchInput = document.getElementById("searchStudent");
        const list = document.getElementById("studentList");

        searchInput.addEventListener("keyup", function () {
            let filter = this.value.toLowerCase();
            let items = list.getElementsByTagName("div");

            list.style.display = "block";

            for (let i = 0; i < items.length; i++) {
                let txt = items[i].innerText.toLowerCase();
                items[i].style.display = txt.includes(filter) ? "" : "none";
            }
        });

        function selectStudent(id, text) {
            document.getElementById("selectedStudent").value = id;
            document.getElementById("searchStudent").value = text;
            list.style.display = "none";
        }

        // close dropdown
        document.addEventListener("click", function (e) {
            if (!e.target.closest(".search-box")) {
                list.style.display = "none";
            }
        });
    </script>

</body>

</html>