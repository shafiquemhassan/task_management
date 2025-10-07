<?php
include '../includes/functions.php';
checkLogin('admin');

// Fetch employees to assign task
$employees = $conn->query("SELECT * FROM users WHERE role='employee'");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $user = $_POST['assigned_to'];
    $due  = $_POST['due_date'];

    $sql = "INSERT INTO tasks (title, description, assigned_to, due_date)
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssis", $title, $desc, $user, $due);
    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}
?>

