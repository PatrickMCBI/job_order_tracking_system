<?php
session_start();
include __DIR__ . '/../config/db.php';
if(!isset($_SESSION['user'])){ header("Location:/auth/login.php"); exit(); }
include __DIR__ . '/../templates/header.php';
$role = $_SESSION['user']['role'];
$map = [
  'encoder'=>['Pending Layout'],'artist'=>['Pending Layout','In Layout','Sizing'],
  'printer'=>['Ready for Printing','Printing'],'heatpress'=>['Ready for Heat Pressing','Pressing'],
  'cutting'=>['Ready for Cutting','Cutting'],'sewing'=>['Ready for Sewing','In Sewing','Done Sewing'],
  'qc'=>['Done Sewing','QC Checking'],'sales'=>[]
];
if($role==='sales' || $role==='admin'){ $q = "SELECT j.*, u.username AS creator FROM job_orders j LEFT JOIN users u ON j.created_by = u.id ORDER BY j.id DESC"; $res = $conn->query($q);
} else {
  $allowed = $map[$role] ?? [];
  if(empty($allowed)){ $res = $conn->query("SELECT j.*, u.username AS creator FROM job_orders j LEFT JOIN users u ON j.created_by = u.id WHERE 1=0");
  } else {
    $escaped = array_map(function($s){ return "'" . $GLOBALS['conn']->real_escape_string($s) . "'"; }, $allowed);
    $in = implode(",", $escaped);
    $q = "SELECT j.*, u.username AS creator FROM job_orders j LEFT JOIN users u ON j.created_by = u.id WHERE j.status IN ($in) ORDER BY j.id DESC";
    $res = $conn->query($q);
  }
}
?>
<h2>Job Orders</h2>
<table class="table table-striped">
<thead><tr><th>ID</th><th>Customer</th><th>Qty</th><th>Deadline</th><th>Status</th><th>Creator</th><th>Actions</th></tr></thead>
<tbody>
<?php while($row = $res->fetch_assoc()): ?>
<tr>
  <td><?= $row['id'] ?></td>
  <td><?= htmlspecialchars($row['customer_name']) ?></td>
  <td><?= (int)$row['quantity'] ?></td>
  <td><?= htmlspecialchars($row['deadline']) ?></td>
  <td><?= htmlspecialchars($row['status']) ?></td>
  <td><?= htmlspecialchars($row['creator']) ?></td>
  <td><a class="btn btn-sm btn-info" href="/jo/view.php?id=<?= $row['id'] ?>">View</a></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php include __DIR__ . '/../templates/footer.php'; ?>