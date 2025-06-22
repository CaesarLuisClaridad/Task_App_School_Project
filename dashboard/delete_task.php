<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/login.html');
    exit();
}

require '../config/db.php';
$user_id = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $conn->query("DELETE FROM tasks WHERE id = $id AND user_id = $user_id");
} elseif (isset($_GET['clear'])) {
    $conn->query("DELETE FROM tasks WHERE user_id = $user_id");
}
header('Location: task.php');
exit();
?>
