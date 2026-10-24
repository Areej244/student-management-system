<?php
require_once 'config.php';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  check_csrf();
  $s = $db->prepare('SELECT * FROM users WHERE email=?');
  $s->bind_param('s', $_POST['email']);
  $s->execute();
  $u = $s->get_result()->fetch_assoc();

  if ($u && password_verify($_POST['password'], $u['password'])) {
    session_regenerate_id(true);
    $_SESSION['user'] = ['id' => $u['id'], 'name' => $u['name'], 'role' => $u['role']];
    header('Location: dashboard.php');
    exit;
  }
  $err = 'Invalid email or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body class="auth">
<div id="loader"><div class="spinner"></div></div>
<form class="card auth-box" method="post" novalidate>
  <h2>Login</h2>
  <?php if (isset($_GET['registered'])): ?><p class="ok">Registered! Please log in.</p><?php endif; ?>
  <?php if ($err): ?><p class="alert"><?= e($err) ?></p><?php endif; ?>
  <input type="hidden" name="csrf" value="<?= csrf() ?>">
  <label>Email<input type="email" name="email" required></label>
  <label>Password<input type="password" name="password" required></label>
  <button class="btn">Login</button>
  <p>No account? <a href="register.php">Register</a></p>
</form>
<script src="assets/script.js"></script>
</body>
</html>