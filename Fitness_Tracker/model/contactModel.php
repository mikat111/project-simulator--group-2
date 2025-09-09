<?php


function generateCaptcha() {
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $captcha = '';
    for($i=0;$i<6;$i++){
        $captcha .= $chars[rand(0, strlen($chars)-1)];
    }
    $_SESSION['captcha'] = $captcha;
    return $captcha;
}

function processContact(&$name, &$email, &$message, &$captchaInput, &$errors, &$success) {
    if(empty($_SESSION['captcha'])) generateCaptcha();

    if($_SERVER['REQUEST_METHOD']==='POST'){
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $message = trim($_POST['message'] ?? '');
        $captchaInput = trim($_POST['captcha'] ?? '');

        if($name==='') $errors['name']='Name required';
        if($email==='') $errors['email']='Email required';
        elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)) $errors['email']='Enter valid email';
        if($message==='') $errors['message']='Message required';
        if($captchaInput==='') $errors['captcha']='Captcha required';
        elseif($captchaInput!==$_SESSION['captcha']){
            $errors['captcha']='Captcha does not match';
            generateCaptcha();
        }

        if(!$errors){
            $success = true;
            $name = $email = $message = $captchaInput = '';
            generateCaptcha();
        } else {
            generateCaptcha();
        }
    }
}
