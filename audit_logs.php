<?php
session_start();
include __DIR__ . '/config/db.php';
if(!isset($_SESSION['user']) || $_SESSION['user']['role']!=='admin'){ header('Location:/auth/login.php'); exit(); }
include __DIR__ . '/templates/header.php';
$q = "SELECT a.*, u.username AS updater, j.customer_name FROM audit_logs a LEFT JOIN users u ON a.updated_by=u.id LEFT JOIN job_orders j ON a.jo_id=j.id ORDER BY a.updated_at DESC LIMIT 500";
$res = $conn->query($q);
?>
<h2>Audit Logs</h2>
<table class="table table-sm"><thead><tr><th>When</th><th>JO</th><th>Customer</th><th>Status</th><th>Updated By</th></tr></thead><tbody>
<?php while($r = $res->fetch_assoc()): ?>
<tr><td><?= $r['updated_at'] ?></td><td><a href="/jo/view.php?id=<?= $r['jo_id'] ?>">JO#<?= $r['jo_id'] ?></a></td><td><?= htmlspecialchars($r['customer_name']) ?></td><td><?= htmlspecialchars($r['status']) ?></td><td><?= htmlspecialchars($r['updater']) ?></td></tr>
<?php endwhile; ?></tbody></table>
<?php include __DIR__ . '/templates/footer.php'; ?>