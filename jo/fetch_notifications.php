<?php
session_start();
include __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

if(!isset($_SESSION['user'])){
    echo json_encode([]); exit;
}

$userId = $_SESSION['user']['id'];

$stmt = $conn->prepare("SELECT message, is_read, created_at FROM notifications WHERE user_id=? ORDER BY created_at DESC LIMIT 5");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$notifs = [];
while($row = $result->fetch_assoc()){
    $notifs[] = $row;
}

echo json_encode($notifs);
