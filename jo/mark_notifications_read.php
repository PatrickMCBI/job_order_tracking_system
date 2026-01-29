<?php
session_start();
include __DIR__ . '/../config/db.php';

if(!isset($_SESSION['user'])) exit;

$userId = $_SESSION['user']['id'];

$stmt = $conn->prepare("UPDATE notifications SET is_read=1 WHERE user_id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();

echo 'ok';
