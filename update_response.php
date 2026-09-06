<?php

include "db.php";

$id = $_POST['id'];
$admin_response = $_POST['admin_response'];

// Prepared statement
$sql = "UPDATE grievances
        SET admin_response=?
        WHERE id=?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "si",
    $admin_response,
    $id
);

if (mysqli_stmt_execute($stmt)) {

    header("Location: admin.php");
    exit();

} else {

    echo "Error saving response: " . mysqli_error($conn);

}

mysqli_stmt_close($stmt);

?>