<?php

require_once __DIR__ . '/../model/authModel.php';

function showAuthPage() {
    $errors = [];
    $success = "";
    $currentForm = $_POST['form'] ?? ($_GET['form'] ?? 'login');

    processAuth($currentForm, $errors, $success);

    include __DIR__ . '/../view/authView.php';
}
