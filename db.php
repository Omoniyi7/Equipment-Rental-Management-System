<?php
$conn = mysqli_connect("localhost", "root", "", "equipment_rental_db");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>