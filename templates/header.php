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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href=" /job_order_tracking_system/assets/css/main.css ">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container">
        <a class="navbar-brand" href="/job_order_tracking_system/index.php">Job Order System</a>
        <div class="d-flex">
          <a class="btn btn-outline-primary me-2 position-relative" href="/job_order_tracking_system/notifications.php">Notifications
          <?php if($unread_count>0): ?><span class="badge bg-danger"><?= $unread_count ?></span><?php endif; ?></a>
          <div class="me-3">Signed in as <strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong></div>
          <a class="btn btn-outline-secondary" href="/job_order_tracking_system/auth/logout.php">Logout</a>
        </div>
      </div>
    </nav>
    <div class="container mt-4">
    <?php if(!empty($_SESSION['success'])): ?>
      <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if(!empty($_SESSION['error'])): ?>
      <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
