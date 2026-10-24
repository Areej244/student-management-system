<?php
require_once 'config.php';
require_login();
if (!is_admin() || $_SERVER['REQUEST_METHOD'] !== 'POST') die('Not allowed.');
check_csrf();

$id = (int)$_POST['id'];
$s = $db->prepare('DELETE FROM students WHERE id=?');
$s->bind_param('i', $id);
$s->execute();
header('Location: students.php');