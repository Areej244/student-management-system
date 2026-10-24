<?php
session_start();
$db = new mysqli('localhost', 'root', '', 'student_db');
if ($db->connect_error) die('Database connection failed.');
$db->set_charset('utf8mb4');

function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function require_login(){ if (empty($_SESSION['user'])) { header('Location: login.php'); exit; } }
function is_admin(){ return ($_SESSION['user']['role'] ?? '') === 'admin'; }
function csrf(){ if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16)); return $_SESSION['csrf']; }
function check_csrf(){ if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) die('Invalid request.'); }