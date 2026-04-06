<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Equipment Rental System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Optional Custom CSS -->
    <link href="/equipment_rental_system/assets/css/style.css" rel="stylesheet">
</head>

<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
    <div class="container-fluid">

        <a class="navbar-brand" href="/equipment_rental_system/">
            🏗 Equipment Rental System
        </a>

        <div class="d-flex align-items-center">

            <?php if (isset($_SESSION['role'])) { ?>

                <!-- Role Display -->
                <span class="text-white me-3">
                    Logged in as: <strong><?php echo ucfirst($_SESSION['role']); ?></strong>
                </span>

                <!-- Navigation Links -->
                <?php if ($_SESSION['role'] == 'admin') { ?>
                    <a href="/equipment_rental_system/admin/dashboard.php" class="btn btn-outline-light me-2">Dashboard</a>
                    <a href="/equipment_rental_system/admin/manage_equipment.php" class="btn btn-outline-light me-2">Equipment</a>
                    <a href="/equipment_rental_system/admin/manage_users.php" class="btn btn-outline-light me-2">Users</a>
                    <a href="/equipment_rental_system/admin/history.php" class="btn btn-outline-light me-3">History</a>
                <?php } ?>

                <?php if ($_SESSION['role'] == 'user') { ?>
                    <a href="/equipment_rental_system/user/dashboard.php" class="btn btn-outline-light me-2">Dashboard</a>
                    <a href="/equipment_rental_system/user/rent.php" class="btn btn-outline-light me-2">Rent</a>
                    <a href="/equipment_rental_system/user/return.php" class="btn btn-outline-light me-2">Return</a>
                    <a href="/equipment_rental_system/user/history.php" class="btn btn-outline-light me-3">History</a>
                <?php } ?>

                <!-- Logout Button -->
                <a href="/equipment_rental_system/auth/logout.php" class="btn btn-danger">Logout</a>

            <?php } ?>

        </div>
    </div>
</nav>

<!-- PAGE CONTENT START -->
<div class="container mt-4">