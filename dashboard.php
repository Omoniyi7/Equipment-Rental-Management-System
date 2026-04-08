<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
?>

<?php include("../includes/header.php"); ?>

<div class="container mt-5">

    <h2 class="mb-4">👨‍💼 Admin Dashboard</h2>

    <div class="row g-4">

        <div class="col-md-3">
            <div class="card shadow p-3 text-center h-100">
                <h5>📦 Manage Equipment</h5>
                <a href="manage_equipment.php" class="btn btn-primary mt-3">Go</a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow p-3 text-center h-100">
                <h5>👥 Manage Users</h5>
                <a href="manage_users.php" class="btn btn-secondary mt-3">Go</a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow p-3 text-center h-100">
                <h5>📊 Rental History</h5>
                <a href="history.php" class="btn btn-dark mt-3">View</a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow p-3 text-center h-100">
                <h5>📈 Reports</h5>
                <a href="report.php" class="btn btn-success mt-3">View</a>
            </div>
        </div>

    </div>

</div>

<?php include("../includes/footer.php"); ?>