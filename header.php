<?php require_once 'config.php'; require_login(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Student Management System</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div id="loader"><div class="spinner"></div></div>
<nav class="nav">
  <b>🎓 SMS</b>
  <div>
    <a href="dashboard.php">Dashboard</a>
    <a href="students.php">Students</a>
    <?php if (is_admin()): ?><a href="student_form.php">+ Add</a><?php endif; ?>
    <a href="logout.php">Logout (<?= e($_SESSION['user']['name']) ?>, <?= e($_SESSION['user']['role']) ?>)</a>
  </div>
</nav>
<main class="wrap">