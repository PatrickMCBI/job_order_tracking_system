<?php
session_start();
include __DIR__ . "/../config/db.php";

$id = intval($_GET['id'] ?? 0);

// HEADER
$stmt = $conn->prepare("SELECT * FROM job_orders WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$jo = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$jo) {
  echo "<div class='alert alert-danger'>Job Order not found.</div>";
  exit();
}

// LINEUP
$lineup = [];
$lq = $conn->prepare("SELECT * FROM job_orders_lineup WHERE jo_id = ?");
$lq->bind_param("i", $id);
$lq->execute();
$res = $lq->get_result();
while ($r = $res->fetch_assoc()) {
  $lineup[] = $r;
}
$lq->close();
?>

<div class="row g-3">

  <div class="col-md-4">
    <div class="mb-2"><strong>Customer:</strong> <?= htmlspecialchars($jo['customer_name']) ?></div>
    <div class="mb-2"><strong>Contact #:</strong> <?= htmlspecialchars($jo['contact_num']) ?></div>
    <div class="mb-2"><strong>Date Ordered:</strong> <?= $jo['date_ordered'] ?></div>
    <div class="mb-2"><strong>Deadline:</strong> <?= $jo['deadline'] ?></div>
    <div class="mb-2"><strong>Product Type:</strong> <?= $jo['product_type'] == 1 ? 'Jersey' : 'T-shirt' ?></div>
    <div class="mb-2"><strong>Team Name:</strong> <?= htmlspecialchars($jo['team_name']) ?></div>
    <div class="mb-2"><strong>Status:</strong> 
      <span class="badge bg-warning"><?= htmlspecialchars($jo['status']) ?></span>
    </div>
    <div class="mb-2"><strong>Description:</strong><br>
      <?= nl2br(htmlspecialchars($jo['item_description'])) ?>
    </div>

    <?php if ($jo['file_upload']): ?>
      <div class="mt-3">
        <strong>Mockup:</strong><br>
        <a href="../<?= $jo['file_upload'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">
          View Mockup File
        </a>
      </div>
    <?php endif; ?>
  </div>

  <div class="col-md-8">
    <h5>JO Number: <?= htmlspecialchars($jo['jo_number']) ?></h5>

    <table class="table table-bordered table-sm mt-3">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Team Name</th>
          <th>Jersey #</th>
          <th>Size</th>
          <th>Gender</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($lineup as $i => $l): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($l['jo_lineup_name']) ?></td>
            <td><?= htmlspecialchars($l['jo_lineup_jersey_no']) ?></td>
            <td><?= htmlspecialchars($l['jo_lineup_size']) ?></td>
            <td><?= htmlspecialchars($l['jo_lineup_gender']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</div>
