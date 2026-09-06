<?php

include "db.php";

$id = $_POST['id'];
$status = $_POST['status'];

// Prepared statement
$sql = "UPDATE grievances
        SET status=?
        WHERE id=?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "si",
    $status,
    $id
);

if (mysqli_stmt_execute($stmt)) {

    header("Location: admin.php");
    exit();

} else {

    echo "Error updating status: " . mysqli_error($conn);

}

mysqli_stmt_close($stmt);

?>