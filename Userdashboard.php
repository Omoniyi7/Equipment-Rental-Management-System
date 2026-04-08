<?php
session_start();
if ($_SESSION['role'] != 'user') {
    header("Location: ../auth/login.php");
    exit();
}
?>

<?php include("../includes/header.php"); ?>

<div class="container mt-5">

    <h2 class="mb-4">👤 User Dashboard</h2>

    <div class="row g-4">

        <div class="col-md-4">
            <div class="card shadow p-3 text-center h-100">
                <h5>🔍 Rent Equipment</h5>
                <a href="rent.php" class="btn btn-success mt-3">Browse</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow p-3 text-center h-100">
                <h5>🔄 Return Equipment</h5>
                <a href="return.php" class="btn btn-warning mt-3">Return</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow p-3 text-center h-100">
                <h5>📋 Rental History</h5>
                <a href="history.php" class="btn btn-info mt-3">View</a>
            </div>
        </div>

    </div>

</div>

<?php include("../includes/footer.php"); ?>