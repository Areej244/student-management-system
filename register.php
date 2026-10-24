<?php
require_once 'config.php';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  check_csrf();
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $pw = $_POST['password'];

  if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pw) < 6) {
    $err = 'Enter a name, valid email and a password of 6+ characters.';
  } else {
    $s = $db->prepare('SELECT id FROM users WHERE email=?');
    $s->bind_param('s', $email);
    $s->execute();
    if ($s->get_result()->num_rows) {
      $err = 'Email already registered.';
    } else {
      // first registered user becomes admin, everyone else is a normal user
      $role = $db->query('SELECT COUNT(*) c FROM users')->fetch_assoc()['c'] == 0 ? 'admin' : 'user';
      $h = password_hash($pw, PASSWORD_DEFAULT);
      $i = $db->prepare('INSERT INTO users(name,email,password,role) VALUES(?,?,?,?)');
      $i->bind_param('ssss', $name, $email, $h, $role);
      $i->execute();
      header('Location: login.php?registered=1');
      exit;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Register</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body class="auth">
<div id="loader"><div class="spinner"></div></div>
<form class="card auth-box" method="post" novalidate>
  <h2>Create account</h2>
  <?php if ($err): ?><p class="alert"><?= e($err) ?></p><?php endif; ?>
  <input type="hidden" name="csrf" value="<?= csrf() ?>">
  <label>Name<input name="name" required></label>
  <label>Email<input type="email" name="email" required></label>
  <label>Password<input type="password" name="password" minlength="6" required></label>
  <button class="btn">Register</button>
  <p>Have an account? <a href="login.php">Login</a></p>
</form>
<script src="assets/script.js"></script>
</body>
</html>