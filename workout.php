<?php
session_start();
require_once "includes/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Insert workout
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['workout_date'];
    $exercise = $_POST['exercise_name'];
    $duration = $_POST['duration'];
    $reps_sets = $_POST['reps_sets'];
    $notes = $_POST['notes'];

    $stmt = $conn->prepare("INSERT INTO workouts (user_id, workout_date, exercise_name, duration, reps_sets, notes) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ississ", $user_id, $date, $exercise, $duration, $reps_sets, $notes);
    
    if ($stmt->execute()) {
        $message = "✅ Workout added!";
    } else {
        $message = "❌ Error: " . $conn->error;
    }
}

// Fetch workouts
$result = $conn->query("SELECT * FROM workouts WHERE user_id = $user_id ORDER BY workout_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Workout Tracker</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; padding: 20px; background: #f8f9fa; }
        h2 { color: #333; }
        form, table { background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.1); margin-bottom: 30px; }
        input, textarea, select { width: 100%; padding: 8px; margin: 8px 0; border-radius: 6px; border: 1px solid #ccc; }
        button { padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 8px; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        tr:hover { background-color: #f1f1f1; }
    </style>
</head>
<body>
    <h2>🏋️ Workout Tracker</h2>
    <?php if ($message) echo "<p>$message</p>"; ?>

    <form method="post">
        <label>Date:</label>
        <input type="date" name="workout_date" required>

        <label>Exercise:</label>
        <select name="exercise_name" required>
            <option>Push-ups</option>
            <option>Squats</option>
            <option>Plank</option>
            <option>Running</option>
            <option>Cycling</option>
            <option>Yoga</option>
        </select>

        <label>Duration (in minutes):</label>
        <input type="number" name="duration">

        <label>Reps/Sets:</label>
        <input type="text" name="reps_sets">

        <label>Notes:</label>
        <textarea name="notes"></textarea>

        <button type="submit">➕ Add Workout</button>
    </form>

    <h3>📜 Workout History</h3>
    <table>
        <tr>
            <th>Date</th>
            <th>Exercise</th>
            <th>Duration</th>
            <th>Reps/Sets</th>
            <th>Notes</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['workout_date']) ?></td>
            <td><?= htmlspecialchars($row['exercise_name']) ?></td>
            <td><?= htmlspecialchars($row['duration']) ?> min</td>
            <td><?= htmlspecialchars($row['reps_sets']) ?></td>
            <td><?= htmlspecialchars($row['notes']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
