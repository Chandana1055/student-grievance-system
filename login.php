<?php

session_start();

include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepared statement
    $sql = "SELECT * FROM students WHERE email=?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "s", $email);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {

        $student = mysqli_fetch_assoc($result);

        // Verify hashed password
        if (password_verify($password, $student['password'])) {

            $_SESSION['student'] = $student['name'];
            $_SESSION['email'] = $student['email'];

            header("Location: dashboard.php");
            exit();

        } else {

            echo "Invalid email or password!";

        }

    } else {

        echo "Invalid email or password!";

    }

    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Student Login</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #f2f4f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            background-color: white;
            width: 350px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #2563eb;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #1d4ed8;
        }

        .register {
            text-align: center;
            margin-top: 20px;
        }

        a {
            color: #2563eb;
            text-decoration: none;
        }

    </style>

</head>

<body>

    <div class="login-box">

        <h1>Student Login</h1>

        <form method="post">

            <label>Email:</label>

            <input type="email"
                   name="email"
                   required>

            <label>Password:</label>

            <input type="password"
                   name="password"
                   required>

            <button type="submit">
                Login
            </button>

        </form>

        <div class="register">

            <p>
                Don't have an account?
                <a href="register.php">Register here</a>
            </p>

        </div>

    </div>

</body>

</html>