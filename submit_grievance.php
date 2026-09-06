<?php

include "db.php";

$name = $_POST['student_name'];
$email = $_POST['student_email'];
$phone = $_POST['phone'];
$subject = $_POST['subject'];
$description = $_POST['description'];

// Prepared statement
$sql = "INSERT INTO grievances
        (student_name, student_email, phone, subject, description)
        VALUES (?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "sssss",
    $name,
    $email,
    $phone,
    $subject,
    $description
);

if (mysqli_stmt_execute($stmt)) {

    echo "Grievance submitted successfully!";

} else {

    echo "Error: " . mysqli_error($conn);

}

mysqli_stmt_close($stmt);

?>