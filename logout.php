<?php
session_start();
include "include/config.php";

if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];

    // Update user offline
    $update = $conn->prepare("UPDATE users SET is_online=0, last_seen=NOW() WHERE id=?");
    $update->bind_param("i", $id);
    $update->execute();
    $update->close();

    session_unset();
    session_destroy();
}

header("Location: index.php");
exit;
