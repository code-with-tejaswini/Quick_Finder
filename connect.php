<?php
// connect.php - Database Connection

define('DB_HOST', 'localhost');
define('DB_USER', 'root');      // Change to your MySQL username
define('DB_PASS', '');          // Change to your MySQL password
define('DB_NAME', 'quick_finder');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, 3307);

if (!$conn) {
    die('<div style="text-align:center;padding:50px;font-family:sans-serif;">
        <h2>Database Connection Failed</h2>
        <p>' . mysqli_connect_error() . '</p>
        <p>Please check your database credentials in <strong>connect.php</strong></p>
    </div>');
}

mysqli_set_charset($conn, 'utf8mb4');

// Helper: sanitize input
function sanitize($conn, $data) {
    return mysqli_real_escape_string($conn, trim(htmlspecialchars($data)));
}

// Helper: redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>