<?php

session_start();

if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

include "db.php";

$email = $_SESSION['email'];

// Total grievances
$total_sql = "SELECT COUNT(*) AS total
              FROM grievances
              WHERE student_email='$email'";

// Pending grievances
$pending_sql = "SELECT COUNT(*) AS total
                FROM grievances
                WHERE student_email='$email'
                AND status='Pending'";

// In Progress grievances
$progress_sql = "SELECT COUNT(*) AS total
                 FROM grievances
                 WHERE student_email='$email'
                 AND status='In Progress'";

// Resolved grievances
$resolved_sql = "SELECT COUNT(*) AS total
                 FROM grievances
                 WHERE student_email='$email'
                 AND status='Resolved'";

$total_result = mysqli_query($conn, $total_sql);
$pending_result = mysqli_query($conn, $pending_sql);
$progress_result = mysqli_query($conn, $progress_sql);
$resolved_result = mysqli_query($conn, $resolved_sql);

$total = mysqli_fetch_assoc($total_result)['total'];
$pending = mysqli_fetch_assoc($pending_result)['total'];
$progress = mysqli_fetch_assoc($progress_result)['total'];
$resolved = mysqli_fetch_assoc($resolved_result)['total'];

?>

<!DOCTYPE html>
<html>

<head>

    <title>Student Dashboard</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #f2f4f7;
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 900px;
            margin: auto;
        }

        .header {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            text-align: center;
        }

        .header h1 {
            margin-bottom: 10px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-top: 25px;
        }

        .card {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .card h2 {
            margin: 0;
            font-size: 30px;
        }

        .card p {
            margin-bottom: 0;
            font-weight: bold;
        }

        .actions {
            background-color: white;
            margin-top: 25px;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .actions a {
            display: inline-block;
            padding: 12px 20px;
            margin: 8px;
            background-color: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .actions a:hover {
            background-color: #1d4ed8;
        }

        .logout {
            background-color: #dc2626 !important;
        }

        .logout:hover {
            background-color: #b91c1c !important;
        }

        @media (max-width: 700px) {

            body {
                padding: 20px;
            }

            .cards {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media (max-width: 450px) {

            .cards {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <div class="header">

        <h1>Student Dashboard</h1>

        <p>
            Welcome,
            <strong><?php echo $_SESSION['student']; ?></strong>!
        </p>

    </div>


    <div class="cards">

        <div class="card">

            <h2><?php echo $total; ?></h2>

            <p>Total Grievances</p>

        </div>


        <div class="card">

            <h2><?php echo $pending; ?></h2>

            <p>Pending</p>

        </div>


        <div class="card">

            <h2><?php echo $progress; ?></h2>

            <p>In Progress</p>

        </div>


        <div class="card">

            <h2><?php echo $resolved; ?></h2>

            <p>Resolved</p>

        </div>

    </div>


    <div class="actions">

        <h2>What would you like to do?</h2>

        <a href="grievance.php">
            Submit a Grievance
        </a>

        <a href="my_grievances.php">
            My Grievances
        </a>

        <a href="logout.php" class="logout">
            Logout
        </a>

    </div>

</div>

</body>

</html>