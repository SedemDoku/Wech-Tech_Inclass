<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef2f3;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
        }
        .login-box {
            background: white;
            padding: 25px;
            border-radius: 8px;
            width: 300px;
            box-shadow: 0 0 5px rgba(0,0,0,0.2);
        }
        h2 {
            text-align: center;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin: 6px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            background: #902020ff;
            color: white;
            padding: 8px;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            cursor: pointer;
        }
        button:hover {
            background: #df0d0dff;
        }
        p {
            text-align: center;
            color: red;
        }
        .db-message {
            position: absolute;
            top: 10px;
            left: 10px;
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="db-message">
    <?php include('db_connect.php'); ?>
</div>

<div class="login-box">
    <h2>Login</h2>
    <form method="POST">
        <select name="role" required>
            <option value="">-- Select Role --</option>
            <option value="student">Student</option>
            <option value="faculty">Faculty</option>
        </select>
        <input type="email" name="email" placeholder="Enter email" required>
        <input type="password" name="password" placeholder="Enter password" required>
        <button type="submit">Login</button>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            include('db_connect.php');
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $role = $_POST['role'];

            if ($role === "student") {
                $sql = "SELECT * FROM students WHERE email = ?";
            } elseif ($role === "faculty") {
                $sql = "SELECT * FROM faculty WHERE email = ?";
            } else {
                echo "<p>Please select a valid role.</p>";
                exit();
            }

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();

                if (password_verify($password, $user['password'])) {
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['full_name'] = $user['full_name'];

                    if ($role === "student") {
                        $_SESSION['student_id'] = $user['student_id'] ?? $user['id'];
                        header("Location: student_dashboard.php");
                    } else {
                        $_SESSION['faculty_id'] = $user['faculty_id'] ?? $user['id'];
                        header("Location: faculty_dashboard.php");
                    }
                    exit();
                } else {
                    echo "<p>Incorrect password.</p>";
                }
            } else {
                echo "<p>No account found with that email.</p>";
            }
        }
        ?>
    </form>
</div>

</body>
</html>
