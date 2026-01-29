<?php
session_start();
include __DIR__ . '/../config/db.php';
if(!isset($_SESSION['user']) || $_SESSION['user']['role']!=='admin'){ 
    header('Location:/job_order_tracking_system/auth/login.php'); exit(); 
}
include __DIR__ . '/../templates/header.php';

// Users
$res = $conn->query("SELECT * FROM users ORDER BY id DESC");

// Jobs for monitoring
$jobs = $conn->query("SELECT * FROM job_orders ORDER BY created_at DESC");
?>

<div class="row">

<nav class="col-md-3 col-lg-2 bg-light p-3 vh-100 d-flex flex-column align-items-center">
  <img src="https://via.placeholder.com/100" class="rounded-circle mb-3" alt="Profile Image">
  <h5 class="mb-4"><?= htmlspecialchars($_SESSION['user']['username']) ?></h5>
  <ul class="nav flex-column w-100">
    <li class="nav-item"><a class="nav-link active" href="#">Dashboard</a></li>
    <li class="nav-item"><a class="nav-link" href="#">Profile</a></li>
    <li class="nav-item"><a class="nav-link" href="#">Settings</a></li>
    <li class="nav-item"><a class="nav-link" href="../auth/logout.php">Logout</a></li>
  </ul>
  <?php include '../dashboard/notifications_sidebar.php'; ?>
</nav>

<main class="col-md-9 col-lg-10 p-4">

  <h2>User Management</h2>
  <a class="btn btn-primary mb-2" href="/job_order_tracking_system/admin/add_user.php">Add User</a>
  <table class="table mb-5">
    <thead>
      <tr><th>ID</th><th>Username</th><th>Role</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php while($u = $res->fetch_assoc()): ?>
      <tr>
        <td><?= $u['id'] ?></td>
        <td><?= htmlspecialchars($u['username']) ?></td>
        <td><?= htmlspecialchars($u['role']) ?></td>
        <td>
          <a class="btn btn-sm btn-secondary" href="/job_order_tracking_system/admin/edit_user.php?id=<?= $u['id'] ?>">Edit</a>
          <a class="btn btn-sm btn-danger" href="/job_order_tracking_system/admin/delete_user.php?id=<?= $u['id'] ?>" onclick="return confirm('Delete user?')">Delete</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <!-- Job Monitoring -->
  <h2>Workflow Monitoring</h2>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Status</th>
        <th>Deadline</th>
        <th>Current Stage</th>
        <th>Next Action</th>
      </tr>
    </thead>
    <tbody>
    <?php 
    $stageMap = [
        'Pending Layout'=>'Graphic Artist',
        'In Layout'=>'Graphic Artist',
        'Ready for Printing'=>'Printer',
        'Done Printing'=>'Heat Press',
        'In Cutting'=>'Cutting',
        'In Sewing'=>'Sewing',
        'In QC'=>'QC & Packing',
        'Ready for Sales'=>'Sales',
        'Done'=>'Completed'
    ];

    $nextStatusMap = [
        'Pending Layout'=>'In Layout',
        'In Layout'=>'Ready for Printing',
        'Ready for Printing'=>'Done Printing',
        'Done Printing'=>'In Cutting',
        'In Cutting'=>'In Sewing',
        'In Sewing'=>'In QC',
        'In QC'=>'Ready for Sales',
        'Ready for Sales'=>'Done'
    ];

    $counter=1;
    while($row = $jobs->fetch_assoc()):
        $currentStage = $stageMap[$row['status']] ?? 'Unknown';
        $nextAction = isset($nextStatusMap[$row['status']]) 
            ? "<a href='../jo/update_status.php?id={$row['id']}&status={$nextStatusMap[$row['status']]}' class='btn btn-sm btn-success'>Move to Next Stage</a>" 
            : "<span class='text-success'>Completed</span>";
    ?>
      <tr>
        <td><?= $counter ?></td>
        <td><?= htmlspecialchars($row['customer_name']) ?></td>
        <td><?= htmlspecialchars($row['status']) ?></td>
        <td><?= $row['deadline'] ?></td>
        <td><?= $currentStage ?></td>
        <td><?= $nextAction ?></td>
      </tr>
    <?php 
        $counter++;
    endwhile; ?>
    </tbody>
  </table>

</main>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
<script src="../jo/notifications.js"></script>

