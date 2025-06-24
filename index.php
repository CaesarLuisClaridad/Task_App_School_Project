<?php
// index.php

session_start();

// Redirect user based on login status
if (!empty($_SESSION['user_id'])) {
    header("Location: dashboard/task.php");
} else {
    header("Location: login/login.php"); 
}

exit();
