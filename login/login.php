<?php
session_start();
require_once "../config/db.php";

$error = "";

// When form is submitted
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Check if user exists
  $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows === 1) {
    $stmt->bind_result($user_id, $hashed_password);
    $stmt->fetch();

    // Check if password is correct
    if (password_verify($password, $hashed_password)) {
      $_SESSION['user_id'] = $user_id;
      $_SESSION['username'] = $username;
      header("Location: ../dashboard/task.php");
      exit();
    } else {
      $error = "Wrong password.";
    }
  } else {
    $error = "Username not found.";
  }

  $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="login.css" />
  <script src="https://kit.fontawesome.com/b859f19bb1.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.waves.min.js"></script>
  <title>Login | Task App</title>
</head>
<body>
  <div class="container" id="vanta-bg">
    <div class="form-container">
      <form method="POST" action="">
        <h2>Task App <i class="fa-solid fa-pen-nib"></i></h2>
        <p>One step closer to a <span>productive day!</span></p>

        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <div class="inputs">
          <label for="username">Username</label>
          <input type="text" name="username" id="username" placeholder="Enter your username..." required />

          <label for="password">Password</label>
          <input type="password" name="password" id="password" placeholder="Enter your password..." required />
        </div>

        <div class="btn">
          <input type="submit" value="Login">         
        </div>

        <div class="create">
          <p>Don't have an account? <a href="../register/register.php">Sign up</a></p>
        </div>
      </form>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      VANTA.WAVES({
        el: "#vanta-bg",
        mouseControls: true,
        touchControls: true,
        gyroControls: false,
        minHeight: 200.0,
        minWidth: 200.0,
        scale: 1.0,
        scaleMobile: 1.0,
        color: 0x4f46e5,
        shininess: 50,
        waveHeight: 20,
        waveSpeed: 1.2,
        zoom: 1,
      });
    });
  </script>
</body>
</html>
