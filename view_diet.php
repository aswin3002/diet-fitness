<?php
require_once "includes/db.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$uid = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM diet_plans WHERE user_id = $uid ORDER BY meal_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Diet Plans</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        th {
            background: #2c3e50;
            color: white;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>🍱 Your Saved Diet Plans</h2>
    
    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Date</th>
                <th>Breakfast</th>
                <th>Lunch</th>
                <th>Dinner</th>
                <th>Snacks</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['meal_date']) ?></td>
                    <td><?= htmlspecialchars($row['breakfast']) ?></td>
                    <td><?= htmlspecialchars($row['lunch']) ?></td>
                    <td><?= htmlspecialchars($row['dinner']) ?></td>
                    <td><?= htmlspecialchars($row['snacks']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No diet plans saved yet.</p>
    <?php endif; ?>

    <a href="diet_plan.php" class="back-link">← Back to Diet Planner</a>
</div>
</body>
</html>
