<?php
include '../includes/functions.php';
checkLogin('admin');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $user = $_POST['assigned_to'];
    $due = $_POST['due_date'];
    $status = $_POST['status'];

    $sql = "UPDATE tasks SET title=?, description=?, assigned_to=?, due_date=?, status=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssissi", $title, $desc, $user, $due, $status, $id);
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
