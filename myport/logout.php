<?php
// logout.php

session_start();

// Destroy all session data
session_unset();
session_destroy();

// Clear any cookies if you have set them (optional)
setcookie("student_id", "", time() - 3600, "/");
setcookie("student_email", "", time() - 3600, "/");

// Redirect to login page
header("Location: login.php");
exit();
?>