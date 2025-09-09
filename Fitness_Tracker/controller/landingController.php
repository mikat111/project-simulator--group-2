<?php
require_once __DIR__ . '/../model/userModel.php';

function showLandingPage() {
    $visits = getVisitCount();
    include __DIR__ . '/../view/landingView.php';
}
