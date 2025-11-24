<?php
session_start();
include __DIR__ . '/../config/db.php';
if(!isset($_SESSION['user']) || $_SESSION['user']['role']!=='admin'){ header('Location:/job_order_tracking_system/auth/login.php'); exit(); }
$id = intval($_GET['id'] ?? 0);
if($id>0){ $stmt = $conn->prepare('DELETE FROM users WHERE id=?'); $stmt->bind_param('i',$id); $stmt->execute(); $stmt->close(); $_SESSION['success']='User deleted'; }
header('Location: /job_order_tracking_system/admin/users.php'); exit();
?>