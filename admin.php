<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

include "db.php";

$search = "";
$status = "";

if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

if (isset($_GET['status'])) {
    $status = $_GET['status'];
}


// Count grievances
$total_sql = "SELECT COUNT(*) AS total FROM grievances";

$pending_sql = "SELECT COUNT(*) AS total
                FROM grievances
                WHERE status='Pending'";

$progress_sql = "SELECT COUNT(*) AS total
                 FROM grievances
                 WHERE status='In Progress'";

$resolved_sql = "SELECT COUNT(*) AS total
                 FROM grievances
                 WHERE status='Resolved'";


$total_result = mysqli_query($conn, $total_sql);
$pending_result = mysqli_query($conn, $pending_sql);
$progress_result = mysqli_query($conn, $progress_sql);
$resolved_result = mysqli_query($conn, $resolved_sql);


$total = mysqli_fetch_assoc($total_result)['total'];
$pending = mysqli_fetch_assoc($pending_result)['total'];
$progress = mysqli_fetch_assoc($progress_result)['total'];
$resolved = mysqli_fetch_assoc($resolved_result)['total'];


// Get grievances using prepared statement
$sql = "SELECT * FROM grievances
        WHERE (student_name LIKE ?
        OR student_email LIKE ?)";

$search_value = "%" . $search . "%";

if ($status != "") {

    $sql .= " AND status=?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "sss",
        $search_value,
        $search_value,
        $status
    );

} else {

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ss",
        $search_value,
        $search_value
    );
}

$sql .= " ORDER BY created_at DESC";

// Re-prepare because ORDER BY was added
if ($status != "") {

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "sss",
        $search_value,
        $search_value,
        $status
    );

} else {

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ss",
        $search_value,
        $search_value
    );
}

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Admin Dashboard</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #f2f4f7;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 1200px;
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
            margin: 0;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-top: 25px;
        }

        .card {
            background-color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .card h2 {
            font-size: 30px;
            margin: 0;
        }

        .card p {
            font-weight: bold;
        }

        .search-box {
            background-color: white;
            padding: 20px;
            margin-top: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .search-box input,
        .search-box select {
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-box button {
            padding: 10px 18px;
            background-color: #2563eb;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-box button:hover {
            background-color: #1d4ed8;
        }

        .table-box {
            background-color: white;
            margin-top: 25px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

        th {
            background-color: #2563eb;
            color: white;
            padding: 12px;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
        }

        tr:hover {
            background-color: #f8fafc;
        }

        textarea {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: vertical;
        }

        select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .update-btn,
        .response-btn,
        .delete-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            margin-top: 5px;
        }

        .update-btn {
            background-color: #2563eb;
        }

        .response-btn {
            background-color: #16a34a;
        }

        .delete-btn {
            background-color: #dc2626;
        }

        .update-btn:hover {
            background-color: #1d4ed8;
        }

        .response-btn:hover {
            background-color: #15803d;
        }

        .delete-btn:hover {
            background-color: #b91c1c;
        }

        .navigation {
            text-align: center;
            margin-top: 25px;
        }

        .navigation a {
            text-decoration: none;
            color: #2563eb;
            margin: 0 10px;
        }

        @media (max-width: 700px) {

            body {
                padding: 15px;
            }

            .summary {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media (max-width: 450px) {

            .summary {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <div class="header">

        <h1>Admin Dashboard</h1>

        <p>
            Manage Student Grievances
        </p>

    </div>


    <!-- Summary -->

    <div class="summary">

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


    <!-- Search -->

    <div class="search-box">

        <form method="get">

            <input type="text"
                   name="search"
                   placeholder="Search student or email"
                   value="<?php echo htmlspecialchars($search); ?>">

            <select name="status">

                <option value="">
                    All Status
                </option>

                <option value="Pending"
                    <?php if ($status == "Pending") echo "selected"; ?>>
                    Pending
                </option>

                <option value="In Progress"
                    <?php if ($status == "In Progress") echo "selected"; ?>>
                    In Progress
                </option>

                <option value="Resolved"
                    <?php if ($status == "Resolved") echo "selected"; ?>>
                    Resolved
                </option>

            </select>

            <button type="submit">
                Search
            </button>

        </form>

    </div>


    <!-- Grievances -->

    <div class="table-box">

        <h2>All Grievances</h2>

        <table>

            <tr>

                <th>ID</th>
                <th>Student</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Subject</th>
                <th>Grievance</th>
                <th>Status</th>
                <th>Admin Response</th>
                <th>Action</th>

            </tr>


            <?php while ($row = mysqli_fetch_assoc($result)) { ?>

            <tr>

                <td>
                    <?php echo $row['id']; ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row['student_name']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row['student_email']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row['phone']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row['subject']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row['description']); ?>
                </td>

                <td>

                    <form action="update_status.php"
                          method="post">

                        <input type="hidden"
                               name="id"
                               value="<?php echo $row['id']; ?>">

                        <select name="status">

                            <option value="Pending"
                                <?php if ($row['status'] == 'Pending') echo 'selected'; ?>>
                                Pending
                            </option>

                            <option value="In Progress"
                                <?php if ($row['status'] == 'In Progress') echo 'selected'; ?>>
                                In Progress
                            </option>

                            <option value="Resolved"
                                <?php if ($row['status'] == 'Resolved') echo 'selected'; ?>>
                                Resolved
                            </option>

                        </select>

                        <br>

                        <button class="update-btn"
                                type="submit">
                            Update
                        </button>

                    </form>

                </td>


                <td>

                    <form action="update_response.php"
                          method="post">

                        <input type="hidden"
                               name="id"
                               value="<?php echo $row['id']; ?>">

                        <textarea name="admin_response"
                                  rows="4"
                                  cols="25"
                                  placeholder="Enter response"><?php echo htmlspecialchars($row['admin_response']); ?></textarea>

                        <br>

                        <button class="response-btn"
                                type="submit">
                            Save Response
                        </button>

                    </form>

                </td>


                <td>

                    <form action="delete_grievance.php"
                          method="post">

                        <input type="hidden"
                               name="id"
                               value="<?php echo $row['id']; ?>">

                        <button class="delete-btn"
                                type="submit"
                                onclick="return confirm('Are you sure you want to delete this grievance?');">
                            Delete
                        </button>

                    </form>

                </td>

            </tr>

            <?php } ?>

        </table>

    </div>


    <div class="navigation">

        <a href="admin.php">
            Dashboard
        </a>

        <a href="admin_logout.php">
            Logout
        </a>

    </div>

</div>

</body>

</html>