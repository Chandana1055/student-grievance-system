<?php

include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password != $confirm_password) {

        echo "Passwords do not match!";

    } else {

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepared statement
        $sql = "INSERT INTO students (name, email, password)
                VALUES (?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "sss",
            $name,
            $email,
            $hashed_password
        );

        if (mysqli_stmt_execute($stmt)) {

            header("Location: login.php");
            exit();

        } else {

            echo "Error: " . mysqli_error($conn);

        }

        mysqli_stmt_close($stmt);
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Student Registration</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #f2f4f7;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .register-box {
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

        .login {
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

    <div class="register-box">

        <h1>Student Registration</h1>

        <form method="post">

            <label>Name:</label>

            <input type="text"
                   name="name"
                   required>

            <label>Email:</label>

            <input type="email"
                   name="email"
                   required>

            <label>Password:</label>

            <input type="password"
                   name="password"
                   required>

            <label>Confirm Password:</label>

            <input type="password"
                   name="confirm_password"
                   required>

            <button type="submit">
                Register
            </button>

        </form>

        <div class="login">

            <p>
                Already have an account?
                <a href="login.php">Login here</a>
            </p>

        </div>

    </div>

</body>

</html>