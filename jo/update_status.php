<?php
session_start();
include __DIR__ . '/../config/db.php';
if(!isset($_SESSION['user'])){ header('Location:/auth/login.php'); exit(); }
if($_SERVER['REQUEST_METHOD'] !== 'POST'){ header('Location:/jo/list.php'); exit(); }
$job_id = intval($_POST['job_id'] ?? 0); $new_status = trim($_POST['status'] ?? ''); $comment = trim($_POST['comment'] ?? '');
$user_id = $_SESSION['user']['id']; $user_role = $_SESSION['user']['role'];
if($job_id<=0 || $new_status===''){ $_SESSION['error']='Invalid input'; header("Location:/jo/view.php?id={$job_id}"); exit(); }
$s = $conn->prepare("SELECT status, customer_name FROM job_orders WHERE id = ?"); $s->bind_param("i", $job_id); $s->execute(); $row = $s->get_result()->fetch_assoc(); $s->close();
if(!$row){ $_SESSION['error']='JO not found'; header('Location:/jo/list.php'); exit(); }
$old_status = $row['status']; $customer = $row['customer_name'];
$role_allowed_map = ['encoder'=>["Pending Layout","In Layout","Sizing"],'artist'=>["Pending Layout","In Layout","Sizing","Ready for Printing"],
'printer'=>["Ready for Printing","Printing"],'heatpress'=>["Ready for Heat Pressing","Pressing"],'cutting'=>["Ready for Cutting","Cutting"],
'sewing'=>["Ready for Sewing","In Sewing","Done Sewing"],'qc'=>["Done Sewing","QC Checking"],'sales'=>[]];
$can_update = in_array($old_status, $role_allowed_map[$user_role] ?? []) || $user_role==='admin';
if(!$can_update){ $_SESSION['error']='Not allowed to update'; header("Location:/jo/view.php?id={$job_id}"); exit(); }
$u = $conn->prepare("UPDATE job_orders SET status = ?, updated_at = NOW() WHERE id = ?"); $u->bind_param("si", $new_status, $job_id); $ok = $u->execute(); $u->close();
if(!$ok){ $_SESSION['error']='Update failed'; header("Location:/jo/view.php?id={$job_id}"); exit(); }
$al = $conn->prepare("INSERT INTO audit_logs (jo_id, status, updated_by) VALUES (?, ?, ?)"); $al->bind_param("isi", $job_id, $new_status, $user_id); $al->execute(); $al->close();
$next_role_map = ['Pending Layout'=>'artist','In Layout'=>'artist','Sizing'=>'printer','Ready for Printing'=>'printer','Printing'=>'heatpress',
'Ready for Heat Pressing'=>'heatpress','Pressing'=>'cutting','Ready for Cutting'=>'cutting','Cutting'=>'sewing','Ready for Sewing'=>'sewing',
'In Sewing'=>'qc','Done Sewing'=>'qc','QC Checking'=>'sales','Ready for Pickup'=>'sales'];
$next_role = $next_role_map[$new_status] ?? null;
if($next_role){
  $q = $conn->prepare("SELECT id FROM users WHERE role = ?"); $q->bind_param("s", $next_role); $q->execute(); $res = $q->get_result();
  $message = "JO#{$job_id} ({$customer}) status changed to '{$new_status}'";
  while($u = $res->fetch_assoc()){ $ins = $conn->prepare("INSERT INTO notifications (user_id, job_order_id, message) VALUES (?, ?, ?)"); $ins->bind_param("iis", $u['id'], $job_id, $message); $ins->execute(); $ins->close(); }
  $q->close();
}
$_SESSION['success'] = "Status updated to {$new_status}."; header("Location:/jo/view.php?id={$job_id}"); exit();
?>