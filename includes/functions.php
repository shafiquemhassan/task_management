<?php
session_start();
include_once __DIR__ . '/../config/db.php';

// Check login and role
function checkLogin($role = null) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../auth/login.php");
        exit();
    }
    if ($role && $_SESSION['role'] !== $role) {
        header("Location: ../auth/login.php");
        exit();
    }
}

// Get all tasks (Admin)
function getAllTasks($conn) {
    $sql = "SELECT t.*, u.username 
            FROM tasks t 
            LEFT JOIN users u ON t.assigned_to = u.id
            ORDER BY t.due_date ASC";
    return $conn->query($sql);
}

// Get tasks assigned to employee
function getTasksByUser($conn, $user_id) {
    $sql = "SELECT * FROM tasks WHERE assigned_to = $user_id ORDER BY due_date ASC";
    return $conn->query($sql);
}
?>
