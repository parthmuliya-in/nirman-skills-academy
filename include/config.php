<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Kolkata');

//160.153.173.128
$conn = mysqli_connect("localhost", "root", "", "nsa");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$conn->query("SET time_zone = '+05:30'");

?>