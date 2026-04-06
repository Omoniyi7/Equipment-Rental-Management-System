<?php
session_start();
include("../config/db.php");

if ($_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// TOTAL USERS
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'];

// TOTAL EQUIPMENT
$total_equipment = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM equipment"))['total'];

// TOTAL RENTALS
$total_rentals = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM rentals"))['total'];

// RETURNED ITEMS
$returned = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM rentals WHERE status='returned'"))['total'];

// OVERDUE ITEMS
$overdue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM rentals WHERE due_date < NOW() AND status='rented'"))['total'];
?>

<?php include("../includes/header.php"); ?>

<div class="container mt-5">

    <h2 class="mb-4">📊 System Reports</h2>

    <div class="row g-4">

        <div class="col-md-3">
            <div class="card bg-primary text-white shadow p-3 text-center">
                <h5>Total Users</h5>
                <h2><?php echo $total_users; ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white shadow p-3 text-center">
                <h5>Total Equipment</h5>
                <h2><?php echo $total_equipment; ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-dark text-white shadow p-3 text-center">
                <h5>Total Rentals</h5>
                <h2><?php echo $total_rentals; ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-info text-white shadow p-3 text-center">
                <h5>Returned Items</h5>
                <h2><?php echo $returned; ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-danger text-white shadow p-3 text-center">
                <h5>Overdue Items</h5>
                <h2><?php echo $overdue; ?></h2>
            </div>
        </div>

    </div>

</div>

<?php include("../includes/footer.php"); ?>