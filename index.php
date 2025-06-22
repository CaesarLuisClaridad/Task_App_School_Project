<?php
// index.php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard/task.php");
} else {
    header("Location: login/login.html");
}
exit();
