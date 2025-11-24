<?php
session_start();
include __DIR__ . '/../config/db.php';
if(!isset($_SESSION['user'])){ header('Location:/auth/login.php'); exit(); }
include __DIR__ . '/../templates/header.php';
$jo_id = intval($_GET['id'] ?? 0);
if($jo_id<=0){ echo '<p>Invalid JO</p>'; include __DIR__.'/../templates/footer.php'; exit();}
$stmt = $conn->prepare("SELECT j.*, u.username AS creator FROM job_orders j LEFT JOIN users u ON j.created_by = u.id WHERE j.id = ?");
$stmt->bind_param("i", $jo_id); $stmt->execute(); $jo = $stmt->get_result()->fetch_assoc(); $stmt->close();
$al = $conn->prepare("SELECT a.*, u.username AS updater FROM audit_logs a LEFT JOIN users u ON a.updated_by = u.id WHERE a.jo_id = ? ORDER BY a.updated_at DESC");
$al->bind_param("i", $jo_id); $al->execute(); $logs = $al->get_result(); $al->close();
$transitions = ["Pending Layout"=>["In Layout","Sizing","Ready for Printing"],"In Layout"=>["Sizing","Ready for Printing"],"Sizing"=>["Ready for Printing"],
"Ready for Printing"=>["Printing"],"Printing"=>["Ready for Heat Pressing"],"Ready for Heat Pressing"=>["Pressing"],
"Pressing"=>["Ready for Cutting"],"Ready for Cutting"=>["Cutting"],"Cutting"=>["Ready for Sewing"],"Ready for Sewing"=>["In Sewing"],
"In Sewing"=>["Done Sewing"],"Done Sewing"=>["QC Checking","Ready for Pickup"],"QC Checking"=>["Ready for Pickup"]];
$role = $_SESSION['user']['role'];
$role_allowed = ['encoder'=>["Pending Layout","In Layout","Sizing"],'artist'=>["Pending Layout","In Layout","Sizing","Ready for Printing"],
'printer'=>["Ready for Printing","Printing"],'heatpress'=>["Ready for Heat Pressing","Pressing"],'cutting'=>["Ready for Cutting","Cutting"],
'sewing'=>["Ready for Sewing","In Sewing","Done Sewing"],'qc'=>["Done Sewing","QC Checking"],'sales'=>[]];
$can_update = in_array($jo['status'], $role_allowed[$role] ?? []) || $role==='admin';
?>
<h2>JO#<?= $jo['id'] ?> — <?= htmlspecialchars($jo['customer_name']) ?></h2>
<p><strong>Status:</strong> <?= htmlspecialchars($jo['status']) ?></p>
<p><?= nl2br(htmlspecialchars($jo['item_description'])) ?></p>
<?php if($can_update): ?>
<form method="POST" action="/jo/update_status.php">
  <input type="hidden" name="job_id" value="<?= $jo['id'] ?>">
  <div class="row g-3">
    <div class="col-md-4"><select name="status" class="form-select">
      <option value="<?= htmlspecialchars($jo['status']) ?>"><?= htmlspecialchars($jo['status']) ?> (current)</option>
      <?php $allowed_next = $transitions[$jo['status']] ?? []; foreach($allowed_next as $s) echo '<option value="'.htmlspecialchars($s).'">'.htmlspecialchars($s).'</option>'; ?>
    </select></div>
    <div class="col-md-4"><input name="comment" class="form-control" placeholder="Optional comment"></div>
    <div class="col-md-4"><button class="btn btn-primary">Update Status</button></div>
  </div>
</form>
<?php else: ?><div class="alert alert-info">You cannot update this job.</div><?php endif; ?>
<hr><h4>Audit Log</h4>
<table class="table table-sm"><thead><tr><th>When</th><th>User</th><th>Status</th></tr></thead><tbody>
<?php while($r = $logs->fetch_assoc()): ?><tr><td><?= $r['updated_at'] ?></td><td><?= htmlspecialchars($r['updater']) ?></td><td><?= htmlspecialchars($r['status']) ?></td></tr><?php endwhile; ?>
</tbody></table>
<?php include __DIR__ . '/../templates/footer.php'; ?>