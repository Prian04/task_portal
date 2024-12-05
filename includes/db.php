<?php
$host = 'localhost';
$user = 'root';
$password = 'techoexpert321!!';
$database = 'task_portal';
$port = 3310;
$conn = mysqli_connect($host, $user, $password, $database, $port);

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}
?>
