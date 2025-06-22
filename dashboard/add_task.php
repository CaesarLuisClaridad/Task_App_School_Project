<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/login.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['task'])) {
    require '../config/db.php';
    $task = $conn->real_escape_string($_POST['task']);
    $user_id = $_SESSION['user_id'];
    $conn->query("INSERT INTO tasks (user_id, task) VALUES ('$user_id', '$task')");
}
header('Location: task.php');
exit();
?>