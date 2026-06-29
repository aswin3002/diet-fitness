<?php
session_start();
require_once "includes/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current data
$stmt = $conn->prepare("SELECT username, email, weight, height, target_weight, calorie_goal, goal_type, activity_level FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

function calculateBMI($weightKg, $heightCm) {
    if ($weightKg > 0 && $heightCm > 0) {
        $heightM = $heightCm / 100;
        $bmi = $weightKg / ($heightM * $heightM);
        return round($bmi, 1);
    }
    return null;
}

function getBMICategory($bmi) {
    if ($bmi < 18.5) return "Underweight";
    elseif ($bmi < 25) return "Normal";
    elseif ($bmi < 30) return "Overweight";
    else return "Obese";
}

$bmi = calculateBMI($user['weight'], $user['height']);
$bmiCategory = $bmi !== null ? getBMICategory($bmi) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $weight = floatval($_POST['weight']);
    $height = floatval($_POST['height']);
    $target_weight = floatval($_POST['target_weight']);
    $calorie_goal = intval($_POST['calorie_goal']);
    $goal_type = $_POST['goal_type'];
    $activity_level = $_POST['activity_level'];

    $updateStmt = $conn->prepare("UPDATE users SET weight=?, height=?, target_weight=?, calorie_goal=?, goal_type=?, activity_level=? WHERE id=?");
    $updateStmt->bind_param("dddiisi", $weight, $height, $target_weight, $calorie_goal, $goal_type, $activity_level, $user_id);

    $updateStmt->execute();

    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile & Goals</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: #f0f4f8;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
        }

        .form-container {
            max-width: 500px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
        }

        form label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        button {
            background: #3498db;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            margin-top: 20px;
            width: 100%;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #2980b9;
        }

        h3 {
            text-align: center;
            margin-top: 20px;
            color: #34495e;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>?? Profile & Goals</h2>

        <form method="post">
            <label>Current Weight (kg):</label>
            <input type="number" name="weight" step="0.1" value="<?= htmlspecialchars($user['weight']) ?>" required>

            <label>Height (cm):</label>
            <input type="number" name="height" step="0.1" value="<?= htmlspecialchars($user['height']) ?>" required>

            <label>Target Weight (kg):</label>
            <input type="number" name="target_weight" step="0.1" value="<?= htmlspecialchars($user['target_weight']) ?>">

            <label>Daily Calorie Goal (kcal):</label>
            <input type="number" name="calorie_goal" value="<?= htmlspecialchars($user['calorie_goal']) ?>">

            <label>Fitness Goal:</label>
            <select name="goal_type">
                <option value="">--Select--</option>
                <option value="Lose Weight" <?= $user['goal_type'] == 'Lose Weight' ? 'selected' : '' ?>>Lose Weight</option>
                <option value="Build Muscle" <?= $user['goal_type'] == 'Build Muscle' ? 'selected' : '' ?>>Build Muscle</option>
                <option value="Stay Fit" <?= $user['goal_type'] == 'Stay Fit' ? 'selected' : '' ?>>Stay Fit</option>
            </select>

            <label>Activity Level:</label>
            <select name="activity_level">
                <option value="">--Select--</option>
                <option value="Sedentary" <?= $user['activity_level'] == 'Sedentary' ? 'selected' : '' ?>>Sedentary</option>
                <option value="Lightly Active" <?= $user['activity_level'] == 'Lightly Active' ? 'selected' : '' ?>>Lightly Active</option>
                <option value="Moderately Active" <?= $user['activity_level'] == 'Moderately Active' ? 'selected' : '' ?>>Moderately Active</option>
                <option value="Very Active" <?= $user['activity_level'] == 'Very Active' ? 'selected' : '' ?>>Very Active</option>
            </select>

            <button type="submit">Save & Calculate BMI</button>
        </form>

        <h3>Your BMI: <?= $bmi !== null ? $bmi : 'N/A' ?> 
        <?= $bmi !== null ? "($bmiCategory)" : '' ?></h3>
    </div>
</body>
</html>
