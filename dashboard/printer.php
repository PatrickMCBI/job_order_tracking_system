<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role']!=='printer'){
    header("Location: ../auth/login.php"); exit();
}
include '../templates/header.php';
include '../config/db.php';
?>
<div class="row">
<nav class="col-md-3 col-lg-2 p-3 vh-100 d-flex flex-column align-items-center" style="background-color:#1d2935; color:white;">
<img src="../assets/img/default_profile.png" class="rounded-circle mb-3" alt="Profile Image">
<h5 class="mb-4">Hello, <?= htmlspecialchars($_SESSION['user']['username']) ?>!</h5>
<ul class="nav flex-column w-100">
<li class="nav-item"><a class="nav-link active" href="#">Dashboard</a></li>
<li class="nav-item"><a class="nav-link" href="#">Profile</a></li>
<li class="nav-item"><a class="nav-link" href="#">Settings</a></li>
<li class="nav-item"><a class="nav-link" href="../auth/logout.php">Logout</a></li>
</ul>
<?php include 'notifications_sidebar.php'; ?>
</nav>
<main class="col-md-9 col-lg-10 p-4">
<h1>Printer Dashboard</h1>
<h3>Ready for Printing</h3>
<table class="table table-striped">
<thead>
<tr><th>ID</th><th>Customer</th><th>Status</th><th>Deadline</th><th>Action</th></tr>
</thead>
<tbody>
<?php
$stmt=$conn->query("SELECT * FROM job_orders WHERE status='Ready for Printing' ORDER BY created_at DESC");
$counter=1;
while($row=$stmt->fetch_assoc()){
  echo "<tr>
  <td>{$counter}</td>
  <td>{$row['customer_name']}</td>
  <td>{$row['status']}</td>
  <td>{$row['deadline']}</td>
  <td>
    <a href='../jo/update_status.php?id={$row['id']}&status=In Heat Press' class='btn btn-sm btn-warning'>In Heat Press</a>
    <a href='../jo/update_status.php?id={$row['id']}&status=Done Printing' class='btn btn-sm btn-success'>Done Printing</a>
  </td>
  </tr>";
  $counter++;
}
?>
</tbody>
</table>
</main>
</div>
<?php include '../templates/footer.php'; ?>
<script src="../jo/notifications.js"></script>
