<?php
// Assuming $conn is the active MySQLi connection

// Start session and check if the user is an admin
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header('Location: login.php');
    exit;
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'];
    $user_id = $_SESSION['user_id'];

    // Hash the new password using MD5 (Note: MD5 is not recommended for password hashing in production)
    $hashed_password = md5($new_password);

    // Update the password in the database
    $update_query = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $update_query)) {
        mysqli_stmt_bind_param($stmt, 'si', $hashed_password, $user_id);
        if (mysqli_stmt_execute($stmt)) {
            echo "<p>Password has been updated successfully.</p>";
        } else {
            echo "<p>Error updating password: " . mysqli_stmt_error($stmt) . "</p>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<p>Database query failed: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!-- HTML form to reset password -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Password Reset</title>
</head>
<body>
    <h2>Reset Admin Password</h2>
    <form method="post" action="">
        <input type="hidden" name="user_id" value="1">
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required>
        <br><br>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>
