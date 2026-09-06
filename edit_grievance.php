<?php

session_start();

if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

include "db.php";

$id = $_GET['id'];
$email = $_SESSION['email'];

// Get the grievance using prepared statement
$sql = "SELECT * FROM grievances
        WHERE id=? AND student_email=?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "is", $id, $email);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) != 1) {
    die("Grievance not found!");
}

$grievance = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


// Prevent editing if grievance is resolved
if ($grievance['status'] == 'Resolved') {
    die("You cannot edit a resolved grievance!");
}


// Update grievance
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $subject = $_POST['subject'];
    $description = $_POST['description'];
    $phone = $_POST['phone'];

    // Prepared statement for update
    $update_sql = "UPDATE grievances
                   SET phone=?,
                       subject=?,
                       description=?
                   WHERE id=?
                   AND student_email=?";

    $update_stmt = mysqli_prepare($conn, $update_sql);

    mysqli_stmt_bind_param(
        $update_stmt,
        "sssis",
        $phone,
        $subject,
        $description,
        $id,
        $email
    );

    if (mysqli_stmt_execute($update_stmt)) {

        header("Location: my_grievances.php");
        exit();

    } else {

        echo "Error updating grievance: " . mysqli_error($conn);

    }

    mysqli_stmt_close($update_stmt);
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Edit Grievance</title>

</head>

<body>

    <h1>Edit Grievance</h1>

    <form method="post">

        <label>Phone Number:</label><br>

        <input type="tel"
               name="phone"
               value="<?php echo htmlspecialchars($grievance['phone']); ?>"
               required>

        <br><br>

        <label>Subject:</label><br>

        <input type="text"
               name="subject"
               value="<?php echo htmlspecialchars($grievance['subject']); ?>"
               required>

        <br><br>

        <label>Grievance:</label><br>

        <textarea name="description"
                  required><?php echo htmlspecialchars($grievance['description']); ?></textarea>

        <br><br>

        <button type="submit">
            Update Grievance
        </button>

    </form>

    <br>

    <a href="my_grievances.php">
        Back to My Grievances
    </a>

</body>

</html>