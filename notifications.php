<?php
session_start();
include __DIR__ . '/config/db.php';
if(!isset($_SESSION['user'])){ header('Location:/auth/login.php'); exit(); }
include __DIR__ . '/templates/header.php';
$uid = $_SESSION['user']['id'];
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['mark_read'])){ $nid=intval($_POST['nid']); $m=$conn->prepare('UPDATE notifications SET is_read=1 WHERE id=? AND user_id=?'); $m->bind_param('ii',$nid,$uid); $m->execute(); $m->close(); header('Location:/notifications.php'); exit(); }
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['mark_all'])){ $m=$conn->prepare('UPDATE notifications SET is_read=1 WHERE user_id=?'); $m->bind_param('i',$uid); $m->execute(); $m->close(); header('Location:/notifications.php'); exit(); }
$stmt = $conn->prepare('SELECT n.*, j.status AS jo_status FROM notifications n LEFT JOIN job_orders j ON n.job_order_id=j.id WHERE n.user_id=? ORDER BY n.created_at DESC'); $stmt->bind_param('i',$uid); $stmt->execute(); $res = $stmt->get_result();
?>
<h2>Notifications</h2>
<form method="POST" class="mb-2"><button name="mark_all" class="btn btn-sm btn-primary">Mark all read</button></form>
<table class="table table-hover"><thead><tr><th>When</th><th>Message</th><th>JO</th><th>JO Status</th><th>Action</th></tr></thead><tbody>
<?php while($n = $res->fetch_assoc()): ?>
<tr class="<?= $n['is_read'] ? '' : 'table-warning' ?>">
  <td><?= $n['created_at'] ?></td>
  <td><?= nl2br(htmlspecialchars($n['message'])) ?></td>
  <td><?= $n['job_order_id'] ? '<a href="/jo/view.php?id='.$n['job_order_id'].'">'.$n['job_order_id'].'</a>' : '-' ?></td>
  <td><?= htmlspecialchars($n['jo_status'] ?? '-') ?></td>
  <td><?php if(!$n['is_read']): ?><form method="POST" style="display:inline"><input type="hidden" name="nid" value="<?= $n['id'] ?>"><button name="mark_read" class="btn btn-sm btn-success">Mark read</button></form><?php else: ?> <span class="text-muted">Read</span><?php endif; ?></td>
</tr>
<?php endwhile; ?>
</tbody></table>
<?php include __DIR__ . '/templates/footer.php'; ?>