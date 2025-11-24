<?php
session_start();
include __DIR__ . '/../config/db.php';
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'encoder'){ header("Location: job_order_tracking_system/auth/login.php"); exit(); }
include __DIR__ . '/../templates/header.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $customer = trim($_POST['customer_name']);
  $desc = trim($_POST['item_description']);
  $qty = intval($_POST['quantity']);
  $deadline = $_POST['deadline'];
  $created_by = $_SESSION['user']['id'];
  if($customer==='' || $qty<=0){ $_SESSION['error']="Please fill required fields."; header("Location:job_order_tracking_system/jo/create.php"); exit();}
  $stmt = $conn->prepare("INSERT INTO job_orders (customer_name, item_description, quantity, deadline, status, created_by) VALUES (?, ?, ?, ?, 'Pending Layout', ?)");
  $stmt->bind_param("ssisi",$customer,$desc,$qty,$deadline,$created_by); $stmt->execute();
  $jo_id = $stmt->insert_id; $stmt->close();
  $al = $conn->prepare("INSERT INTO audit_logs (jo_id, status, updated_by) VALUES (?, ?, ?)");
  $init = 'Pending Layout'; $al->bind_param("isi",$jo_id,$init,$created_by); $al->execute(); $al->close();
  $_SESSION['success']="Job Order created (JO#{$jo_id})."; header("Location:job_order_tracking_system/jo/list.php"); exit();
}
?>
<h2>Create Job Order</h2>
<div class="rows">
  <div class="col-md-6">
    <form method="POST" class="row g-3">
      <div class="col-md-12"><label class="form-label">Customer</label><input type="text" class="form-control" name="customer_name" required></div>

      <div class="col-md-12"><label class="form-label">Contact #</label><input type="text" class="form-control" name="customer_number" required></div>
   
      <div class="col-md-12"><label class="form-label">Date Ordered</label><input type="date" class="form-control" name="date_ordered" required></div>

      <div class="col-md-12"><label class="form-label">Deadline</label><input class="form-control" type="date" name="deadline" required></div>

      <div class="col-md-12">
        <label class="form-label">Product Type</label>
        <select name="product_type" class="form-control" required>
          <option value="1">Jersey</option>
          <option value="1">T-shirt</option>
        </select>
      </div>

      <div class="col-md-12"><label class="form-label">Team Name</label><input class="form-control" type="text" name="team_name" required></div>
      
      <div class="col-md-12"><label class="form-label">Quantity</label><input class="form-control" type="number" name="quantity" required readonly></div>

      <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="item_description" rows="3" required></textarea></div>

      <div class="col-12">
        <label class="form-label">Mock-up Design</label>
        <input type="file" class="form-control" name="mockup_design" accept="image/*,.pdf">
      </div>


      <div class="col-12"><button class="btn btn-primary">Create</button></div>
    </form>
  </div>
  <div class="col-md-6">
    

  </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>