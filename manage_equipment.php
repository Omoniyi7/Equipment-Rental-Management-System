<?php
session_start();
include("../config/db.php");

if ($_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// FETCH CATEGORIES
$categories = mysqli_query($conn, "SELECT * FROM categories");

// ADD EQUIPMENT
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $serial = $_POST['serial'];
    $condition = $_POST['condition'];
    $quantity = $_POST['quantity'];

    mysqli_query($conn, "INSERT INTO equipment (name, category_id, serial_number, condition_status, quantity, available_quantity)
    VALUES ('$name', '$category', '$serial', '$condition', '$quantity', '$quantity')");
}

// DELETE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM equipment WHERE equipment_id=$id");
}

// EDIT FETCH
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $editData = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM equipment WHERE equipment_id=$id"));
}

// UPDATE
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $serial = $_POST['serial'];
    $condition = $_POST['condition'];
    $quantity = $_POST['quantity'];

    mysqli_query($conn, "UPDATE equipment SET 
        name='$name',
        category_id='$category',
        serial_number='$serial',
        condition_status='$condition',
        quantity='$quantity',
        available_quantity='$quantity'
        WHERE equipment_id=$id");
}

// FETCH EQUIPMENT + CATEGORY NAME
$result = mysqli_query($conn, "
    SELECT equipment.*, categories.name AS category_name 
    FROM equipment 
    LEFT JOIN categories ON equipment.category_id = categories.category_id
");
?>

<?php include("../includes/header.php"); ?>

<div class="container mt-5">

    <h2 class="mb-4">📦 Manage Equipment</h2>

    <!-- FORM -->
    <div class="card shadow p-4 mb-4">
        <h5><?php echo $editData ? "✏️ Edit Equipment" : "➕ Add Equipment"; ?></h5>

        <form method="POST" class="row g-3">

            <input type="hidden" name="id" value="<?php echo $editData['equipment_id'] ?? ''; ?>">

            <div class="col-md-3">
                <input type="text" name="name" class="form-control" placeholder="Name"
                value="<?php echo $editData['name'] ?? ''; ?>" required>
            </div>

            <div class="col-md-3">
                <select name="category" class="form-control" required>
                    <option value="">Select Category</option>
                    <?php while ($cat = mysqli_fetch_assoc($categories)) { ?>
                        <option value="<?php echo $cat['category_id']; ?>"
                        <?php if (($editData['category_id'] ?? '') == $cat['category_id']) echo 'selected'; ?>>
                            <?php echo $cat['name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-md-2">
                <input type="text" name="serial" class="form-control" placeholder="Serial"
                value="<?php echo $editData['serial_number'] ?? ''; ?>">
            </div>

            <div class="col-md-2">
                <input type="text" name="condition" class="form-control" placeholder="Condition"
                value="<?php echo $editData['condition_status'] ?? ''; ?>">
            </div>

            <div class="col-md-1">
                <input type="number" name="quantity" class="form-control" placeholder="Qty"
                value="<?php echo $editData['quantity'] ?? ''; ?>" required>
            </div>

            <div class="col-md-1">
                <?php if ($editData) { ?>
                    <button name="update" class="btn btn-warning w-100">Update</button>
                <?php } else { ?>
                    <button name="add" class="btn btn-success w-100">Add</button>
                <?php } ?>
            </div>

        </form>
    </div>

    <!-- TABLE -->
    <div class="card shadow p-4">
        <h5>📋 Equipment List</h5>

        <table class="table table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Serial</th>
                    <th>Condition</th>
                    <th>Total</th>
                    <th>Available</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['equipment_id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['category_name']; ?></td>
                    <td><?php echo $row['serial_number']; ?></td>
                    <td><?php echo $row['condition_status']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['available_quantity']; ?></td>

                    <td>
                        <a href="?edit=<?php echo $row['equipment_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="?delete=<?php echo $row['equipment_id']; ?>" class="btn btn-sm btn-danger"
                        onclick="return confirm('Delete this item?')">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>

        </table>
    </div>

</div>

<?php include("../includes/footer.php"); ?>