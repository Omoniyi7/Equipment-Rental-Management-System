<?php
session_start();
include("../config/db.php");

if ($_SESSION['role'] != 'user') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// HANDLE RETURN
if (isset($_GET['return'])) {
    $rental_id = $_GET['return'];

    // GET RENTAL INFO
    $rental = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM rentals WHERE rental_id=$rental_id"));

    if ($rental && $rental['status'] == 'rented') {

        $equipment_id = $rental['equipment_id'];

        // INCREASE AVAILABLE QUANTITY
        mysqli_query($conn, "UPDATE equipment 
        SET available_quantity = available_quantity + 1 
        WHERE equipment_id=$equipment_id");

        // UPDATE RENTAL RECORD
        mysqli_query($conn, "UPDATE rentals 
        SET return_date = NOW(), status = 'returned' 
        WHERE rental_id=$rental_id");

        $success = "Equipment returned successfully!";
    }
}

// FETCH USER RENTALS
$result = mysqli_query($conn, "
    SELECT rentals.*, equipment.name 
    FROM rentals
    JOIN equipment ON rentals.equipment_id = equipment.equipment_id
    WHERE rentals.user_id = $user_id
");
?>

<?php include("../includes/header.php"); ?>

<div class="container mt-5">

    <h2 class="mb-4">🔄 Return Equipment</h2>

    <?php if (isset($success)) { ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php } ?>

    <div class="card shadow p-4">

        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Equipment</th>
                    <th>Rent Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['rent_date']; ?></td>
                    <td><?php echo $row['due_date']; ?></td>

                    <td>
                        <?php if ($row['status'] == 'returned') { ?>
                            <span class="badge bg-success">Returned</span>
                        <?php } elseif (strtotime($row['due_date']) < time()) { ?>
                            <span class="badge bg-danger">Overdue</span>
                        <?php } else { ?>
                            <span class="badge bg-warning">Rented</span>
                        <?php } ?>
                    </td>

                    <td>
                        <?php if ($row['status'] == 'rented') { ?>
                            <a href="?return=<?php echo $row['rental_id']; ?>" 
                               class="btn btn-primary btn-sm"
                               onclick="return confirm('Return this item?')">
                               Return
                            </a>
                        <?php } else { ?>
                            <button class="btn btn-secondary btn-sm" disabled>Done</button>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>

        </table>

    </div>

</div>

<?php include("../includes/footer.php"); ?>