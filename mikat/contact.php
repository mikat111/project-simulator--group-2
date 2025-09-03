<?php
session_start();

if (!isset($_SESSION['captcha'])) {
    $_SESSION['captcha'] = '';
}

function generateCaptcha() {
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $captcha = '';
    for ($i = 0; $i < 6; $i++) {
        $captcha .= $chars[rand(0, strlen($chars) - 1)];
    }
    $_SESSION['captcha'] = $captcha;
    return $captcha;
}

if ($_SESSION['captcha'] === '') {
    generateCaptcha();
}

$errors = [];
$success = false;
$name = $email = $message = $captchaInput = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $captchaInput = trim($_POST['captcha'] ?? '');

    if ($name === '') $errors['name'] = 'Name required';
    if ($email === '') $errors['email'] = 'Email required';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Enter a valid email';
    if ($message === '') $errors['message'] = 'Message required';
    if ($captchaInput === '') $errors['captcha'] = 'Captcha required';
    elseif ($captchaInput !== $_SESSION['captcha']) {
        $errors['captcha'] = 'Captcha does not match';
        generateCaptcha();
    }

    if (!$errors) {
        $success = true;
        $name = $email = $message = $captchaInput = '';
        generateCaptcha();
    } else {
        generateCaptcha();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us</title>
<style>
  body {margin:0; font-family: Arial, sans-serif; background:#f2f2f2;}
  .navbar {display:flex; gap:25px; padding:15px 80px; background: rgba(30,30,60,0.85); position:fixed; top:0; width:100%; z-index:10; backdrop-filter:blur(4px);}
  .navbar a {color:#e0e0e0; text-decoration:none; font-weight:bold; transition:0.3s;}
  .navbar a:hover {color:#a3d8ff;}
  .header {background: linear-gradient(135deg, #d3cce3, #e9e4f0); color: #3f5893; text-align:center; padding:80px 20px 40px;}
  .header h1 {margin:0; font-size:36px;}
  .header p {font-size:16px; margin-top:10px; color:black;}
  .container {max-width:450px; margin:-50px auto 40px; background:#fff; padding:30px; border-radius:8px; box-shadow:0 8px 25px rgba(0,0,0,0.2);}
  .container h2 {text-align:center; color:#3f5893; margin-bottom:20px;}
  input, textarea {width:100%; padding:12px; margin-bottom:15px; border-radius:4px; border:1px solid #ccc; font-size:14px; box-sizing:border-box;}
  button {width:100%; padding:12px; background:#395086; color:#fff; font-weight:bold; border:none; border-radius:4px; cursor:pointer; font-size:16px;}
  button:hover {background:#9a64ab;}
  .error {color:red; font-size:13px; margin-top:-10px; margin-bottom:10px;}
  .success-message {text-align:center; font-size:16px; color:green; margin-bottom:15px;}
  .captcha-box {display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; background:#e0e0e0; padding:8px 10px; font-weight:bold; font-size:18px; border-radius:4px;}
  .captcha-box button {width:auto; padding:6px 10px; font-size:16px; background:#395086; color:#fff; border:none; cursor:pointer; border-radius:4px;}
</style>
</head>
<body>
 <div class="navbar">
    <a href ="land.php">Home</a>
    <a href="l.html">About</a>
    <a href="authentication.php">Signup</a>
    <a href="contact.php">Contact Us</a>
  </div>

<div class="header">
  <h1>Contact Us</h1>
  <p>Questions, bug reports, feedback, feature requests — we're here for it all.</p>
</div>

<div class="container">
  <h2>Send us a message</h2>

  <?php if($success): ?>
    <p class="success-message">Thank you! Your inquiry has been submitted.</p>
  <?php endif; ?>

  <form method="post" action="">
    <input type="text" name="name" placeholder="Your Name" value="<?= $name ?>">
    <p class="error"><?= $errors['name'] ?? '' ?></p>

    <input type="text" name="email" placeholder="Your Email" value="<?= $email ?>">
    <p class="error"><?= $errors['email'] ?? '' ?></p>

    <textarea name="message" placeholder="How can we help?" rows="5"><?= $message ?></textarea>
    <p class="error"><?= $errors['message'] ?? '' ?></p>

    <div class="captcha-box">
      <span><?= $_SESSION['captcha'] ?></span>
      <button type="button" onclick="location.reload();">🔄</button>
    </div>
    <input type="text" name="captcha" placeholder="Enter Captcha">
    <p class="error"><?= $errors['captcha'] ?? '' ?></p>

    <button type="submit">SEND</button>
  </form>
</div>
</body>
</html>
