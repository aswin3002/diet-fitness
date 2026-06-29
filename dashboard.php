<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: #f0f4f8;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: 0.3s;
            text-align: center;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card a {
            text-decoration: none;
            color: #3498db;
            font-size: 16px;
            font-weight: bold;
        }
        .logout {
            text-align: right;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logout">
            <a href="logout.php">🚪 Logout</a>
        </div>
        <h1>Welcome to Your Fitness Dashboard 💪</h1>

        <div class="grid">
            <div class="card">
                <h3>👤 Profile & Goals</h3>
                <a href="profile.php">Set Your Goals</a>
            </div>

            <div class="card">
                <h3>🥗 Diet Plan</h3>
                <a href="diet_plan.php">Plan Your Meals</a>
            </div>

            <div class="card">
                <h3>📋 View Diet History</h3>
                <a href="view_diet.php">See Saved Plans</a>
            </div>

            
			<div class="card">
    <h3>?? Workout Tracker</h3>
    <a href="workout.php">Track Your Exercise</a>
</div>
            <div class="card">
                <h3>📊 Progress & Analytics</h3>
                <a href="progress.php">View Progress Charts</a>
				
           </div>
        </div>
    </div>
</body>
</html>
