<?php
session_start();
include __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($user && password_verify($password, $user['password'])) {
        // don't store password in session
        unset($user['password']);
        $_SESSION['user'] = $user;
        header("Location: /job_order_tracking_system/index.php");
        exit();
    } else {
        $error = "Invalid credentials";
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Login</title></head>
<body class="p-4">
  <h2>Login</h2>
  <?php if(!empty($error)) echo '<p style="color:red;">'.htmlspecialchars($error).'</p>'; ?>
  <form method="POST">
    <label>Username<br><input name="username" required></label><br><br>
    <label>Password<br><input name="password" type="password" required></label><br><br>
    <button>Login</button>
  </form>
  <p>Need an admin? run <code>php tools/create_admin.php</code> to generate admin user (CLI).</p>
</body></html>
