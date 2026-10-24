<?php
include 'header.php';
$q = trim($_GET['q'] ?? '');
$per = 5;
$page = max(1, (int)($_GET['page'] ?? 1));
$like = "%$q%";

$c = $db->prepare('SELECT COUNT(*) c FROM students WHERE name LIKE ? OR email LIKE ? OR course LIKE ?');
$c->bind_param('sss', $like, $like, $like);
$c->execute();
$total = $c->get_result()->fetch_assoc()['c'];
$pages = max(1, ceil($total / $per));
$page = min($page, $pages);
$off = ($page - 1) * $per;

$s = $db->prepare('SELECT * FROM students WHERE name LIKE ? OR email LIKE ? OR course LIKE ? ORDER BY id DESC LIMIT ? OFFSET ?');
$s->bind_param('sssii', $like, $like, $like, $per, $off);
$s->execute();
$rows = $s->get_result();
?>
<h1>Students <small>(<?= $total ?>)</small></h1>

<form class="searchbar">
  <input name="q" value="<?= e($q) ?>" placeholder="Search...">
  <button class="btn">Search</button>
</form>

<div class="card table-wrap">
  <table>
    <tr><th>#</th><th>Name</th><th>Email</th><th>Course</th><th>Actions</th></tr>
    <?php while ($r = $rows->fetch_assoc()): ?>
    <tr>
      <td><?= $r['id'] ?></td>
      <td><?= e($r['name']) ?></td>
      <td><?= e($r['email']) ?></td>
      <td><?= e($r['course']) ?></td>
      <td class="actions">
        <a href="student_view.php?id=<?= $r['id'] ?>">View</a>
        <?php if (is_admin()): ?>
          <a href="student_form.php?id=<?= $r['id'] ?>">Edit</a>
          <form method="post" action="student_delete.php" onsubmit="return confirm('Delete this student?')">
            <input type="hidden" name="csrf" value="<?= csrf() ?>">
            <input type="hidden" name="id" value="<?= $r['id'] ?>">
            <button class="link danger">Delete</button>
          </form>
        <?php endif; ?>
      </td>
    </tr>
    <?php endwhile; ?>
    <?php if (!$total): ?><tr><td colspan="5">No students found.</td></tr><?php endif; ?>
  </table>
</div>

<div class="pager">
  <?php for ($i = 1; $i <= $pages; $i++): ?>
    <a class="<?= $i == $page ? 'active' : '' ?>" href="?q=<?= urlencode($q) ?>&page=<?= $i ?>"><?= $i ?></a>
  <?php endfor; ?>
</div>
<?php include 'footer.php'; ?>