<?php
if(isset($_POST['generate'])){

    $name = $_POST['student_name'];
    $date = $_POST['certificate_date']; // ✅ new field

    // Load certificate image
    $image = imagecreatefromjpeg("cirtificat.jpg");

    // Colors
    $name_color = imagecolorallocate($image, 40, 40, 40);
    $date_color = imagecolorallocate($image, 40, 40, 40);

    // Font path
    $font = __DIR__ . "/PlayfairDisplay-ExtraBold.ttf";

    // -----------------------
    // ✅ ADD NAME (CENTER)
    // -----------------------
    $font_size = 60;

    $image_width = imagesx($image);

    $bbox = imagettfbbox($font_size, 0, $font, $name);
    $text_width = $bbox[2] - $bbox[0];

    $x = ($image_width / 2) - ($text_width / 2);
    $y = 840; // your working position

    imagettftext($image, $font_size, 0, $x, $y, $name_color, $font, $name);

    // -----------------------
    // ✅ ADD DATE (LEFT BOTTOM)
    // -----------------------
    $date_font_size = 35;

    // Format date (optional)
    $formatted_date = date("d M Y", strtotime($date));

    // Position (adjust if needed)
    $date_x = 620;   // 🔥 move left/right
    $date_y = 1390;  // 🔥 move up/down

    imagettftext($image, $date_font_size, 0, $date_x, $date_y, $date_color, $font, $formatted_date);

    // Output image
    header("Content-Type: image/jpeg");
    imagejpeg($image);

    imagedestroy($image);
}
?>

<form method="POST" action="">
    <input type="text" name="student_name" placeholder="Enter Student Name" required>
    
    <!-- ✅ Date Input -->
    <input type="date" name="certificate_date" required>
    
    <button type="submit" name="generate">Generate Certificate</button>
</form>