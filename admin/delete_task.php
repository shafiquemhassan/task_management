<?php
include '../includes/functions.php';
checkLogin('admin');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$task_id = intval($_GET['id']);

$sql = "DELETE FROM tasks WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $task_id);
$stmt->execute();

header("Location: dashboard.php");
exit();
?>
