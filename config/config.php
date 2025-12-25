<?php
define('SECURE_ACCESS', true);
require_once __DIR__."/../function/function.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// ==========================
// No Direct Access to File 
// ==========================
protectFile(__FILE__);
// ==========================
// Email Configuration Constants
// ==========================
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USER', '');// Enter your gmail here
define('SMTP_PASSWORD', ''); // Enter app password
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');
// ==========================
// Database Configuration
// ==========================

$host = "localhost";
$dataBase = "ZenBlogs";
$db_user = "root";
$db_password = "1234";
$charset = "utf8mb4";

$dataSourceName = "mysql:host=$host;dbname=$dataBase;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    PDO::ATTR_EMULATE_PREPARES => false,
];

// ==========================
// Connect to Database
// ==========================

try {
    $connection = new PDO($dataSourceName, $db_user, $db_password, $options);
} catch (PDOException $e) {
    die("Connection failed: " . htmlspecialchars($e->getMessage()));
}
?>