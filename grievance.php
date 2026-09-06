<?php

session_start();

if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Submit Grievance</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #f2f4f7;
            margin: 0;
            padding: 40px;
        }

        .grievance-box {
            background-color: white;
            max-width: 500px;
            margin: auto;
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

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        textarea {
            height: 120px;
            resize: vertical;
        }

        input[readonly] {
            background-color: #eee;
        }

        button {
            width: 100%;
            padding: 11px;
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

    <div class="grievance-box">

        <h1>Submit Grievance</h1>

        <form action="submit_grievance.php" method="post">

            <label>Student Name:</label>

            <input type="text"
                   name="student_name"
                   value="<?php echo $_SESSION['student']; ?>"
                   readonly>

            <label>Email:</label>

            <input type="email"
                   name="student_email"
                   value="<?php echo $_SESSION['email']; ?>"
                   readonly>

            <label>Phone Number:</label>

            <input type="tel"
                   name="phone"
                   required>

            <label>Subject:</label>

            <input type="text"
                   name="subject"
                   required>

            <label>Grievance:</label>

            <textarea name="description"
                      required></textarea>

            <button type="submit">
                Submit Grievance
            </button>

        </form>

        <div class="back">

            <a href="dashboard.php">
                Back to Dashboard
            </a>

        </div>

    </div>

</body>

</html>