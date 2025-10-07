<?php
session_start();

// Redirect user if already logged in
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: employee/dashboard.php");
    }
    exit();
} else {
    header("Location: auth/login.php");
    exit();
}
?>
