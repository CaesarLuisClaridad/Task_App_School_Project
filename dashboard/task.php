<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: ../login/login.php");
  exit();
}

require '../config/db.php';

$user_id = $_SESSION['user_id'];

// Add Task
if (isset($_POST['task'])) {
  $task = $conn->real_escape_string($_POST['task']);
  $conn->query("INSERT INTO tasks (user_id, task) VALUES ($user_id, '$task')");
  header("Location: task.php");
  exit();
}

// Update Task
if (isset($_POST['update_task_id'])) {
  $id = (int) $_POST['update_task_id'];
  $updated = $conn->real_escape_string($_POST['updated_task']);
  $conn->query("UPDATE tasks SET task = '$updated' WHERE id = $id AND user_id = $user_id");
  header("Location: task.php");
  exit();
}

// Delete Task
if (isset($_GET['delete'])) {
  $id = (int) $_GET['delete'];
  $conn->query("DELETE FROM tasks WHERE id = $id AND user_id = $user_id");
  header("Location: task.php");
  exit();
}

// Clear All Tasks
if (isset($_GET['clear'])) {
  $conn->query("DELETE FROM tasks WHERE user_id = $user_id");
  header("Location: task.php");
  exit();
}

// Logout
if (isset($_GET['logout'])) {
  session_destroy();
  header("Location: ../login/login.php");
  exit();
}

// Get tasks for current user
$tasks = $conn->query("SELECT * FROM tasks WHERE user_id = $user_id ORDER BY id DESC");
$task_count = $tasks->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://kit.fontawesome.com/b859f19bb1.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="task.css" />
  <title>Task List | Task App</title>
</head>
<body>
  <div class="main-container" id="vanta-bg">
    <div class="glass-card">
      <form method="POST" action="">
        <h2>Task App <i class="fa-solid fa-pen-nib"></i></h2>
        <p>Welcome back! Plan your <span>productive day</span>.</p>
        <div class="input">
          <input type="text" name="task" placeholder="Add tasks..." required />
          <button type="submit"><i class="fa-solid fa-cloud-arrow-up"></i></button>
        </div>
      </form>

      <?php if ($task_count > 0): ?>
        <div class="task-count">
          <p>My Task: <?= $task_count ?></p>
        </div>
      <?php endif; ?>

      <div class="list">
        <?php while ($row = $tasks->fetch_assoc()): ?>
          <div class="task">
            <div class="input-task">
              <?php if (isset($_GET['edit']) && $_GET['edit'] == $row['id']): ?>
                <form method="POST" action="" class="edit-form">
                  <input type="hidden" name="update_task_id" value="<?= $row['id'] ?>" />
                  <input type="text" name="updated_task" value="<?= htmlspecialchars($row['task']) ?>" required />
                  <button type="submit" class="confirm-btn"><i class="fa-solid fa-check"></i></button>
                </form>
              <?php else: ?>
                <p><?= htmlspecialchars($row['task']) ?></p>
              <?php endif; ?>
            </div>
            <div class="actions">
              <a href="?edit=<?= $row['id'] ?>"><i class="fa-solid fa-pen"></i></a>
              <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this task?')"><i class="fa-solid fa-trash"></i></a>
            </div>
          </div>
        <?php endwhile; ?>
      </div>

      <div class="clr-btn">
        <a href="?clear=1">
          <button class="clear">Clear Task</button>
        </a>
      </div>
    </div>

    <div class="login-icon">
      <a href="?logout=1" title="Logout">
        <i class="fa-solid fa-right-from-bracket"></i>
      </a>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.waves.min.js"></script>
  <script>
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
      shininess: 50.0,
      waveHeight: 20.0,
      waveSpeed: 1.0,
      zoom: 0.85,
    });
  </script>
</body>
</html>
