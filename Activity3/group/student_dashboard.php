<?php
session_start();
include('db_connect.php');

// Redirect if not logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$student_name = $_SESSION['full_name'];

$sql = "SELECT 
            c.course_code,
            c.course_name,
            s.session_date,
            s.session_type,
            s.note,
            a.status
        FROM attendance a
        JOIN sessions s ON a.session_id = s.session_id
        JOIN courses c ON s.course_id = c.course_id
        WHERE a.student_id = ?
        ORDER BY s.session_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 850px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0,0,0,0.2);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        p {
            text-align: center;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background: #007bff;
            color: white;
        }
        .status-present { color: green; font-weight: bold; }
        .status-absent { color: red; font-weight: bold; }
        .status-excused { color: orange; font-weight: bold; }
        .lecture { color: #1e7e34; font-weight: bold; }
        .lab { color: #e67e22; font-weight: bold; }
        .logout-btn {
            text-align: center;
            margin-top: 15px;
        }
        button {
            background: #dc3545;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
        }
        button:hover {
            background: #b52b37;
        }
        textarea {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            resize: vertical;
        }
        .issue-box {
            margin-top: 30px;
            background: #fafafa;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        .issue-box h3 {
            margin-top: 0;
        }
        @media (max-width: 700px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }
            th { display: none; }
            td {
                border: none;
                padding-left: 50%;
                position: relative;
                text-align: right;
            }
            td::before {
                position: absolute;
                left: 10px;
                content: attr(data-label);
                font-weight: bold;
                text-align: left;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Attendance Dashboard</h2>
    <p>Welcome, <strong><?php echo htmlspecialchars($student_name); ?></strong></p>

    <!-- Feedback messages after report submission -->
    <?php
    if (isset($_GET['msg'])) {
        if ($_GET['msg'] === 'success') {
            echo "<p style='color:green; text-align:center;'>Issue reported successfully!</p>";
        } elseif ($_GET['msg'] === 'error') {
            echo "<p style='color:red; text-align:center;'>Error submitting issue. Please try again.</p>";
        } elseif ($_GET['msg'] === 'empty') {
            echo "<p style='color:orange; text-align:center;'>Please enter an issue description.</p>";
        }
    }
    ?>

    <table>
        <thead>
            <tr>
                <th>Course</th>
                <th>Date</th>
                <th>Type</th>
                <th>Status</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $type_class = strtolower($row['session_type']) === 'lab' ? 'lab' : 'lecture';
                $status_class = "status-" . strtolower($row['status']);
                echo "<tr>
                        <td data-label='Course'>{$row['course_code']} - {$row['course_name']}</td>
                        <td data-label='Date'>{$row['session_date']}</td>
                        <td data-label='Type' class='$type_class'>{$row['session_type']}</td>
                        <td data-label='Status' class='$status_class'>{$row['status']}</td>
                        <td data-label='Notes'>{$row['note']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No attendance records found.</td></tr>";
        }
        ?>
        </tbody>
    </table>

    <div class="issue-box">
        <h3>Report Attendance Issue</h3>
        <form method="POST" action="report_issue.php">
            <textarea name="issue_text" rows="4" placeholder="Describe your issue (e.g., missing mark, incorrect status)" required></textarea><br>
            <button type="submit">Submit Issue</button>
        </form>
    </div>

    <div class="logout-btn">
        <form method="POST" action="logout.php">
            <button type="submit">Logout</button>
        </form>
    </div>
</div>

</body>
</html>
