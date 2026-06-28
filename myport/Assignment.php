<?php
session_start();
include '../include/config.php';

// 🔒 Check login
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id']; // enrollment.id

// ✅ Fetch practices for logged-in student
$query = "SELECT * FROM practices 
          WHERE enrollment_id = ? 
          ORDER BY id DESC";

$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $student_id);

if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Practices</title>

<style>
body{
    font-family: Arial;
    background:#f5f5f5;
}

.container{
    width:90%;
    margin:auto;
    margin-top:40px;
}

h2{
    text-align:center;
    margin-bottom:20px;
}

/* TABLE */
table{
    width:100%;
    border-collapse: collapse;
    background:#fff;
    box-shadow:0 0 10px rgba(0,0,0,0.1);
}

th, td{
    padding:12px;
    border:1px solid #ddd;
    text-align:center;
}

th{
    background:#ff6600;
    color:white;
}

/* STATUS COLORS */
.status{
    padding:5px 10px;
    border-radius:5px;
    color:white;
    font-size:14px;
}

.pending{ background:orange; }
.completed{ background:green; }
.rejected{ background:red; }

.no-data{
    text-align:center;
    padding:20px;
}
</style>

</head>
<body>

<div class="container">

    <h2>My Practices</h2>

    <table>
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Description</th>
            <th>Status</th>
            <th>Date</th>
        </tr>

        <?php
        if ($result && $result->num_rows > 0) {
            $i = 1;

            while ($row = $result->fetch_assoc()) {

                // ✅ Safe output
                $title = htmlspecialchars($row['title']);
                $desc = htmlspecialchars($row['description']);
                $status = strtolower($row['status']);
                $date = date("d M Y", strtotime($row['created_at']));

                // ✅ Dynamic status class
                $statusClass = "pending";
                if ($status == "completed") $statusClass = "completed";
                elseif ($status == "rejected") $statusClass = "rejected";
        ?>

        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo $title; ?></td>
            <td><?php echo $desc; ?></td>
            <td>
                <span class="status <?php echo $statusClass; ?>">
                    <?php echo ucfirst($status); ?>
                </span>
            </td>
            <td><?php echo $date; ?></td>
        </tr>

        <?php
            }
        } else {
            echo "<tr><td colspan='5' class='no-data'>No Practices Found</td></tr>";
        }
        ?>

    </table>

</div>

</body>
</html>