<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['faculty_id'])) {
    header("Location: login.php");
    exit();
}

$faculty_id = $_SESSION['faculty_id'];

if (isset($_POST['add_session'])) {
    $course_id = $_POST['course_id'];
    $session_date = $_POST['session_date'];
    $session_type = $_POST['session_type'];
    $note = trim($_POST['note']);

    if (!empty($course_id) && !empty($session_date) && !empty($session_type)) {
        $stmt = $conn->prepare("INSERT INTO sessions (course_id, session_date, session_type, note) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $course_id, $session_date, $session_type, $note);
        $stmt->execute();
        $stmt->close();
        echo "<p style='color:green;'>✅ Session added successfully!</p>";
    } else {
        echo "<p style='color:red;'>⚠️ Please fill in all required fields.</p>";
    }
}

if (isset($_POST['delete_session'])) {
    $session_id = $_POST['session_id'];
    $stmt = $conn->prepare("DELETE FROM sessions WHERE session_id = ?");
    $stmt->bind_param("i", $session_id);
    $stmt->execute();
    $stmt->close();
    echo "<p style='color:orange;'>Session deleted successfully.</p>";
}

$stmt = $conn->prepare("SELECT full_name, email FROM faculty WHERE id = ?");
$stmt->bind_param("i", $faculty_id);
$stmt->execute();
$result = $stmt->get_result();
$faculty = $result->fetch_assoc();
$stmt->close();

$sessions = $conn->query("SELECT * FROM sessions ORDER BY session_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Faculty Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; margin: 0; padding: 20px; }
        h2 { color: #333; }
        a.logout { float: right; color: red; text-decoration: none; font-weight: bold; }
        section { background: #fff; padding: 15px; margin: 20px 0; border-radius: 8px; box-shadow: 0 0 4px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; }
        th { background: #efefef; text-align: left; }
        input, select, textarea { width: 100%; padding: 8px; margin: 6px 0; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #902020; color: white; padding: 6px 12px; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #df0d0d; }
    </style>
</head>
<body>

<h2>Welcome, <?php echo htmlspecialchars($faculty['full_name']); ?></h2>
<a href="logout.php" class="logout">Logout</a>

<section>
    <h3>Add New Session</h3>
    <form method="POST">
        <label>Course ID:</label>
        <input type="number" name="course_id" placeholder="Enter course ID" required>

        <label>Date:</label>
        <input type="date" name="session_date" required>

        <label>Session Type:</label>
        <select name="session_type" required>
            <option value="">-- Select Type --</option>
            <option value="lecture">Lecture</option>
            <option value="lab">Lab</option>
        </select>

        <label>Note (optional):</label>
        <textarea name="note" placeholder="Write a short note..."></textarea>

        <button type="submit" name="add_session">Add Session</button>
    </form>
</section>

<section>
    <h3>Existing Sessions</h3>
    <?php if ($sessions->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Course</th>
                <th>Date</th>
                <th>Type</th>
                <th>Note</th>
                <th>Delete</th>
            </tr>
            <?php while ($s = $sessions->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $s['session_id']; ?></td>
                    <td><?php echo htmlspecialchars($s['course_id']); ?></td>
                    <td><?php echo htmlspecialchars($s['session_date']); ?></td>
                    <td><?php echo htmlspecialchars($s['session_type']); ?></td>
                    <td><?php echo htmlspecialchars($s['note']); ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="session_id" value="<?php echo $s['session_id']; ?>">
                            <button type="submit" name="delete_session">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No sessions found.</p>
    <?php endif; ?>
</section>

</body>
</html>
