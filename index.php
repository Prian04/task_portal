<?php
error_reporting(E_ALL);
session_start();
include 'includes/db.php';
include 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute query
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        // Check if user exists
        if ($user = mysqli_fetch_assoc($result)) {
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Check if password change is due
                $lastPasswordChange = strtotime($user['last_password_change']);
                $currentTime = time();
                $daysSinceLastChange = ($currentTime - $lastPasswordChange) / (60 * 60 * 24);

                // Start session and store user details
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['is_admin'] = $user['is_admin'];
                $_SESSION['last_password_change'] = $user['last_password_change'];

                // Update last login time in the database
                $updateLoginQuery = "UPDATE users SET last_login = NOW() WHERE id = ?";
                $updateStmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($updateStmt, $updateLoginQuery)) {
                    mysqli_stmt_bind_param($updateStmt, 'i', $user['id']);
                    mysqli_stmt_execute($updateStmt);
                    mysqli_stmt_close($updateStmt);
                }

                // Check if user needs to change their password
                if ($daysSinceLastChange > 30) {
                    header('Location: user/change_password.php');
                    exit;
                }

                // Redirect to user dashboard if no password change is needed
                if ($user['is_admin'] == 1) {
                    header('Location: admin/dashboard.php');
                } else {
                    header('Location: user/dashboard.php');
                }
                exit;
            } else {
                $error = "Invalid credentials!";
            }
        } else {
            $error = "No user found!";
        }

        // Free result
        mysqli_free_result($result);
    } else {
        $error = "Database query failed!";
    }

    // Close statement
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Login</title>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h3 class="text-center">Login</h3>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <?php if (isset($error)) { echo "<div class='text-danger'>$error</div>"; } ?>
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
