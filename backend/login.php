<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (isset($users[$username]) && $users[$username]['password'] === $password) {
        $_SESSION['user'] = $username;
        $_SESSION['username'] = $username;
        $_SESSION['access'] = $users[$username]['access'];
        header('Location: dashboard.php');
        exit;
    }
    $error = "Invalid credentials";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login - Spanning Tree Corporation</title>
  <link rel="stylesheet" href="../css/admin.css" />
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600" rel="stylesheet" />
</head>
<body class="backend">
  <div class="login-container">
    <img src="../images/logo.png" alt="Spanning Tree Corporation Logo" class="logo" />
    <h2>Admin Login</h2>
    <?php if (isset($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form action="login.php" method="post">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" required />

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required />

      <button type="submit" class="btn">Login</button>
    </form>
  </div>
  <script src="../js/main.js"></script>
</body>
</html>
