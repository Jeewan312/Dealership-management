<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['e_mail'])) {
    header("Location: ../user/login.php");
    exit();
}

// For admin-only pages, check role
function checkAdmin() {
    if ($_SESSION['role'] !== 'admin') {
        header("Location: ../visitor/homepage.php");
        exit();
    }
}
?>
