<?php
if(!isset($_SESSION['user'])) return;
$userId = $_SESSION['user']['id'];

include __DIR__ . '/../config/db.php';

// Get unread notifications
$notifStmt = $conn->prepare("SELECT * FROM notifications WHERE user_id=? ORDER BY created_at DESC LIMIT 5");
$notifStmt->bind_param("i", $userId);
$notifStmt->execute();
$notifications = $notifStmt->get_result();
?>

<div class="mt-auto w-100">
    <h6 class="mt-3">Notifications</h6>
    <ul class="list-group" id="notifList">
        <?php while($n = $notifications->fetch_assoc()): ?>
            <li class="list-group-item <?php if($n['is_read']==0) echo 'list-group-item-warning'; ?>">
                <?= htmlspecialchars($n['message']) ?>
                <small class="text-muted d-block"><?= date('Y-m-d H:i', strtotime($n['created_at'])) ?></small>
            </li>
        <?php endwhile; ?>
    </ul>
    <button class="btn btn-sm btn-link mt-2" id="markAllRead">Mark All as Read</button>
</div>
