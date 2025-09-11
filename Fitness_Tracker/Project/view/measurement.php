<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Measurement</title>
  <link rel="stylesheet" href="../asset/css/measurement.css">
</head>
<body>

  <header>
    <h1>Measurement</h1>
  </header>

  <nav>
    <a href="measurement.php">Measurement</a>
    <a href="progress_photo.html">Photo Album</a>
    <a href="trend.html">Trend Analysis</a>
  </nav>

  <div class="measurement-container">
    <div class="form-section">
      <h2>Enter Your Measurements</h2>
      
      <?php
        if (isset($_SESSION['error'])) {
          echo '<p class="error-message">' . $_SESSION['error'] . '</p>';
          unset($_SESSION['error']);
        }

        if (isset($_SESSION['success'])) {
          echo '<p id="successMsg" class="success-message">' . $_SESSION['success'] . '</p>';
          unset($_SESSION['success']);
        }

        if (isset($_COOKIE['last_measurement_date'])) {
          echo '<p id="lastMeasurementMsg" class="top-highlight">Last measurement was on: ' . htmlspecialchars($_COOKIE['last_measurement_date']) . '</p>';
        }
      ?>

      <form action="../controller/measurement_handler.php" method="post" onsubmit="return validateMeasurement();">
        <input type="hidden" name="action" value="add">
        <label for="date">Date:</label>
        <input type="date" id="date" name="date">
        <p id="dateError" class="error-message"></p>

        <label for="weight">Weight (kg):</label>
        <input type="number" id="weight" name="weight">
        <p id="weightError" class="error-message"></p>

        <label for="waist">Waist (cm):</label>
        <input type="number" id="waist" name="waist">
        <p id="waistError" class="error-message"></p>

        <label for="chest">Chest (cm):</label>
        <input type="number" id="chest" name="chest">
        <p id="chestError" class="error-message"></p>

        <label for="arms">Arms (cm):</label>
        <input type="number" id="arms" name="arms">
        <p id="armsError" class="error-message"></p>

        <button type="submit">Save Measurements</button>
        <p id="measurementMsg" class="success-message"></p>
      </form>
    </div>

    <div class="table-section">
      <h2>Saved Measurements</h2>

      <?php
        require_once('../model/db.php');
        $con = getConnection();
        $sql = "SELECT * FROM measurements ORDER BY date DESC";
        $result = mysqli_query($con, $sql);
      ?>

      <table border="1" cellpadding="10">
        <tr>
          <th>Date</th>
          <th>Weight</th>
          <th>Waist</th>
          <th>Chest</th>
          <th>Arms</th>
          <th>Actions</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
          <tr>
            <td><?php echo htmlspecialchars($row['date']); ?></td>
            <td><?php echo htmlspecialchars($row['weight']); ?></td>
            <td><?php echo htmlspecialchars($row['waist']); ?></td>
            <td><?php echo htmlspecialchars($row['chest']); ?></td>
            <td><?php echo htmlspecialchars($row['arms']); ?></td>
            <td>
              <a href="../controller/measurement_handler.php?action=edit&id=<?php echo $row['id']; ?>">Edit</a> |
              <a href="../controller/measurement_handler.php?action=delete&id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
          </tr>
        <?php } ?>
      </table>
    </div>

  </div>

  <script src="../asset/js/measurement_validate.js"></script>

  <script>
    window.addEventListener("DOMContentLoaded", () => {
      let successMsg = document.getElementById("successMsg");
      if (successMsg) {
        setTimeout(() => {
          successMsg.style.display = "none";
          let url = new URL(window.location);
          url.searchParams.delete("success");
          window.history.replaceState({}, document.title, url.pathname);
        }, 5000);
      }

      let lastMsg = document.getElementById("lastMeasurementMsg");
      if (lastMsg) {
        setTimeout(() => {
          lastMsg.style.display = "none";
        }, 5000);
      }
    });
  </script>

</body>
</html>
