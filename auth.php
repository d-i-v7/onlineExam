<?php  
session_start();

// Haddii user-ka aanu active ahayn, u dir login.php
if (!isset($_SESSION['isActive']) && $_SESSION['isActive'] !== TRUE) {
    // Optional: Save current page if needed
    $_SESSION['redirectBack'] = $_SERVER['REQUEST_URI'];
    header("Location: ./");
    exit();
}

?>