<?php
session_start();
include __DIR__ . "/../config/db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: ../dashboard/admin.php");
  exit();
}

$id = intval($_POST['id'] ?? 0);
$status = $_POST['status'] ?? '';
$user_id = $_SESSION['user']['id'] ?? 0;

$allowed = [
  'Pending Layout',
  'In Layout',
  'Ready for Printing',
  'Printing',
  'Printed',
  'Heat Pressing',
  'Cutting & Pairing',
  'Sewing',
  'QC & Packing',
  'Ready for Release',
  'Done'
];

if (!in_array($status, $allowed)) {
  die("Invalid status");
}

// UPDATE STATUS
$stmt = $conn->prepare("UPDATE job_orders SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $id);
$stmt->execute();
$stmt->close();

// AUDIT LOG
$al = $conn->prepare("
  INSERT INTO audit_logs (jo_id, status, updated_by)
  VALUES (?, ?, ?)
");
$al->bind_param("isi", $id, $status, $user_id);
$al->execute();
$al->close();

header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '../dashboard/admin.php'));
exit();
