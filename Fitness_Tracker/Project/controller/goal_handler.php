<?php
session_start();
require_once('../model/db.php');
$con = getConnection();

$goalType = trim($_POST['goalType']);
$targetValue = trim($_POST['targetValue']);
$deadline = trim($_POST['deadline']);
$description = trim($_POST['description']);

if ($goalType == "" || $targetValue == "" || $deadline == "" || $description == "") {
    $_SESSION['error'] = "Please fill in all fields.";
    header('Location: ../view/goal_creator.php');
    exit();
}

if (!is_numeric($targetValue) || $targetValue <= 0) {
    $_SESSION['error'] = "Target value must be a positive number.";
    header('Location: ../view/goal_creator.php');
    exit();
}

if (strtotime($deadline) <= time()) {
    $_SESSION['error'] = "Deadline must be a future date.";
    header('Location: ../view/goal_creator.php');
    exit();
}

$sql = "INSERT INTO goals VALUES (NULL, '$goalType', '$targetValue', '$deadline', '$description')";

if (mysqli_query($con, $sql)) {
    $_SESSION['success'] = "Goal saved successfully!";
    setcookie('last_goal_type', $goalType, time() + (86400 * 7), '/');
} else {
    $_SESSION['error'] = "Database error: " . mysqli_error($con);
}

mysqli_close($con);
header('Location: ../view/goal_creator.php');
exit();
?>