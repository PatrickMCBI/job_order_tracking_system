<?php
session_start();
include __DIR__ . '/../config/db.php';
if(!isset($_SESSION['user']) || $_SESSION['user']['role']!=='admin'){ header('Location:/job_order_tracking_system/auth/login.php'); exit(); }
include __DIR__ . '/../templates/header.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $username = $_POST['username']; $password = password_hash($_POST['password'], PASSWORD_DEFAULT); $role = $_POST['role'];
  $stmt = $conn->prepare('INSERT INTO users (username,password,role) VALUES (?,?,?)'); $stmt->bind_param('sss',$username,$password,$role); $stmt->execute(); $stmt->close();
  $_SESSION['success'] = 'User added'; header('Location:/job_order_tracking_system/admin/users.php'); exit();
}
?>
<h2>Add User</h2>
<form method="POST"><div class="mb-3"><label class="form-label">Username</label><input class="form-control" name="username" required></div>
<div class="mb-3"><label>Password</label><input class="form-control" type="password" name="password" required></div>
<div class="mb-3"><label>Role</label><select class="form-select" name="role">
<option value="admin">Admin</option><option value="encoder">Encoder</option><option value="artist">Artist</option><option value="printer">Printer</option>
<option value="heatpress">Heat Press</option><option value="cutting">Cutting</option><option value="sewing">Sewing</option><option value="qc">QC</option><option value="sales">Sales</option>
</select></div><button class="btn btn-primary">Create</button></form>
<?php include __DIR__ . '/../templates/footer.php'; ?>