<?php
session_start();
include 'db_connect.php';

// Redirect to login if not logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_SESSION['student_id'];
    $issue_text = trim($_POST['issue_text']);

    if (!empty($issue_text)) {
        $stmt = $conn->prepare("INSERT INTO issues (student_id, issue_text) VALUES (?, ?)");
        $stmt->bind_param("ss", $student_id, $issue_text);

        if ($stmt->execute()) {
            header("Location: student_dashboard.php?msg=success");
            exit();
        } else {
            header("Location: student_dashboard.php?msg=error");
            exit();
        }

        $stmt->close();
    } else {
        header("Location: student_dashboard.php?msg=empty");
        exit();
    }
}

$conn->close();
?>
