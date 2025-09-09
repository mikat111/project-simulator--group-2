<?php


function processAuth(&$currentForm, &$errors, &$success) {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") return;

    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm  = trim($_POST['confirm'] ?? '');
    $code     = trim($_POST['code'] ?? '');

    if ($currentForm === "login") {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Enter a valid email!";
        if (strlen($password) < 6) $errors['password'] = "Password must be 6+ chars!";
        if (!$errors) { $success = "Login Successful"; $_POST = []; }
    } elseif ($currentForm === "signup") {
        if ($name === "") $errors['name'] = "Full name required!";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Enter a valid email!";
        if (strlen($password) < 6) $errors['password'] = "Password must be 6+ chars!";
        if ($confirm === "" || $confirm !== $password) $errors['confirm'] = "Passwords do not match!";
        if (!$errors) { $success = "Signup Successful! Please verify email."; $currentForm = "verify"; $_POST = []; }
    } elseif ($currentForm === "forgot") {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Enter a valid email!";
        if (!$errors) { $success = "Reset link sent"; $currentForm = "reset"; $_POST = []; }
    } elseif ($currentForm === "reset") {
        if (strlen($password) < 6) $errors['password'] = "Password must be 6+ chars!";
        if ($confirm === "" || $confirm !== $password) $errors['confirm'] = "Passwords do not match!";
        if (!$errors) { $success = "Password reset successful"; $currentForm = "login"; $_POST = []; }
    } elseif ($currentForm === "verify") {
        if ($code === "") $errors['code'] = "Enter verification code!";
        if (!$errors) { $success = "Email Verified!"; $currentForm = "login"; $_POST = []; }
    }
}
