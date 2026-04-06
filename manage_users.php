<?php
session_start();
include("../config/db.php");

if ($_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// ADD USER
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $role = $_POST['role'];

    mysqli_query($conn, "INSERT INTO users (name, email, password, role)
    VALUES ('$name', '$email', '$password', '$role')");
}

// DELETE USER
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM users WHERE user_id=$id");
}

// EDIT FETCH
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $editData = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE user_id=$id"));
}

// UPDATE USER
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    mysqli_query($conn, "UPDATE users SET 
        name='$name',
        email='$email',
        role='$role'
        WHERE user_id=$id");
}

// FETCH USERS
$result = mysqli_query($conn, "SELECT * FROM users");
?>

<?php include("../includes/header.php"); ?>

<div class="container mt-5">

    <h2 class="mb-4">👥 Manage Users</h2>

    <!-- FORM -->
    <div class="card shadow p-4 mb-4">
        <h5><?php echo $editData ? "✏️ Edit User" : "➕ Add User"; ?></h5>

        <form method="POST" class="row g-3">

            <input type="hidden" name="id" value="<?php echo $editData['user_id'] ?? ''; ?>">

            <div class="col-md-3">
                <input type="text" name="name" class="form-control" placeholder="Name"
                value="<?php echo $editData['name'] ?? ''; ?>" required>
            </div>

            <div class="col-md-3">
                <input type="email" name="email" class="form-control" placeholder="Email"
                value="<?php echo $editData['email'] ?? ''; ?>" required>
            </div>

            <?php if (!$editData) { ?>
            <div class="col-md-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <?php } ?>

            <div class="col-md-2">
                <select name="role" class="form-control">
                    <option value="user" <?php if (($editData['role'] ?? '') == 'user') echo 'selected'; ?>>User</option>
                    <option value="admin" <?php if (($editData['role'] ?? '') == 'admin') echo 'selected'; ?>>Admin</option>
                </select>
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
        <h5>📋 User List</h5>

        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['user_id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo ucfirst($row['role']); ?></td>

                    <td>
                        <a href="?edit=<?php echo $row['user_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="?delete=<?php echo $row['user_id']; ?>" class="btn btn-sm btn-danger"
                        onclick="return confirm('Delete user?')">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>

        </table>
    </div>

</div>

<?php include("../includes/footer.php"); ?>