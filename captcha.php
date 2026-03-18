<?php
session_start();

// Generate new captcha code each time the image is requested
$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
$code = '';
for ($i = 0; $i < 6; $i++) {
    $code .= $chars[rand(0, strlen($chars)-1)];
}
$_SESSION['captcha_code'] = $code;

// Create image
$width = 140;
$height = 50;
$image = imagecreatetruecolor($width, $height);

// Colors
$bg_color = imagecolorallocate($image, 255, 255, 255);
$text_color = imagecolorallocate($image, 20, 20, 20);
$noise_color = imagecolorallocate($image, 150, 150, 150);

// Fill background
imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);

// Add noise lines
for ($i = 0; $i < 8; $i++) {
    imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $noise_color);
}

// Add noise dots
for ($i = 0; $i < 300; $i++) {
    imagesetpixel($image, rand(0, $width), rand(0, $height), $noise_color);
}

// Draw the text
$font = 5;
$text_width = imagefontwidth($font) * strlen($code);
$text_height = imagefontheight($font);
$x = ($width - $text_width) / 2;
$y = ($height - $text_height) / 2 + 5;

imagestring($image, $font, $x, $y, $code, $text_color);

// Output image
header('Content-Type: image/png');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

imagepng($image);
imagedestroy($image);
?>
