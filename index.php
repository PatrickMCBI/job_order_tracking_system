<?php
session_start();

if(!isset($_SESSION['user'])){ header("Location: /job_order_tracking_system/auth/login.php"); exit(); }
$role = $_SESSION['user']['role'] ?? '';
switch($role){
  case 'encoder': header("Location: /job_order_tracking_system/dashboard/encoder.php"); break;
  case 'artist': header("Location: /job_order_tracking_system/dashboard/artist.php"); break;
  case 'printer': header("Location: /job_order_tracking_system/dashboard/printer.php"); break;
  case 'heatpress': header("Location: /job_order_tracking_system/dashboard/heatpress.php"); break;
  case 'cutting': header("Location: /job_order_tracking_system/dashboard/cutting.php"); break;
  case 'sewing': header("Location: /job_order_tracking_system/dashboard/sewing.php"); break;
  case 'qc': header("Location: /job_order_tracking_system/dashboard/qc.php"); break;
  case 'sales': header("Location: /job_order_tracking_system/dashboard/sales.php"); break;
  case 'admin': header("Location: /job_order_tracking_system/admin/users.php"); break;
  default: header("Location: /job_order_tracking_system/jo/list.php"); break;
}
exit();
?>