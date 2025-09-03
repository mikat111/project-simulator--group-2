<?php
$errors = [];
$success = "";
$currentForm = $_POST['form'] ?? 'login';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm = trim($_POST['confirm'] ?? '');
    $code = trim($_POST['code'] ?? '');

    if ($currentForm === "login") {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Enter a valid email!";
        }
        if (strlen($password) < 6) {
            $errors['password'] = "Password must be 6+ chars!";
        }
        if (!$errors) $success = "Login Successful";
    }

    elseif ($currentForm === "signup") {
        if ($name === "") $errors['name'] = "Full name required!";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Enter a valid email!";
        if (strlen($password) < 6) $errors['password'] = "Password must be 6+ chars!";
        if ($confirm === "" || $confirm !== $password) $errors['confirm'] = "Passwords do not match!";
        if (!$errors) { 
            $success = "Signup Successful! Please verify email."; 
            $currentForm = "verify"; 
        }
    }

    elseif ($currentForm === "forgot") {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Enter a valid email!";
        if (!$errors) { 
            $success = "Reset link sent"; 
            $currentForm = "reset"; 
        }
    }

    elseif ($currentForm === "reset") {
        if (strlen($password) < 6) $errors['password'] = "Password must be 6+ chars!";
        if ($confirm === "" || $confirm !== $password) $errors['confirm'] = "Passwords do not match!";
        if (!$errors) { 
            $success = "Password reset successful"; 
            $currentForm = "login"; 
        }
    }

    elseif ($currentForm === "verify") {
        if ($code === "") $errors['code'] = "Enter verification code!";
        if (!$errors) { 
            $success = "Email Verified!"; 
            $currentForm = "login"; 
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Fitness Tracker Auth</title>
<style>

  body {margin:0; padding:0; font-family:'Segoe UI',sans-serif; height:100vh; background:#f5f5f5;}
  .wrapper {display:flex; height:100%;}
  .left-bg {width:45%; height:100%; background:linear-gradient(135deg,#d3cce3,#e9e4f0); display:flex; justify-content:flex-start; align-items:center; padding-left:60px; box-shadow:6px 0 15px rgba(0,0,0,0.2);}
  .right-side {flex:1; background:url("pic.jpg"); background-size:cover; height:100vh; background-position:center;}
  .container {width:440px; height:50ex; padding:25px 20px; border-radius:12px; background:rgba(255,255,255,0.95); box-shadow:0 6px 18px rgba(0,0,0,0.3); color:#2d2d2d;}
  h2 {text-align:center; color:#4b6cb7;}
  input {width:100%; padding:10px; margin:8px 0; border-radius:8px; border:1px solid #4b6cb7; font-size:14px; background:#f0f0f5; color:#2d2d2d;}
  button {width:100%; padding:10px; margin-top:10px; border-radius:8px; border:none; background:#4b6cb7; color:#f0f0f5; font-size:15px; font-weight:bold; cursor:pointer; transition:0.3s;}
  button:hover {background:#6b7ecf;}
  .switch-btn {margin:5px 0; font-size:14px; cursor:pointer; background:none; color:#4b6cb7; border:1px solid #4b6cb7;}
  .switch-btn:hover {color:#6b7ecf; border-color:#6b7ecf;}
  .error {color:red; font-size:13px; margin:0;}
  .success {color:green; font-size:14px; font-weight:bold; text-align:center;}
  .navbar {display:flex; gap:25px; padding:15px 80px; background:rgba(30,30,60,0.85); position:fixed; top:0; width:100%; z-index:10; backdrop-filter:blur(4px);}
  .navbar a {color:#e0e0e0; text-decoration:none; font-weight:bold; transition:0.3s;}
  .navbar a:hover {color:#a3d8ff;}
</style>
</head>
<body>
<div class="navbar">
  <a href ="land.php">Home</a>
  <a href="l.html">About</a>
  <a href="authentication.php">Signup</a>
  <a href="contact.php">Contact Us</a>
</div>

<div class="wrapper">
  <div class="left-bg">
    <div class="container">
      <h2 id="formTitle"><?= ucfirst($currentForm) ?></h2>
      <?php if($success): ?><p class="success"><?= $success ?></p><?php endif; ?>

      <form method="post" action="">
        <input type="hidden" name="form" id="formType" value="<?= $currentForm ?>">

        <input type="text" name="name" id="name" placeholder="Full Name" style="display:none;" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        <p id="nameErr" class="error"><?= $errors['name'] ?? '' ?></p>

        <input type="text" name="email" id="email" placeholder="Email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        <p id="emailErr" class="error"><?= $errors['email'] ?? '' ?></p>

        <input type="password" name="password" id="password" placeholder="Password">
        <p id="passErr" class="error"><?= $errors['password'] ?? '' ?></p>

        <input type="password" name="confirm" id="confirm" placeholder="Confirm Password" style="display:none;">
        <p id="confirmErr" class="error"><?= $errors['confirm'] ?? '' ?></p>

        <input type="text" name="code" id="code" placeholder="Verification Code" style="display:none;" value="<?= htmlspecialchars($_POST['code'] ?? '') ?>">
        <p id="codeErr" class="error"><?= $errors['code'] ?? '' ?></p>

        <button type="submit" id="submitBtn">Login</button>
      </form>

      <button id="switchBtn" class="switch-btn">Signup</button>
      <button class="switch-btn" onclick="showForm('forgot')">Forgot Password?</button>
    </div>
  </div>
  <div class="right-side"></div>
</div>

<script>
let currentForm = "<?= $currentForm ?>";
const forms = {
  login: { title:"Login", show:["password"], hide:["name","confirm","code"], submit:"Login", switchText:"Signup" },
  signup: { title:"Sign Up", show:["name","confirm","password"], hide:["code"], submit:"Sign Up", switchText:"Switch to Login" },
  forgot: { title:"Forgot Password", show:[], hide:["name","confirm","password","code"], submit:"Send Reset Link", switchText:"Back to Login" },
  reset: { title:"Reset Password", show:["confirm","password"], hide:["name","code"], submit:"Reset Password", switchText:"Back to Login" },
  verify: { title:"Email Verification", show:["code"], hide:["name","confirm","password"], submit:"Verify Email", switchText:"Back to Login" }
};

function showForm(formName){
  currentForm = formName;
  document.getElementById("formTitle").innerText = forms[formName].title;
  document.getElementById("submitBtn").innerText = forms[formName].submit;
  document.getElementById("switchBtn").innerText = forms[formName].switchText;
  document.getElementById("formType").value = formName;

  ["name","email","password","confirm","code"].forEach(id=>{
    document.getElementById(id).style.display = forms[formName].show.includes(id) || id==="email" ? "block" : "none";
  });
}

document.getElementById("switchBtn").onclick = function(){
  if(currentForm==="login") showForm("signup");
  else showForm("login");
};

showForm(currentForm);
</script>
</body>
</html>
