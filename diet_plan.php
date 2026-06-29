<?php
require_once "includes/db.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$uid = $_SESSION['user_id'];
$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $date = $_POST['meal_date'];
    $breakfast = $_POST['breakfast'];
    $lunch = $_POST['lunch'];
    $dinner = $_POST['dinner'];
    $snacks = $_POST['snacks'];

    $stmt = $conn->prepare("INSERT INTO diet_plans (user_id, meal_date, breakfast, lunch, dinner, snacks) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $uid, $date, $breakfast, $lunch, $dinner, $snacks);

    if ($stmt->execute()) {
        $msg = "? Diet plan saved!";
    } else {
        $msg = "? Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Diet Planning</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>?? Plan Your Diet</h2>
    <form method="post">
        <label>Date:</label>
        <input type="date" name="meal_date" required><br>

        <label>Breakfast:</label>
        <input type="text" name="breakfast"><br>

        <label>Lunch:</label>
        <input type="text" name="lunch"><br>

        <label>Dinner:</label>
        <input type="text" name="dinner"><br>

        <label>Snacks:</label>
        <input type="text" name="snacks"><br>

        <button type="submit">? Save Diet</button>
    </form>
    <p><?= $msg ?></p>
    <a href="dashboard.php" class="back-link">? Back to Dashboard</a>
</div>
</body>
</html>
