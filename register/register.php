<?php
require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);
  $confirm_password = trim($_POST["confirm_password"]);

  if ($username && $password && $confirm_password) {
    if ($password !== $confirm_password) {
      $error = "Passwords do not match!";
    } else {
      // Check if username already exists
      $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $stmt->store_result();

      if ($stmt->num_rows > 0) {
        $error = "Username already taken!";
      } else {
        // Insert new user
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $insert->bind_param("ss", $username, $hashed_password);
        if ($insert->execute()) {
          header("Location: ../login/login.php?success=registered");
          exit();
        } else {
          $error = "Registration failed.";
        }
        $insert->close();
      }
      $stmt->close();
    }
  } else {
    $error = "All fields are required!";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="register.css" />
  <script src="https://kit.fontawesome.com/b859f19bb1.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.waves.min.js"></script>
  <title>Sign Up | Task App</title>
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

          <label for="confirm_password">Confirm Password</label>
          <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm password..." required />
        </div>

        <div class="btn">
          <input type="submit" value="Signup">
        </div>

        <div class="create">
          <p>Already have an account? <a href="../login/login.php">Login</a></p>
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