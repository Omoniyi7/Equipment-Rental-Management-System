<?php
session_start();
include("../config/db.php");

if ($_SESSION['role'] != 'user') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$result = mysqli_query($conn, "
    SELECT rentals.*, equipment.name 
    FROM rentals
    JOIN equipment ON rentals.equipment_id = equipment.equipment_id
    WHERE rentals.user_id = $user_id
    ORDER BY rentals.rent_date DESC
");
?>

<?php include("../includes/header.php"); ?>

<div class="container mt-5">

    <h2 class="mb-4">📋 My Rental History</h2>

    <div class="card shadow p-4">

        <table class="table table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Equipment</th>
                    <th>Rent Date</th>
                    <th>Due Date</th>
                    <th>Return Date</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['rent_date']; ?></td>
                    <td><?php echo $row['due_date']; ?></td>
                    <td><?php echo $row['return_date'] ?? '—'; ?></td>

                    <td>
                        <?php if ($row['status'] == 'returned') { ?>
                            <span class="badge bg-success">Returned</span>
                        <?php } elseif (strtotime($row['due_date']) < time()) { ?>
                            <span class="badge bg-danger">Overdue</span>
                        <?php } else { ?>
                            <span class="badge bg-warning">Rented</span>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>

        </table>

    </div>

</div>

<?php include("../includes/footer.php"); ?>