<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start_time = $_POST['start_time'];
    $stop_time = $_POST['stop_time'];
    $notes = $_POST['notes'];
    $description = $_POST['description'];

    $query = "INSERT INTO tasks (user_id, start_time, stop_time, notes, description) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'issss', $user_id, $start_time, $stop_time, $notes, $description);

    if (mysqli_stmt_execute($stmt)) {
        $success = "Task submitted successfully.";
    } else {
        $error = "Error submitting task.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>User Dashboard</title>
</head>
<body>
<div class="container mt-5">
    <h3>User Dashboard</h3>
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="form-group">
            <label for="start_time">Start Time</label>
            <input type="datetime-local" class="form-control" id="start_time" name="start_time" required>
        </div>
        <div class="form-group">
            <label for="stop_time">Stop Time</label>
            <input type="datetime-local" class="form-control" id="stop_time" name="stop_time" required>
        </div>
        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Task</button>
        <a href="change_password.php" class="btn btn-secondary">Change Password</a>
        <a href="../logout.php" class="btn btn-danger">Logout</a>

    </form>

</div>
</body>
</html>
