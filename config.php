<?php
$server = 'localhost';
$username = 'root'; // change if you altered credentials
$password = ''; // empty password is XAMPP default
$database = 'bluebirdhotel';

// Wrap connection in try/catch to avoid uncaught mysqli_sql_exception (especially on PHP 8+)
// We'll log the full error and show a short alert to help during debugging.
try {
    // Make mysqli throw exceptions so we can catch them reliably
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = mysqli_connect($server, $username, $password, $database);

    // optional: set a sensible default charset
    mysqli_set_charset($conn, 'utf8mb4');

    // Connection created and left open for the rest of the app to use
    // (don't close it here - included pages expect $conn to be a usable mysqli object)
} catch (mysqli_sql_exception $e) {
    // Don't reveal sensitive internals in production — logging is safer
    error_log('DB connection error: ' . $e->getMessage());
    die("<script>alert('Database connection failed: " . addslashes($e->getMessage()) . "');</script>");
}
?>

