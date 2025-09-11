<?php
session_start();
require_once('../model/db.php');
$con = getConnection();

if (isset($_POST['measurement'])) {
    $data = json_decode($_POST['measurement'], true);

    $date = trim($data['date']);
    $weight = trim($data['weight']);
    $waist = trim($data['waist']);
    $chest = trim($data['chest']);
    $arms = trim($data['arms']);

    $errors = [];

    if ($date === "") $errors[] = "Date is required.";
    if (!is_numeric($weight) || $weight <= 0) $errors[] = "Invalid weight.";
    if (!is_numeric($waist) || $waist <= 0) $errors[] = "Invalid waist.";
    if (!is_numeric($chest) || $chest <= 0) $errors[] = "Invalid chest.";
    if (!is_numeric($arms) || $arms <= 0) $errors[] = "Invalid arms.";

    if (!empty($errors)) {
        echo json_encode(['success' => false, 'message' => implode(" ", $errors)]);
        mysqli_close($con);
        exit();
    }

    $sql = "INSERT INTO measurements (date, weight, waist, chest, arms) VALUES ('$date', '$weight', '$waist', '$chest', '$arms')";
    if (mysqli_query($con, $sql)) {
        echo json_encode(['success' => true, 'message' => 'Measurement saved successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($con)]);
    }

    mysqli_close($con);
    exit();
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'add') {
    $date = trim($_POST['date']);
    $weight = trim($_POST['weight']);
    $waist = trim($_POST['waist']);
    $chest = trim($_POST['chest']);
    $arms = trim($_POST['arms']);

    $errors = [];

    if ($date === "") $errors['date'] = "Please select a date.";
    if (!is_numeric($weight) || $weight <= 0) $errors['weight'] = "Please enter a valid weight.";
    if (!is_numeric($waist) || $waist <= 0) $errors['waist'] = "Please enter a valid waist measurement.";
    if (!is_numeric($chest) || $chest <= 0) $errors['chest'] = "Please enter a valid chest measurement.";
    if (!is_numeric($arms) || $arms <= 0) $errors['arms'] = "Please enter a valid arms measurement.";

    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header('Location: ../view/measurement.php');
        mysqli_close($con);
        exit();
    }

    $sql = "INSERT INTO measurements (date, weight, waist, chest, arms) VALUES ('$date', '$weight', '$waist', '$chest', '$arms')";
    if (mysqli_query($con, $sql)) {
        $_SESSION['success'] = "Measurements saved successfully!";
        setcookie('last_measurement_date', $date, time() + (86400 * 7), '/');
    } else {
        $_SESSION['form_errors']['general'] = "Database error: " . mysqli_error($con);
        $_SESSION['form_data'] = $_POST;
    }

    header('Location: ../view/measurement.php');
    mysqli_close($con);
    exit();
}

if ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM measurements WHERE id = $id";
    mysqli_query($con, $sql);
    header("Location: ../view/measurement.php");
    mysqli_close($con);
    exit();
}

if ($action === 'edit' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM measurements WHERE id = $id";
    $result = mysqli_query($con, $sql);
    $data = mysqli_fetch_assoc($result);

    echo "<!DOCTYPE html>
    <html>
    <head>
      <title>Edit Measurement</title>
      <link rel='stylesheet' href='../asset/css/measurement.css'>
    </head>
    <body>
      <div class='form-section'>
        <h2>Edit Measurement</h2>
        <form action='measurement_handler.php' method='post'>
          <input type='hidden' name='action' value='update'>
          <input type='hidden' name='id' value='{$data['id']}'>
          <label>Date:</label><input type='date' name='date' value='{$data['date']}'><br>
          <label>Weight:</label><input type='number' name='weight' value='{$data['weight']}'><br>
          <label>Waist:</label><input type='number' name='waist' value='{$data['waist']}'><br>
          <label>Chest:</label><input type='number' name='chest' value='{$data['chest']}'><br>
          <label>Arms:</label><input type='number' name='arms' value='{$data['arms']}'><br>
          <button type='submit'>Update</button>
        </form>
      </div>
    </body>
    </html>";
    mysqli_close($con);
    exit();
}

if ($action === 'update') {
    $id = intval($_POST['id']);
    $date = $_POST['date'];
    $weight = $_POST['weight'];
    $waist = $_POST['waist'];
    $chest = $_POST['chest'];
    $arms = $_POST['arms'];

    $sql = "UPDATE measurements SET 
              date = '$date',
              weight = '$weight',
              waist = '$waist',
              chest = '$chest',
              arms = '$arms'
            WHERE id = $id";

    mysqli_query($con, $sql);
    header("Location: ../view/measurement.php");
    mysqli_close($con);
    exit();
}

mysqli_close($con);
?>
