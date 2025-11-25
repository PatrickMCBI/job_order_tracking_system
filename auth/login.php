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
<html>
  <head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/bootstrap5_3_3.min.css">
    <link rel="stylesheet" href="../assets/css/bs-brain-login.min.css">
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    
  </head>
  <body class="p-4">

    <!-- Login 3 - Bootstrap Brain Component -->
    <section class="p-3 p-md-4 p-xl-5">
      <div class="container">
        <div class="row">
          <div class="col-12 col-md-6 bsb-tpl-bg-platinum">
            <div class="d-flex flex-column justify-content-between h-100 p-3 p-md-4 p-xl-5">
              <h3 class="m-0">Welcome!</h3>
              <img class="img-fluid rounded mx-auto my-4" loading="lazy" src="./assets/img/bsb-logo.svg" width="245" height="80" alt="BootstrapBrain Logo">
              <p class="mb-0">Zeal Print Apparel</p>
            </div>
          </div>
          <div class="col-12 col-md-6 bsb-tpl-bg-lotion">
            <div class="p-3 p-md-4 p-xl-5">
              <div class="row">
                <div class="col-12">
                  <div class="mb-5">
                    <h3>Log in</h3>
                  </div>
                </div>
              </div>
              <form method="POST">
                <?php if(!empty($error)) echo '<p style="color:red;">'.htmlspecialchars($error).'</p>'; ?>

                <div class="row gy-3 gy-md-4 overflow-hidden">
                  <div class="col-12">
                    <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="username" required>
                  </div>
                  <div class="col-12">
                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" name="password" id="password" value="" required>
                  </div>
                  <div class="col-12">
                    <div class="d-grid">
                      <button class="btn bsb-btn-xl btn-primary" type="submit">Login</button>
                    </div>
                  </div>
                </div>
              </form>
             
             
            </div>
          </div>
        </div>
      </div>
    </section>
  </body>
</html>
