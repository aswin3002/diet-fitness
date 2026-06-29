<?php
session_start();
require_once "includes/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$selected_date = date("Y-m-d");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_date = $_POST['entry_date'];
}

// Fetch progress data for selected date
$stmt = $conn->prepare("SELECT weight, calories, steps FROM progress WHERE user_id = ? AND entry_date = ?");
$stmt->bind_param("is", $user_id, $selected_date);
$stmt->execute();
$result = $stmt->get_result();
$progress = $result->fetch_assoc();

// Fetch diet plan for selected date
$stmt2 = $conn->prepare("SELECT breakfast, lunch, dinner, snacks FROM diet_plans WHERE user_id = ? AND meal_date = ?");
$stmt2->bind_param("is", $user_id, $selected_date);
$stmt2->execute();
$result2 = $stmt2->get_result();
$diet = $result2->fetch_assoc();

function estimateCalories($meal) {
    return $meal ? 250 : 0; // Placeholder logic
}

$calories_consumed = 0;
if ($diet) {
    $calories_consumed += estimateCalories($diet['breakfast']);
    $calories_consumed += estimateCalories($diet['lunch']);
    $calories_consumed += estimateCalories($diet['dinner']);
    $calories_consumed += estimateCalories($diet['snacks']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Progress & Analytics</title>

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<style>
  body {
    background: #e9f0f7;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0; padding: 20px;
  }
  .container {
    max-width: 700px;
    margin: 30px auto;
    background: #fff;
    border-radius: 15px;
    padding: 30px 40px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
  }
  h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #2c3e50;
    font-weight: 700;
  }
  form {
    text-align: center;
    margin-bottom: 30px;
  }
  input[type="text"] {
    padding: 10px 15px;
    font-size: 1rem;
    border: 2px solid #3498db;
    border-radius: 8px;
    width: 180px;
    transition: border-color 0.3s ease;
  }
  input[type="text"]:focus {
    border-color: #2980b9;
    outline: none;
  }
  button {
    background-color: #3498db;
    color: white;
    border: none;
    font-size: 1rem;
    padding: 11px 22px;
    border-radius: 8px;
    cursor: pointer;
    margin-left: 15px;
    transition: background-color 0.3s ease;
  }
  button:hover {
    background-color: #2980b9;
  }
  .summary {
    text-align: center;
    margin-bottom: 20px;
    font-size: 1.1rem;
    color: #34495e;
  }
  canvas {
    display: block;
    margin: 0 auto;
    border-radius: 12px;
    background: #fefefe;
    padding: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
  }
  @media (max-width: 480px) {
    .container {
      padding: 20px;
    }
    input[type="text"] {
      width: 140px;
    }
  }
</style>
</head>
<body>

<div class="container">
  <h2>📊 Your Fitness Progress</h2>

  <form method="POST">
    <input type="text" id="datepicker" name="entry_date" value="<?= htmlspecialchars($selected_date) ?>" required />
    <button type="submit">View</button>
  </form>

  <div class="summary">
    <strong>Date:</strong> <?= htmlspecialchars($selected_date) ?><br />
    <strong>Weight:</strong> <?= isset($progress['weight']) ? $progress['weight'] . ' kg' : 'No data' ?> |
    <strong>Calories Burned:</strong> <?= isset($progress['calories']) ? $progress['calories'] : 'No data' ?> |
    <strong>Calories Consumed:</strong> <?= $calories_consumed ? $calories_consumed : 'No data' ?> |
    <strong>Steps:</strong> <?= isset($progress['steps']) ? $progress['steps'] : 'No data' ?>
  </div>

  <canvas id="progressChart" height="400"></canvas>
</div>

<script>
  flatpickr("#datepicker", {
    dateFormat: "Y-m-d",
    maxDate: "today"
  });

  const ctx = document.getElementById('progressChart').getContext('2d');
  const progressChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Weight (kg)', 'Calories Burned', 'Calories Consumed', 'Steps'],
      datasets: [{
        label: 'Progress on <?= $selected_date ?>',
        data: [
          <?= isset($progress['weight']) ? $progress['weight'] : 0 ?>,
          <?= isset($progress['calories']) ? $progress['calories'] : 0 ?>,
          <?= $calories_consumed ?>,
          <?= isset($progress['steps']) ? $progress['steps'] : 0 ?>
        ],
        backgroundColor: [
          'rgba(75, 192, 192, 0.7)',  // weight
          'rgba(255, 99, 132, 0.7)',  // calories burned
          'rgba(54, 162, 235, 0.7)',  // calories consumed
          'rgba(255, 206, 86, 0.7)'   // steps
        ],
        borderColor: [
          'rgb(75, 192, 192)',
          'rgb(255, 99, 132)',
          'rgb(54, 162, 235)',
          'rgb(255, 206, 86)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 10
          }
        }
      },
      plugins: {
        legend: {
          display: false
        }
      }
    }
  });
</script>

</body>
</html>
