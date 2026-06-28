<?php
session_start();
include '../include/config.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['student_id'];
// echo $user_id;

// $query = $conn->prepare("
//         SELECT course_title, certificate_file 
//         FROM enrollments 
//         WHERE user_id=? AND certificate_file IS NOT NULL
//     ");
$query = $conn->prepare("
    SELECT course_title, certificate_file 
    FROM enrollments 
    WHERE id=? AND certificate_file IS NOT NULL AND certificate_file != ''
");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Certificates</title>

    <style>
        /* RESET */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* BODY */
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }

        /* HEADER */
        .header {
            background: #ff6600;
            color: #fff;
            text-align: center;
            padding: 16px;
            font-size: 22px;
            font-weight: bold;
        }

        /* CONTAINER */
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px 15px;
        }

        /* GRID (FIXED) */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        /* CARD */
        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: 0.3s;
            display: flex;
            flex-direction: column;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        /* IMAGE (IMPORTANT FIX) */
        .card img {
            width: 100%;
            height: 367px;
            /* fixed preview height */
            object-fit: contain;
            /* 🔥 show full certificate */
            background: #eee;
            /* clean background */
            padding: 10px;
        }

        /* CONTENT */
        .card-body {
            padding: 15px;
            text-align: center;
            flex-grow: 1;
        }

        .card-body p {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #333;
        }

        /* BUTTON */
        .btn {
            display: inline-block;
            width: 100%;
            background: #ff6600;
            color: #fff;
            padding: 10px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            transition: 0.3s;
        }

        .btn:hover {
            background: #e65c00;
        }

        /* NO DATA */
        .no-data {
            text-align: center;
            margin-top: 50px;
            font-size: 18px;
            color: #555;
        }

        /* TABLET */
        @media (max-width: 768px) {
            .header {
                font-size: 18px;
            }

            .card img {
                height: 369px;
            }
        }

        /* MOBILE */
        @media (max-width: 480px) {
            .container {
                padding: 10px;
            }

            .card img {
                height: 304px;
            }

            .card-body p {
                font-size: 14px;
            }

            .btn {
                font-size: 13px;
                padding: 8px;
            }
        }
    </style>
</head>

<body>

    <!-- <div class="header">My Certificates</div> -->

    <div class="container">

        <?php if ($result->num_rows > 0): ?>

            <div class="grid">

                <?php while ($row = $result->fetch_assoc()): ?>

                    <div class="card">
                        <img src="../uploads/certificates/<?php echo $row['certificate_file']; ?>" alt="Certificate">

                        <div class="card-body">
                            <p><?php echo htmlspecialchars($row['course_title']); ?></p>

                            <a href="../uploads/certificates/<?php echo $row['certificate_file']; ?>" download class="btn">
                                Download Certificate
                            </a>
                        </div>
                    </div>

                <?php endwhile; ?>

            </div>

        <?php else: ?>
            <div class="no-data">No certificate available yet.</div>
        <?php endif; ?>

    </div>

</body>

</html>