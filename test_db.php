<?php
$server = 'localhost';
$username = 'root'; // adjust if you changed root password
$password = '';      // XAMPP default is blank
$database = 'bluebirdhotel';

// Simple test page to check DB connection (safe to remove after testing)
try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = mysqli_connect($server, $username, $password, $database);
    mysqli_set_charset($conn, 'utf8mb4');
    echo "Connection OK — bluebirdhotel available.\n";
    mysqli_close($conn);
} catch (mysqli_sql_exception $e) {
    error_log('DB test error: ' . $e->getMessage());
    echo "Connection failed: " . htmlspecialchars($e->getMessage());
}
?>
