<?php
// Connects to the CAT2 database. Passwords live in config.php (kept off GitHub).
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = null;
$db_error = "";

if (file_exists(__DIR__ . '/config.php')) {
    require __DIR__ . '/config.php';
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $conn->set_charset('utf8mb4');
    } catch (Throwable $e) {
        $conn = null;
        $db_error = "Could not connect to the database.";
    }
} else {
    $db_error = "Database not configured yet (config.php is missing).";
}
