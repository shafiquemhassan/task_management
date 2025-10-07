<?php
include '../includes/functions.php';
checkLogin('employee');

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $sql = "UPDATE tasks SET status='completed' WHERE id=? AND assigned_to=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $_SESSION['user_id']);
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
}
?>

