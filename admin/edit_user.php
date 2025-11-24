<?php
session_start();
include __DIR__ . '/../config/db.php';
if(!isset($_SESSION['user']) || $_SESSION['user']['role']!=='admin'){ header('Location:/auth/login.php'); exit(); }
$id = intval($_GET['id'] ?? 0);
if($id<=0){ header('Location:/job_order_tracking_system/admin/users.php'); exit(); }
if($_SERVER['REQUEST_METHOD']==='POST'){
  $full = $_POST['username']; $role = $_POST['role'];
  $stmt = $conn->prepare('UPDATE users SET username=?, role=? WHERE id=?'); $stmt->bind_param('ssi',$full,$role,$id); $stmt->execute(); $stmt->close();
  $_SESSION['success']='User updated'; header('Location: /job_order_tracking_system/admin/users.php'); exit();
}
$stmt = $conn->prepare('SELECT * FROM users WHERE id=?'); $stmt->bind_param('i',$id); $stmt->execute(); $user = $stmt->get_result()->fetch_assoc(); $stmt->close();
include __DIR__ . '/../templates/header.php';
?>
<h2>Edit User</h2>
<form method="POST"><div class="mb-3"><label>Username</label><input class="form-control" name="username" value="<?= htmlspecialchars($user['username']) ?>"></div>
<div class="mb-3"><label>Role</label><select class="form-select" name="role"><?php foreach(['admin','encoder','artist','printer','heatpress','cutting','sewing','qc','sales'] as $r) echo '<option value="'.$r.'" '.($user['role']==$r?'selected':'').'>'.$r.'</option>'; ?></select></div>
<button class="btn btn-primary">Save</button></form>
<?php include __DIR__ . '/../templates/footer.php'; ?>