<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role']!=='encoder'){
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
<h1>Encoder Dashboard</h1>
<a href="#" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createJobOrderModal">Create Job Order</a>

<div class="modal fade" id="createJobOrderModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create Job Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <?php include "../jo/create.php"; ?>
      </div>
    </div>
  </div>
</div>

<h3>All Job Orders</h3>
<table class="table table-striped">
<thead>
<tr><th>ID</th><th>Customer</th><th>Status</th><th>Deadline</th><th>Action</th></tr>
</thead>
<tbody>
<?php
$stmt = $conn->query("SELECT * FROM job_orders ORDER BY created_at DESC");
$counter=1;
while($row=$stmt->fetch_assoc()){
    echo "<tr>
    <td>{$counter}</td>
    <td>{$row['customer_name']}</td>
    <td>{$row['status']}</td>
    <td>{$row['deadline']}</td>
    <td><button class='btn btn-sm btn-info view-btn' data-id='{$row['id']}' data-bs-toggle='modal' data-bs-target='#viewJobOrderModal'>View</button></td>
    </tr>";
    $counter++;
}
?>
</tbody>
</table>

<div class="modal fade" id="viewJobOrderModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Job Order Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="viewJobOrderContent">
        <div class="text-center p-5"><div class="spinner-border"></div><p>Loading...</p></div>
      </div>
    </div>
  </div>
</div>

</main>
</div>
<?php include '../templates/footer.php'; ?>
<script src="../jo/notifications.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".view-btn").forEach(btn=>{
    btn.addEventListener("click",function(){
      const joId=this.dataset.id;
      const content=document.getElementById("viewJobOrderContent");
      content.innerHTML=`<div class="text-center p-5"><div class="spinner-border"></div><p>Loading...</p></div>`;
      fetch("../jo/view_modal.php?id="+joId)
      .then(res=>res.text())
      .then(html=>{content.innerHTML=html})
      .catch(()=>{content.innerHTML="<div class='alert alert-danger'>Failed to load job order.</div>"});
    });
  });
});
</script>
