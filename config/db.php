<?php
$host = "localhost";
$user = "root";       
$pass = "";           
$db   = "todo_system";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
