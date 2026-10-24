<?php
include 'header.php';
$total   = $db->query('SELECT COUNT(*) c FROM students')->fetch_assoc()['c'];
$week    = $db->query('SELECT COUNT(*) c FROM students WHERE created_at >= NOW() - INTERVAL 7 DAY')->fetch_assoc()['c'];
$courses = $db->query('SELECT COUNT(DISTINCT course) c FROM students')->fetch_assoc()['c'];
$recent  = $db->query('SELECT * FROM students ORDER BY created_at DESC LIMIT 5');
?>
<h1>Dashboard</h1>

<form action="students.php" class="searchbar">
  <input name="q" placeholder="Search students by name, email or course...">
  <button class="btn">Search</button>
</form>

<div class="stats">
  <div class="card stat"><span><?= $total ?></span>Total Students</div>
  <div class="card stat"><span><?= $week ?></span>New This Week</div>
  <div class="card stat"><span><?= $courses ?></span>Courses</div>
</div>

<div class="card">
  <h3>Recent Registrations</h3>
  <div class="table-wrap">
    <table>
      <tr><th>Name</th><th>Email</th><th>Course</th><th>Added</th></tr>
      <?php while ($r = $recent->fetch_assoc()): ?>
      <tr>
        <td><a href="student_view.php?id=<?= $r['id'] ?>"><?= e($r['name']) ?></a></td>
        <td><?= e($r['email']) ?></td>
        <td><?= e($r['course']) ?></td>
        <td><?= e(substr($r['created_at'], 0, 10)) ?></td>
      </tr>
      <?php endwhile; ?>
    </table>
  </div>
</div>
<?php include 'footer.php'; ?>