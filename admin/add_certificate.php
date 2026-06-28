<?php
include "header.php";
include '../include/config.php';

if (isset($_POST['generate'])) {

    $name = $_POST['student_name'];
    $course_title = $_POST['course_title'];
    $date = $_POST['certificate_date'];
    $enrollment_id = $_POST['enrollment_id']; // important

    // Load image
    $image = imagecreatefromjpeg("cirtificat.jpg");

    $color = imagecolorallocate($image, 40, 40, 40);
    $font = __DIR__ . "/static/PlayfairDisplay-ExtraBold.ttf";

    // ---------------- NAME ----------------
    $font_size = 60;
    $image_width = imagesx($image);

    $bbox = imagettfbbox($font_size, 0, $font, $name);
    $text_width = $bbox[2] - $bbox[0];

    // $x = ($image_width / 2) - ($text_width / 2);
    $x = (int) (($image_width / 2) - ($text_width / 2));
    $y = 840;

    imagettftext($image, $font_size, 0, $x, $y, $color, $font, $name);

    // ---------------- COURSE TITLE ----------------
    $course_font_size = 40;

    // Center align course title
    $bbox_course = imagettfbbox($course_font_size, 0, $font, $course_title);
    $course_width = $bbox_course[2] - $bbox_course[0];

    $course_x = (int) (($image_width / 2) - ($course_width / 2));
    $course_y = 950; // adjust position as needed

    imagettftext($image, $course_font_size, 0, $course_x, $course_y, $color, $font, $course_title);

    // ---------------- DATE ----------------
    $date_font_size = 35;
    $formatted_date = date("d M Y", strtotime($date));

    $date_x = 620;
    $date_y = 1390;

    imagettftext($image, $date_font_size, 0, $date_x, $date_y, $color, $font, $formatted_date);

    // ---------------- SAVE FILE ----------------
    // $file_name = "certificate_" . time() . ".jpg";
    $file_name = "certificate_" . $enrollment_id . ".jpg";
    // $file_name = "certificate_" . $enrollment_id . "_" . time() . ".jpg";
    $file_path = "../uploads/certificates/" . $file_name;

    imagejpeg($image, $file_path);
    imagedestroy($image);

    // ---------------- SAVE IN DATABASE ----------------
    $stmt = $conn->prepare("UPDATE enrollments SET certificate_file=? WHERE enrollment_no=?");
    $stmt->bind_param("ss", $file_name, $enrollment_id);
    $stmt->execute();

    echo "Certificate Generated Successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Certificate</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css"> <!-- External CSS -->

    <style>
        /* CONTAINER */
        .container {
            max-width: 500px;
            margin: 40px auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* TITLE */
        h2 {
            text-align: center;
            color: #ff6600;
            margin-bottom: 20px;
        }

        /* FORM GROUP */
        .form-group {
            margin-bottom: 15px;
        }

        /* LABEL */
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        /* INPUT */
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            transition: 0.3s;
        }

        input:focus {
            border-color: #ff6600;
            outline: none;
        }

        /* BUTTON */
        button {
            width: 100%;
            padding: 12px;
            background: #ff6600;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #e65c00;
        }

        /* SUCCESS MESSAGE */
        .success {
            background: #e6ffe6;
            color: green;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }

        /* MOBILE */
        @media (max-width: 600px) {
            .container {
                margin: 20px;
                padding: 15px;
            }

            input {
                padding: 10px;
                font-size: 13px;
            }

            button {
                padding: 10px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>



    <div class="container">

        <h2>Add Certificate Details</h2>

        <form method="POST">

            <div class="form-group">
                <label>Student Name</label>
                <input type="text" name="student_name" placeholder="Enter Student Name" required>
            </div>
            
            <div class="form-group">
                <label>Course Title</label>
                <input type="text" name="course_title" placeholder="Enter Course Title" required>
            </div>

            <div class="form-group">
                <label>Certificate Date</label>
                <input type="date" name="certificate_date" required>
            </div>

            <div class="form-group">
                <label>Enrollment Number</label>
                <input type="text" name="enrollment_id" placeholder="Enter Enrollment No" required>
            </div>

            <button type="submit" name="generate">Generate Certificate</button>

        </form>

    </div>

</body>

</html>