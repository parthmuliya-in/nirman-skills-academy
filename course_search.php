<?php
session_start();
include "include/config.php"; // Adjust path if needed

if (!isset($_GET['q']) || trim($_GET['q']) === '') {
    header("Location: courses.php");
    exit();
}

$search_term = trim($_GET['q']);
$search_term_escaped = mysqli_real_escape_string($conn, $search_term);

$sql = "SELECT id, title, slug FROM courses 
        WHERE title LIKE '%$search_term_escaped%' 
           OR description LIKE '%$search_term_escaped%' 
           OR course_code LIKE '%$search_term_escaped%'
        ORDER BY 
            CASE WHEN title LIKE '$search_term_escaped%' THEN 0 ELSE 1 END,
            title
        LIMIT 10";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // If exact match found (case-insensitive), go directly to detail page
    while ($row = mysqli_fetch_assoc($result)) {
        if (strcasecmp($row['title'], $search_term) === 0) {
            // Use slug if you have one, otherwise use id
            $url = isset($row['slug']) && !empty($row['slug']) 
                ? "course_detail.php?slug=" . urlencode($row['slug'])
                : "course_detail.php?id=" . $row['id'];
            header("Location: $url");
            exit();
        }
    }

    // If no exact match, show search results page (you can create this later)
    // For now, redirect to first result
    mysqli_data_seek($result, 0);
    $first = mysqli_fetch_assoc($result);
    $url = isset($first['slug']) && !empty($first['slug'])
        ? "course_detail.php?slug=" . urlencode($first['slug'])
        : "course_detail.php?id=" . $first['id'];
    header("Location: $url");
    exit();

} else {
    // No results found
    $_SESSION['search_message'] = "No courses found for '$search_term'";
    header("Location: courses.php");
    exit();
}
?>