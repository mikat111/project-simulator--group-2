<?php
function getVisitCount() {
    
    if (!isset($_SESSION['visits'])) {
        $_SESSION['visits'] = 1;
    } else {
        $_SESSION['visits']++;
    }
    return $_SESSION['visits'];
}