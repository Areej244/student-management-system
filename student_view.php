<?php
include 'header.php';
$id = (int)($_GET['id'] ?? 0);
$s = $db->prepare('SELECT * FROM students WHERE id=?');
$s->bind_param('i', $id);
$s->execute();
$r = $s->get_result()->fetch_assoc() ?: die('Not found.');
?>
<h1><?= e($r['name']) ?></h1>
<div class="card details">
  <p><b>Email:</b> <?= e($r['email']) ?></p>
  <p><b>Phone:</b> <?= e($r['phone']) ?></p>
  <p><b>Course:</b> <?= e($r['course']) ?></p>
  <p><b>Semester:</b> <?= e($r['semester']) ?></p>
  <p><b>Address:</b> <?= e($r['address']) ?></p>
  <p><b>Registered:</b> <?= e($r['created_at']) ?></p>
  <a href="students.php">← Back</a>
</div>
<?php include 'footer.php'; ?>