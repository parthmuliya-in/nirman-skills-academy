<?php
// captcha.php - Simple Image-based CAPTCHA Generator

session_start();

if (!isset($_SESSION['captcha_code'])) {
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $_SESSION['captcha_code'] = substr(str_shuffle($chars), 0, 6);
}

$code = $_SESSION['captcha_code'];

$width = 150;
$height = 50;
$image = imagecreatetruecolor($width, $height);

$bg_color = imagecolorallocate($image, 255, 255, 255);
$text_color = imagecolorallocate($image, 0, 0, 0);
$noise_color = imagecolorallocate($image, 100, 100, 100);

imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);

// Add some noise (lines)
for ($i = 0; $i < 10; $i++) {
    imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $noise_color);
}

// Add the text
$font_size = 20;
$x = 20;
for ($i = 0; $i < strlen($code); $i++) {
    $y = rand(30, 40);
    imagestring($image, 5, $x, $y - 15, $code[$i], $text_color);
    $x += 20;
}

header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);
?>