<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'artist'){
    header("Location: ../auth/login.php"); exit();
}
include '../templates/header.php';
?>
<h1>Graphic Artist Dashboard</h1>
<p>Update jobs in layout/sizing workflow.</p>
<table class="table table-striped">
<thead><tr><th>ID</th><th>Customer</th><th>Status</th><th>Deadline</th><th>Action</th></tr></thead>
<tbody>
<?php
include '../config/db.php';
$stmt = $conn->query("SELECT * FROM job_orders WHERE status IN ('Pending Layout','Sizing') ORDER BY created_at DESC");
while($row = $stmt->fetch_assoc()){
    echo "<tr>
    <td>{$row['id']}</td>
    <td>{$row['customer_name']}</td>
    <td>{$row['status']}</td>
    <td>{$row['deadline']}</td>
    <td>
        <a href='../jo/update_status.php?id={$row['id']}&status=In Layout' class='btn btn-sm btn-warning'>In Layout</a>
        <a href='../jo/update_status.php?id={$row['id']}&status=Ready for Printing' class='btn btn-sm btn-success'>Ready for Printing</a>
    </td>
    </tr>";
}
?>
</tbody>
</table>
<?php include '../templates/footer.php'; ?>
