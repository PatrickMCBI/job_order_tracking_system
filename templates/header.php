<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user'])) { header("Location: /job_order_tracking_system/auth/login.php"); exit(); }
include __DIR__ . '/../config/db.php';
$stmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM notifications WHERE user_id = ? AND is_read = 0");
$stmt->bind_param("i", $_SESSION['user']['id']);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$unread_count = intval($res['cnt'] ?? 0);
$stmt->close();
?>
<!Doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Job Order System</title>
    <link rel="stylesheet" href="../assets/css/bootstrap5_3_3.min.css">
    <link rel="stylesheet" href=" /job_order_tracking_system/assets/css/main.css ">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #5b6d87;">
      <div class="container">
        <a class="navbar-brand" href="/job_order_tracking_system/index.php" style="color: white;">Job Order System</a>
        <div class="d-flex">
          <a class="position-relative" href="/job_order_tracking_system/notifications.php"><img src="../assets/img/bell-z.png" alt="notifications">
          <?php if($unread_count>0): ?><span class="badge bg-danger"><?= $unread_count ?></span><?php endif; ?></a>
          
          
        </div>
      </div>
    </nav>
    <div class="container-fluid">
    <?php if(!empty($_SESSION['success'])): ?>
      <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION['error'])): ?>
      <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
