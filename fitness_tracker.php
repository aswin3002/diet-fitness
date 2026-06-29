<?php
session_start();
require_once "includes/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$uid = $_SESSION['user_id'];
$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $date = $_POST['workout_date'];
    $type = $_POST['workout_type'];
    $duration = $_POST['duration_minutes'];
    $calories = $_POST['calories_burned'];

    $stmt = $conn->prepare("INSERT INTO workouts (user_id, workout_date, workout_type, duration_minutes, calories_burned) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issii", $uid, $date, $type, $duration, $calories);

    if ($stmt->execute()) {
        $msg = "✅ Workout logged!";
    } else {
        $msg = "❌ Error: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Workout Tracker</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>💪 Workout Tracker</h2>
    <form method="post">
        <label>Date:</label>
        <input type="date" name="workout_date" required><br>

        <label>Workout Type:</label>
        <input type="text" name="workout_type" required><br>

        <label>Duration (minutes):</label>
        <input type="number" name="duration_minutes" required><br>

        <label>Calories Burned:</label>
        <input type="number" name="calories_burned" required><br>

        <button type="submit">Log Workout</button>
    </form>
    <p><?= $msg ?></p>
    <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
</div>
</body>
</html>
