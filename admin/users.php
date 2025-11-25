<?php
session_start();
include __DIR__ . '/../config/db.php';
if(!isset($_SESSION['user']) || $_SESSION['user']['role']!=='admin'){ header('Location:/job_order_tracking_system/auth/login.php'); exit(); }
include __DIR__ . '/../templates/header.php';
$res = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<div class="row">
      
      <!-- Sidebar -->
      <nav class="col-md-3 col-lg-2 bg-light p-3 vh-100 d-flex flex-column align-items-center">
        
        <!-- Profile image -->
        <img src="https://via.placeholder.com/100" 
             class="rounded-circle mb-3" 
             alt="Profile Image">
        
        <!-- Profile name -->
        <h5 class="mb-4"><?= htmlspecialchars($_SESSION['user']['username']) ?></h5>

        <!-- Navigation links -->
        <ul class="nav flex-column w-100">
          <li class="nav-item"><a class="nav-link active" href="#">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Profile</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Settings</a></li>
          <li class="nav-item">
            <a class="nav-link" href="/job_order_tracking_system/auth/logout.php">Logout</a>
          </li>
        </ul>
      </nav>

      <!-- Content -->
      <main class="col-md-9 col-lg-10 p-4">
        <h2>User Management</h2><a class="btn btn-primary mb-2" href="/job_order_tracking_system/admin/add_user.php">Add User</a>
<table class="table"><thead><tr><th>ID</th><th>Username</th><th>Role</th><th>Actions</th></tr></thead><tbody>
<?php while($u = $res->fetch_assoc()): ?>
<tr><td><?= $u['id'] ?></td><td><?= htmlspecialchars($u['username']) ?></td><td><?= htmlspecialchars($u['role']) ?></td>
<td><a class="btn btn-sm btn-secondary" href="/job_order_tracking_system/admin/edit_user.php?id=<?= $u['id'] ?>">Edit</a> <a class="btn btn-sm btn-danger" href="/job_order_tracking_system/admin/delete_user.php?id=<?= $u['id'] ?>" onclick="return confirm('Delete user?')">Delete</a></td></tr>
<?php endwhile; ?></tbody></table>
      </main>

    </div>
<?php include __DIR__ . '/../templates/footer.php'; ?>