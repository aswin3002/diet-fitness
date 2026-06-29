<?php
session_start();
require_once "includes/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $entry_date = $_POST['entry_date'];
    $weight = $_POST['weight'];
    $calories = $_POST['calories'];
    $steps = $_POST['steps'];

    $stmt = $conn->prepare("INSERT INTO progress (user_id, entry_date, weight, calories, steps) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isdii", $user_id, $entry_date, $weight, $calories, $steps);

    if ($stmt->execute()) {
        $message = "✅ Progress data added successfully!";
    } else {
        $message = "❌ Failed to add data: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Progress</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f4f4;
            padding: 30px;
        }
        form {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            max-width: 400px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
        }
        input[type="date"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        button {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
        }
        .message {
            text-align: center;
            color: green;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <form method="POST" action="">
        <h2>Add Daily Progress</h2>
        <?php if ($message): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>
        <label for="entry_date">Date:</label>
        <input type="date" name="entry_date" required>

        <label for="weight">Weight (kg):</label>
        <input type="number" step="0.1" name="weight" required>

        <label for="calories">Calories Burned:</label>
        <input type="number" name="calories" required>

        <label for="steps">Steps:</label>
        <input type="number" name="steps" required>

        <button type="submit">➕ Add Progress</button>
    </form>

</body>
</html>
