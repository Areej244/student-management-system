<?php
include 'header.php';
if (!is_admin()) die('Admins only.');

$id = (int)($_GET['id'] ?? 0);
$st = ['name'=>'', 'email'=>'', 'phone'=>'', 'course'=>'', 'semester'=>'', 'address'=>''];
$err = '';

if ($id) {
  $s = $db->prepare('SELECT * FROM students WHERE id=?');
  $s->bind_param('i', $id);
  $s->execute();
  $st = $s->get_result()->fetch_assoc() ?: die('Not found.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  check_csrf();
  $st = array_map('trim', array_intersect_key($_POST, $st));

  if ($st['name'] === '' || !filter_var($st['email'], FILTER_VALIDATE_EMAIL) || $st['course'] === '') {
    $err = 'Name, valid email and course are required.';
  } else {
    $sem = $st['semester'] === '' ? null : (int)$st['semester'];
    if ($id) {
      $q = $db->prepare('UPDATE students SET name=?, email=?, phone=?, course=?, semester=?, address=? WHERE id=?');
      $q->bind_param('ssssisi', $st['name'], $st['email'], $st['phone'], $st['course'], $sem, $st['address'], $id);
    } else {
      $q = $db->prepare('INSERT INTO students(name,email,phone,course,semester,address) VALUES(?,?,?,?,?,?)');
      $q->bind_param('ssssis', $st['name'], $st['email'], $st['phone'], $st['course'], $sem, $st['address']);
    }
    $q->execute();
    header('Location: students.php');
    exit;
  }
}
?>
<h1><?= $id ? 'Edit' : 'Add' ?> Student</h1>

<form class="card form" method="post" novalidate>
  <?php if ($err): ?><p class="alert"><?= e($err) ?></p><?php endif; ?>
  <input type="hidden" name="csrf" value="<?= csrf() ?>">
  <label>Name<input name="name" value="<?= e($st['name']) ?>" required></label>
  <label>Email<input type="email" name="email" value="<?= e($st['email']) ?>" required></label>
  <label>Phone<input name="phone" value="<?= e($st['phone']) ?>"></label>
  <label>Course<input name="course" value="<?= e($st['course']) ?>" required></label>
  <label>Semester<input type="number" min="1" max="12" name="semester" value="<?= e($st['semester']) ?>"></label>
  <label>Address<input name="address" value="<?= e($st['address']) ?>"></label>
  <button class="btn">Save</button>
  <a href="students.php">Cancel</a>
</form>
<?php include 'footer.php'; ?>