<?php
include __DIR__ . "/../config/db.php";


// ------------------- GENERATE JO NUMBER FOR DISPLAY -------------------
$display_jo_number = "0000001";

$joQuery = $conn->query("SELECT jo_number FROM job_orders ORDER BY jo_number DESC LIMIT 1");
if ($joQuery && $joQuery->num_rows > 0) {
    $row = $joQuery->fetch_assoc();
    $last = intval($row['jo_number']);
    $next = $last + 1;
    $display_jo_number = str_pad($next, 7, "0", STR_PAD_LEFT);
}


// ------------------- WHEN SUBMITTING FORM -------------------
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // ---------- HEADER INPUTS ----------
    $customer = trim($_POST["customer_name"]);
    $contact = trim($_POST["customer_number"]);
    $date_ordered = $_POST["date_ordered"];
    $deadline = $_POST["deadline"];
    $product_type = intval($_POST["product_type"]);
    $team_name = trim($_POST["team_name"]);
    $desc = trim($_POST["item_description"]);
    $created_by = $_SESSION["user"]["id"];

    // Lineup arrays
    $team_names = $_POST["lineup_team_name"] ?? [];
    $jersey_numbers = $_POST["lineup_jersey_number"] ?? [];
    $sizes = $_POST["lineup_size"] ?? [];
    $genders = $_POST["lineup_gender"] ?? [];

    $quantity = count($team_names);

    if ($customer === "" || $contact === "" || $quantity === 0) {
        $_SESSION["error"] = "Please fill all required fields and add lineup entries.";
        header("Location: create.php");
        exit();
    }


    // ---------- FILE UPLOAD ----------
    $filePath = null;

    if (!empty($_FILES["mockup_design"]["name"])) {
        $targetDir = __DIR__ . "/../uploads/mockups/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $filename = time() . "_" . basename($_FILES["mockup_design"]["name"]);
        $targetFile = $targetDir . $filename;

        if (move_uploaded_file($_FILES["mockup_design"]["tmp_name"], $targetFile)) {
            $filePath = "uploads/mockups/" . $filename;
        }
    }


    // ------------------- GENERATE NEXT JO NUMBER -------------------
    $joQuery = $conn->query("SELECT jo_number FROM job_orders ORDER BY jo_number DESC LIMIT 1");

    if ($joQuery && $joQuery->num_rows > 0) {
        $row = $joQuery->fetch_assoc();
        $lastNumber = intval($row["jo_number"]);
        $nextNumber = $lastNumber + 1;
    } else {
        $nextNumber = 1;
    }

    $jo_number = str_pad($nextNumber, 7, "0", STR_PAD_LEFT);


    // ------------------- INSERT JOB ORDER HEADER -------------------
    $stmt = $conn->prepare("
        INSERT INTO job_orders 
        (jo_number, customer_name, contact_num, date_ordered, deadline, item_description, file_upload, 
        product_type, team_name, quantity, status, created_by) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending Layout', ?)
    ");

    $stmt->bind_param(
        "sssssssisii", // s - string, i - integer
        $jo_number,
        $customer,
        $contact,
        $date_ordered,
        $deadline,
        $desc,
        $filePath,
        $product_type,
        $team_name,
        $quantity,
        $created_by
    );

    $stmt->execute();
    $jo_id = $stmt->insert_id;
    $stmt->close();


    // ------------------- AUDIT LOG -------------------
    $al = $conn->prepare("
        INSERT INTO audit_logs (jo_id, status, updated_by) 
        VALUES (?, 'Pending Layout', ?)
    ");
    $al->bind_param("ii", $jo_id, $created_by);
    $al->execute();
    $al->close();


    // ------------------- LINEUP INSERT -------------------
    $lineupSQL = $conn->prepare("
        INSERT INTO job_orders_lineup 
        (jo_id, jo_lineup_name, jo_lineup_jersey_no, jo_lineup_size, jo_lineup_gender)
        VALUES (?, ?, ?, ?, ?)
    ");

    for ($i = 0; $i < $quantity; $i++) {
        $lineupSQL->bind_param("issss", $jo_id, $team_names[$i], $jersey_numbers[$i], $sizes[$i], $genders[$i]);
        $lineupSQL->execute();
    }

    $lineupSQL->close();

    $_SESSION["success"] = "Job Order #{$jo_number} created successfully.";
    header("Location: list.php");
    exit();
}
?>

<form method="POST" enctype="multipart/form-data" class="row g-3">
  <div class="row p-3">

    <div class="col-md-4">
        <div class="col-md-12">
            <label class="form-label">Customer</label>
            <input type="text" class="form-control" name="customer_name" required>
        </div>

        <div class="col-md-12">
            <label class="form-label">Contact #</label>
            <input type="text" class="form-control" name="customer_number" required>
        </div>

        <div class="col-md-12">
            <label class="form-label">Date Ordered</label>
            <input type="date" class="form-control" name="date_ordered" required>
        </div>

        <div class="col-md-12">
            <label class="form-label">Deadline</label>
            <input class="form-control" type="date" name="deadline" required>
        </div>

        <div class="col-md-12">
            <label class="form-label">Product Type</label>
            <select name="product_type" class="form-control" required>
                <option value="1">Jersey</option>
                <option value="2">T-shirt</option>
            </select>
        </div>

        <div class="col-md-12">
            <label class="form-label">Team Name</label>
            <input class="form-control" type="text" name="team_name" required>
        </div>

        <div class="col-12">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="item_description" rows="3" required></textarea>
        </div>

        <div class="col-12">
            <label class="form-label">Mock-up Design</label>
            <input type="file" class="form-control" name="mockup_design" accept="image/*,.pdf">
        </div>

        <div class="col-12">
            <button class="btn btn-primary mt-3">Create</button>
        </div>

    </div>

    <!-- RIGHT SIDE LINEUP -->
    <div class="col-md-8">
        <h3>JO Number : <?php echo htmlspecialchars($display_jo_number); ?></h3>        

        <div class="row mt-2 lineup-row">
            <div class="col-md-1 d-flex align-items-center fw-bold lineup-number">#</div>
            <div class="col-md-3"><label>Team Name</label></div>
            <div class="col-md-3"><label>Jersey #</label></div>
            <div class="col-md-2"><label>Size</label></div>
            <div class="col-md-2"><label>Gender</label></div>
            <div class="col-md-1 d-flex align-items-center"><label>/</label></div>
        </div>

        <div class="col-md-12">
            <div id="lineup-entries" class="lineup-entries-container" style="max-height: 300px; overflow-y: auto; border:1px solid #ddd; padding:10px;">
            </div>
        </div>

        <div class="col-md-12 text-center mt-3 mb-3">
            <button type="button" class="btn btn-secondary" id="add-lineup-entry">Add Lineup Entry</button>
        </div>
    </div>

</div>
</form>



<script>
document.addEventListener("DOMContentLoaded", function () {
    const addBtn = document.getElementById("add-lineup-entry");
    const container = document.getElementById("lineup-entries");

    function updateNumbers() {
        const rows = container.querySelectorAll(".lineup-row");
        rows.forEach((row, index) => {
            row.querySelector(".lineup-number").innerText = (index + 1) + ".";
        });
    }

    function createLineupRow() {
        const row = document.createElement("div");
        row.classList.add("row", "mt-2", "lineup-row");

        row.innerHTML = `
            <div class="col-md-1 d-flex align-items-center fw-bold lineup-number">#</div>

            <div class="col-md-3">
                <input type="text" name="lineup_team_name[]" class="form-control" placeholder="Team Name" required>
            </div>

            <div class="col-md-3">
                <input type="text" name="lineup_jersey_number[]" class="form-control" placeholder="# Number" required>
            </div>

            <div class="col-md-2">
                <select name="lineup_size[]" class="form-control" required>
                    <option value="">Size</option>
                    <option value="XS">XS</option>
                    <option value="Small">Small</option>
                    <option value="Medium">Medium</option>
                    <option value="Large">Large</option>
                    <option value="XL">XL</option>
                    <option value="2XL">2XL</option>
                    <option value="3XL">3XL</option>
                </select>
            </div>

            <div class="col-md-2">
                <select name="lineup_gender[]" class="form-control" required>
                    <option value="">Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Unisex">Unisex</option>
                </select>
            </div>

            <div class="col-md-1 d-flex align-items-center">
                <button type="button" class="btn btn-danger btn-sm remove-entry">&times;</button>
            </div>
        `;

        row.querySelector(".remove-entry").addEventListener("click", function () {
            row.remove();
            updateNumbers();
        });

        return row;
    }

    addBtn.addEventListener("click", function () {
        container.appendChild(createLineupRow());
        updateNumbers();
    });
});
</script>


