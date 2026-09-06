<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username == "admin" && $password == "admin123") {

        $_SESSION['admin'] = $username;

        header("Location: admin.php");
        exit();

    } else {

        echo "Invalid admin username or password!";

    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Admin Login</title>

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

        .back {
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

        <h1>Admin Login</h1>

        <form method="post">

            <label>Username:</label>

            <input type="text"
                   name="username"
                   required>

            <label>Password:</label>

            <input type="password"
                   name="password"
                   required>

            <button type="submit">
                Login
            </button>

        </form>

        <div class="back">

            <a href="login.php">
                Back to Student Login
            </a>

        </div>

    </div>

</body>

</html>