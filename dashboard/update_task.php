<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/login.html');
    exit();
}

require '../config/db.php';
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = (int) $_POST['task_id'];
    $updated_task = $conn->real_escape_string($_POST['updated_task']);
    $conn->query("UPDATE tasks SET task = '$updated_task' WHERE id = $task_id AND user_id = $user_id");
    header('Location: task.php');
    exit();
}

$task_id = (int) $_GET['id'];
$result = $conn->query("SELECT task FROM tasks WHERE id = $task_id AND user_id = $user_id");
if ($result->num_rows === 1) {
    $task = $result->fetch_assoc()['task'];
} else {
    header('Location: task.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Task</title>
</head>
<body>
    <form method="POST">
        <input type="hidden" name="task_id" value="<?= $task_id ?>">
        <input type="text" name="updated_task" value="<?= htmlspecialchars($task) ?>">
        <button type="submit">Update</button>
    </form>
</body>
</html>