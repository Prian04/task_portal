<?php
include '../includes/db.php';
include '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $auto_generate = isset($_POST['auto_generate']);
    $password = $auto_generate ? generatePassword() : $_POST['password'];

    if (!validatePassword($password)) {
        $error = "Password must be at least 8 characters, include one uppercase letter and one number.";
    } else {
        $hashed_password = hashPassword($password);
        $query = "INSERT INTO users (first_name, last_name, email, phone, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'sssss', $first_name, $last_name, $email, $phone, $hashed_password);

        if (mysqli_stmt_execute($stmt)) {
            $success = "User created successfully. Password: $password";
        } else {
            $error = "Error creating user.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Create User</title>
</head>
<body>
<div class="container mt-5">
    <h3>Create User</h3>
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" required>
        </div>
        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone">
        </div>
        <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" id="auto_generate" name="auto_generate">
            <label class="form-check-label" for="auto_generate">Auto-generate strong password</label>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password manually">
        </div>
        <button type="submit" class="btn btn-primary">Create User</button>

        <a href="dashboard.php" class="btn btn-primary">Dashboard</a>
    </form>
</div>
</body>
</html>
