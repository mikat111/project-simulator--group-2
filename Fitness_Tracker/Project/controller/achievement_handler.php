<?php
session_start();
require_once('../model/db.php');
$con = getConnection();

$errors = [];
$title = trim($_POST['title']);
$description = trim($_POST['description']);
$date_earned = trim($_POST['date_earned']);

if ($title === "") {
    $errors['title'] = "Title is required.";
}
if ($description === "") {
    $errors['description'] = "Description is required.";
}
if ($date_earned === "") {
    $errors['date_earned'] = "Date earned is required.";
}

if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    header('Location: ../view/achievement_gallery.php');
    exit();
}

$sql = "INSERT INTO achievements (title, description, date_earned) VALUES ('$title', '$description', '$date_earned')";
if (mysqli_query($con, $sql)) {
    $_SESSION['success'] = "Achievement added successfully!";
    setcookie('last_achievement_title', $title, time() + (86400 * 7), '/');
} else {
    $_SESSION['form_errors']['title'] = "Database error: " . mysqli_error($con);
    $_SESSION['form_data'] = $_POST;
}

mysqli_close($con);
header('Location: ../view/achievement_gallery.php');
exit();
?>
