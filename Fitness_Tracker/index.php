<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page = $_GET['page'] ?? 'landing';

if ($page === 'contact') {
    require_once __DIR__ . '/controller/contactController.php';
    showContactPage();
} elseif ($page === 'about') {
    require_once __DIR__ . '/controller/aboutController.php';
    showAboutPage();
} elseif ($page === 'auth') {
    require_once __DIR__ . '/controller/authController.php';
    showAuthPage();
} else {
    require_once __DIR__ . '/controller/landingController.php';
    showLandingPage();
}