<?php

include "db.php";

$id = $_POST['id'];

// Prepared statement
$sql = "DELETE FROM grievances
        WHERE id=?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

if (mysqli_stmt_execute($stmt)) {

    header("Location: admin.php");
    exit();

} else {

    echo "Error deleting grievance: " . mysqli_error($conn);

}

mysqli_stmt_close($stmt);

?>