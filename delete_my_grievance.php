<?php

session_start();

if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

include "db.php";

$id = $_POST['id'];
$email = $_SESSION['email'];

// Prepared statement
$sql = "DELETE FROM grievances
        WHERE id=? AND student_email=?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "is",
    $id,
    $email
);

if (mysqli_stmt_execute($stmt)) {

    header("Location: my_grievances.php");
    exit();

} else {

    echo "Error deleting grievance: " . mysqli_error($conn);

}

mysqli_stmt_close($stmt);

?>