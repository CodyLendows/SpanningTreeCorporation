<?php
session_start();
require '../config.php';
require '../auth_check.php';

if (!isAuthenticated()) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';

    if ($password === $management_password) {
        $_SESSION['management'] = true;
        header('Location: dashboard.php');
        exit;
    }
    $error = "Invalid Management Password";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Management: Bitsplitter - Spanning Tree Corporation</title>
  <link rel="stylesheet" href="../../css/admin.css" />
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600" rel="stylesheet" />
</head>
<body class="backend">
  <div class="login-container">
    <img src="../../images/logo.png" alt="Spanning Tree Corporation Logo" class="logo" />
    <h2>You are logged in as: <?php echo htmlspecialchars($_SESSION['user']); ?></h2>
    <p>This service requires additional authentication. Please enter your Management Password.</p>
    <br>
    <hr>
    <br>
    <?php if (isset($error)): ?>
        <p class="error" style="color: red"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form action="signatures.php" method="post">
      <label for="password">Management Password</label>
      <input type="password" id="password" name="password" required />
      <button type="submit" class="btn">Authenticate</button>
    </form>
  </div>
  <script src="../js/main.js"></script>
</body>