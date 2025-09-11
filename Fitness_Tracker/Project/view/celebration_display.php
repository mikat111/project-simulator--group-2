<?php
require_once('../model/db.php');
$con = getConnection();

$sql = "SELECT * FROM celebrations ORDER BY date DESC";
$result = mysqli_query($con, $sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Achievements</title>
  <link rel="stylesheet" href="../asset/css/celebration_display.css">
</head>
<body>

  <header>
    <h1>Achievements</h1>
  </header>

  <nav>
    <a href="goal_creator.php">Create Goals</a>
    <a href="progress_tracker.html">Track Progress</a>
    <a href="celebration_display.php">Achievements</a>
  </nav>

  <div class="achievement-section">
    <h2>Completed Goals</h2>

    <div class="trophy-card">
      <h3>Lost 5 kg</h3>
      <p>You reached your weight goal. Amazing discipline!</p>
    </div>

    <div class="trophy-card">
      <h3>30-Day Workout Streak</h3>
      <p>You showed up every day. That's serious commitment!</p>
    </div>

    <div class="trophy-card">
      <h3>Hydration Master</h3>
      <p>Met your water goal for 7 days straight. Cheers to that!</p>
    </div>

    <div class="trophy-card">
      <h3>Sleep Champion</h3>
      <p>Consistent 8-hour sleep for 2 weeks. Rested and ready!</p>
    </div>

  </div>

</body>
</html>
