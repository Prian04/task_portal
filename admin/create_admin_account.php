<?php
// Assuming $conn is the active MySQLi connection

// Start session
session_start();
include '../includes/db.php';
include '../includes/functions.php';
// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $f_name = $_POST['f_name'];
    $l_name = $_POST['l_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // Hash the password using MD5 (for demonstration; in production, use password_hash())
    $hashed_password = hashPassword($password);

    // Insert query for adding a new admin user
    $insert_query = "INSERT INTO users (first_name, last_name, email, phone, password, is_admin, last_login, last_password_change) VALUES (?, ?, ?, ?, ?, 1, NOW(), NOW())";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $insert_query)) {
        // Correct the type definition string to match the number of variables
        mysqli_stmt_bind_param($stmt, 'sssss', $f_name, $l_name, $email, $phone, $hashed_password);
        if (mysqli_stmt_execute($stmt)) {
            echo "<p>Admin account created successfully.</p>";
        } else {
            echo "<p>Error creating admin account: " . mysqli_stmt_error($stmt) . "</p>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<p>Database query failed: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!-- HTML form to create an admin account -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Admin Account</title>
</head>
<body>
    <h2>Create Admin Account</h2>
    <form method="post" action="">
        <label for="f_name">First Name:</label>
        <input type="text" id="f_name" name="f_name" required>
        <br><br>
        <label for="l_name">Last Name:</label>
        <input type="text" id="l_name" name="l_name" required>
        <br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br><br>
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" required>
        <br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br><br>
        <input type="submit" value="Create Admin Account">
    </form>
</body>
</html>
