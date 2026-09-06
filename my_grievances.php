<?php

session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

include "db.php";

$email = $_SESSION['email'];

$sql = "SELECT * FROM grievances
        WHERE student_email='$email'
        ORDER BY created_at DESC";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>My Grievances</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #f2f4f7;
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .grievance-card {
            background-color: white;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .grievance-card p {
            line-height: 1.6;
        }

        .status {
            font-weight: bold;
        }

        .response {
            background-color: #f0f7ff;
            padding: 12px;
            border-left: 4px solid #2563eb;
            margin-top: 10px;
        }

        .buttons {
            margin-top: 20px;
        }

        .edit-btn,
        .delete-btn {
            padding: 8px 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .edit-btn {
            background-color: #2563eb;
            color: white;
        }

        .delete-btn {
            background-color: #dc2626;
            color: white;
        }

        .navigation {
            text-align: center;
            margin-top: 30px;
        }

        .navigation a {
            color: #2563eb;
            text-decoration: none;
            margin: 0 10px;
        }

        .empty {
            background-color: white;
            padding: 25px;
            text-align: center;
            border-radius: 10px;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>My Grievances</h1>

    <?php if (mysqli_num_rows($result) > 0) { ?>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

            <div class="grievance-card">

                <p>
                    <strong>Subject:</strong>
                    <?php echo htmlspecialchars($row['subject']); ?>
                </p>

                <p>
                    <strong>Phone:</strong>
                    <?php echo htmlspecialchars($row['phone']); ?>
                </p>

                <p>
                    <strong>Grievance:</strong>
                    <?php echo htmlspecialchars($row['description']); ?>
                </p>

                <p>
                    <strong>Status:</strong>

                    <span class="status">
                        <?php echo htmlspecialchars($row['status']); ?>
                    </span>
                </p>

                <div class="response">

                    <strong>Admin Response:</strong>

                    <br>

                    <?php

                    if (!empty($row['admin_response'])) {

                        echo htmlspecialchars($row['admin_response']);

                    } else {

                        echo "No response yet.";

                    }

                    ?>

                </div>

                <p>
                    <strong>Date:</strong>
                    <?php echo htmlspecialchars($row['created_at']); ?>
                </p>

                <div class="buttons">

                    <?php if ($row['status'] != 'Resolved') { ?>

                        <a class="edit-btn"
                           href="edit_grievance.php?id=<?php echo $row['id']; ?>">
                            Edit
                        </a>

                    <?php } ?>

                    <form action="delete_my_grievance.php"
                          method="post"
                          style="display:inline;">

                        <input type="hidden"
                               name="id"
                               value="<?php echo $row['id']; ?>">

                        <button class="delete-btn"
                                type="submit"
                                onclick="return confirm('Are you sure you want to delete this grievance?');">
                            Delete
                        </button>

                    </form>

                </div>

            </div>

        <?php } ?>

    <?php } else { ?>

        <div class="empty">

            <p>You have not submitted any grievances yet.</p>

        </div>

    <?php } ?>

    <div class="navigation">

        <a href="grievance.php">
            Submit New Grievance
        </a>

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="logout.php">
            Logout
        </a>

    </div>

</div>

</body>

</html>