<?php
include '../includes/db.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="task_report.csv"');

// Open the output stream
$output = fopen('php://output', 'w');

// Add column headers to the CSV
fputcsv($output, ['Task ID', 'User ID', 'User Name', 'Start Time', 'Stop Time', 'Notes', 'Description']);

// Fetch task data
$query = "
    SELECT 
        t.id AS task_id, 
        t.user_id, 
        CONCAT(u.first_name, ' ', u.last_name) AS user_name, 
        t.start_time, 
        t.stop_time, 
        t.notes, 
        t.description
    FROM tasks t
    JOIN users u ON t.user_id = u.id";
$result = mysqli_query($conn, $query);

// Add rows to the CSV
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}

// Close the output stream
fclose($output);
exit;
