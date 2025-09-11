<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Goal Creator</title>
  <link rel="stylesheet" href="../asset/css/goal_creator.css">
</head>
<body>

  <header>
    <h1>Smart Goals</h1>
  </header>

  <nav>
    <a href="goal_creator.php">Create Goals</a>
    <a href="progress_tracker.html">Track Progress</a>
    <a href="celebration_display.php">Achievements</a>
  </nav>

  <div class="goal-form-section">
    <h2>Set Your SMART Goal</h2>
  <?php
    session_start();

    if (isset($_SESSION['success'])) {
      echo '<p class="success-message">' . $_SESSION['success'] . '</p>';
      unset($_SESSION['success']);
    }

   
    if (isset($_SESSION['error'])) {
      echo '<p class="error-message">' . $_SESSION['error'] . '</p>';
      unset($_SESSION['error']);
    }

    if (isset($_COOKIE['last_goal_type'])) {
      echo '<p class="info-message">Last goal type you created: ' . htmlspecialchars($_COOKIE['last_goal_type']) . '</p>';
    }
  ?>



    <form action="../controller/goal_handler.php" method="post" onsubmit="return validateGoal()">

      <label for="goalType">Goal Type:</label>
      <select id="goalType" name="goalType">
        <option value="weight">Lose Weight</option>
        <option value="steps">Increase Steps</option>
        <option value="workouts">Workout Frequency</option>
        <option value="water">Drink More Water</option>
        <option value="sleep">Improve Sleep</option>
      </select><br><br>

      <label for="targetValue">Target Value:</label>
      <input type="number" id="targetValue" name="targetValue" placeholder="e.g. 5 (kg or liters)">
      <p id="targetError" class="error-message"></p>


      <label for="deadline">Deadline:</label>
      <input type="date" id="deadline" name="deadline">
      <p id="deadlineError" class="error-message"></p>

      <label for="description">Goal Description:</label>
      <textarea id="description" name="description" rows="4" placeholder="Describe your goal..."></textarea>
      <p id="descriptionError" class="error-message"></p>

      <button type="submit">Set Goal</button>
       <p id="goalMsg"></p>

    </form>
  </div>

  <script src="../asset/js/goal_validate.js"></script>

  <script>
    if (window.location.search.includes("success=goal_validated")) {
      setTimeout(() => {
        window.history.replaceState({}, document.title, window.location.pathname);
      }, 3000);
    }
  </script>



</body>
</html>
