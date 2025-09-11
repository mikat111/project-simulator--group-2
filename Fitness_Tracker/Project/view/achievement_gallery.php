<?php
session_start();
require_once('../model/db.php');
$con = getConnection();

$formData = $_SESSION['form_data'] ?? [];
$formErrors = $_SESSION['form_errors'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Achievement Gallery</title>
  <link rel="stylesheet" href="../asset/css/achievement_gallery.css">
</head>
<body>

  <header>
    <h1>Achievement Gallery</h1>
  </header>

  <nav>
    <a href="metrics_dashboard.html">Dashboard</a>
    <a href="trend_graph.html">Trend Graph</a>
    <a href="achievement_gallery.php">Achievements</a>
  </nav>

  <div class="achievement-form">
    <h2>Add New Achievement</h2>

    <?php if (isset($_SESSION['success'])): ?>
      <p class="success-message"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <?php if (isset($_COOKIE['last_achievement_title'])): ?>
      <p class="info-message">Last achievement you added: <?php echo htmlspecialchars($_COOKIE['last_achievement_title']); ?></p>
    <?php endif; ?>

    <form action="../controller/achievement_handler.php" method="post" onsubmit="return validateAchievement()">
      <label for="title">Title:</label>
      <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($formData['title'] ?? ''); ?>">
      <p id="titleError" class="error-message"><?php echo $formErrors['title'] ?? ''; ?></p>

      <label for="description">Description:</label>
      <textarea name="description" id="description" rows="4"><?php echo htmlspecialchars($formData['description'] ?? ''); ?></textarea>
      <p id="descriptionError" class="error-message"><?php echo $formErrors['description'] ?? ''; ?></p>

      <label for="date_earned">Date Earned:</label>
      <input type="date" name="date_earned" id="date_earned" value="<?php echo htmlspecialchars($formData['date_earned'] ?? ''); ?>">
      <p id="dateError" class="error-message"><?php echo $formErrors['date_earned'] ?? ''; ?></p>

      <input type="submit" value="Add Achievement">
    </form>
  </div>

  <?php
  unset($_SESSION['form_errors']);
  unset($_SESSION['form_data']);
  ?>

  <div class="gallery">
    <?php
    $sql = "SELECT * FROM achievements ORDER BY date_earned DESC";
    $result = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
      echo '<div class="achievement-card">';
      echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
      echo '<p>' . htmlspecialchars($row['description']) . '</p>';
      echo '</div>';
    }
    mysqli_close($con);
    ?>
  </div>

  <script src="../asset/js/achievement_validate.js"></script>
</body>
</html>
