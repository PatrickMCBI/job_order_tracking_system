<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'encoder'){
    header("Location: ../auth/login.php"); exit();
}
include '../templates/header.php';
?>
<h1>Encoder Dashboard</h1>
<a href="../jo/create.php" class="btn btn-primary mb-3">Create New Job Order</a>

<h3>Pending Job Orders</h3>
<table class="table table-striped">
<thead><tr><th>ID</th><th>Customer</th><th>Status</th><th>Deadline</th><th>Action</th></tr></thead>
<tbody>
<?php
include '../config/db.php';
$stmt = $conn->query("SELECT * FROM job_orders ORDER BY created_at DESC");
while($row = $stmt->fetch_assoc()){
    echo "<tr>
    <td>{$row['id']}</td>
    <td>{$row['customer_name']}</td>
    <td>{$row['status']}</td>
    <td>{$row['deadline']}</td>
    <td><a href='../jo/view.php?id={$row['id']}' class='btn btn-sm btn-info'>View</a></td>
    </tr>";
}
?>
</tbody>
</table>
<?php include '../templates/footer.php'; ?>
