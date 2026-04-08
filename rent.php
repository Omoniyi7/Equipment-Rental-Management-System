<?php
session_start();
include("../config/db.php");

if ($_SESSION['role'] != 'user') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// HANDLE RENT
if (isset($_GET['rent'])) {
    $equipment_id = $_GET['rent'];

    $eq = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM equipment WHERE equipment_id=$equipment_id"));

    if ($eq['available_quantity'] > 0) {

        mysqli_query($conn, "UPDATE equipment 
        SET available_quantity = available_quantity - 1 
        WHERE equipment_id=$equipment_id");

        $due_date = date('Y-m-d H:i:s', strtotime('+7 days'));

        mysqli_query($conn, "INSERT INTO rentals (user_id, equipment_id, quantity, due_date)
        VALUES ($user_id, $equipment_id, 1, '$due_date')");

        $success = "Equipment rented successfully!";
    } else {
        $error = "Item not available!";
    }
}

// SEARCH VARIABLES
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$condition = $_GET['condition'] ?? '';

// BASE QUERY
$query = "
    SELECT equipment.*, categories.name AS category_name 
    FROM equipment 
    LEFT JOIN categories ON equipment.category_id = categories.category_id
    WHERE 1
";

// APPLY FILTERS
if (!empty($search)) {
    $query .= " AND equipment.name LIKE '%$search%'";
}

if (!empty($category)) {
    $query .= " AND equipment.category_id = '$category'";
}

if (!empty($condition)) {
    $query .= " AND equipment.condition_status = '$condition'";
}

// EXECUTE QUERY
$result = mysqli_query($conn, $query);

// FETCH CATEGORIES FOR DROPDOWN
$categories = mysqli_query($conn, "SELECT * FROM categories");
?>

<?php include("../includes/header.php"); ?>

<div class="container mt-5">

    <h2 class="mb-4">🔍 Rent Equipment</h2>

    <?php if (isset($success)) { ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php } ?>

    <?php if (isset($error)) { ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php } ?>

    <!-- SEARCH & FILTER FORM -->
    <div class="card shadow p-4 mb-4">
        <form method="GET" class="row g-3">

            <div class="col-md-4">
                <input type="text" name="search" class="form-control"
                       placeholder="Search equipment..."
                       value="<?php echo $search; ?>">
            </div>

            <div class="col-md-3">
                <select name="category" class="form-control">
                    <option value="">All Categories</option>
                    <?php while ($cat = mysqli_fetch_assoc($categories)) { ?>
                        <option value="<?php echo $cat['category_id']; ?>"
                        <?php if ($category == $cat['category_id']) echo 'selected'; ?>>
                            <?php echo $cat['name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-md-3">
                <select name="condition" class="form-control">
                    <option value="">All Conditions</option>
                    <option value="Excellent" <?php if ($condition == 'Excellent') echo 'selected'; ?>>Excellent</option>
                    <option value="Good" <?php if ($condition == 'Good') echo 'selected'; ?>>Good</option>
                    <option value="Fair" <?php if ($condition == 'Fair') echo 'selected'; ?>>Fair</option>
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary w-100">Search</button>
            </div>

        </form>
    </div>

    <!-- EQUIPMENT TABLE -->
    <div class="card shadow p-4">

        <table class="table table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Condition</th>
                    <th>Available</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['category_name']; ?></td>
                    <td><?php echo $row['condition_status']; ?></td>

                    <td>
                        <?php if ($row['available_quantity'] > 0) { ?>
                            <span class="badge bg-success"><?php echo $row['available_quantity']; ?></span>
                        <?php } else { ?>
                            <span class="badge bg-danger">Out</span>
                        <?php } ?>
                    </td>

                    <td>
                        <?php if ($row['available_quantity'] > 0) { ?>
                            <a href="?rent=<?php echo $row['equipment_id']; ?>" 
                               class="btn btn-success btn-sm">
                               Rent
                            </a>
                        <?php } else { ?>
                            <button class="btn btn-secondary btn-sm" disabled>
                                Unavailable
                            </button>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>

        </table>

    </div>

</div>

<?php include("../includes/footer.php"); ?>