<?php
require_once __DIR__ . '/../model/contactModel.php';

function showContactPage() {
    $errors = [];
    $success = false;
    $name = $email = $message = $captchaInput = "";

    processContact($name, $email, $message, $captchaInput, $errors, $success);

    include __DIR__ . '/../view/contactView.php';
}
