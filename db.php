<?php
require_once __DIR__ . '/app.php';

$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPassword = getenv('DB_PASSWORD') ?: '';
$dbName = getenv('DB_NAME') ?: 'adrenax-db';
$dbPort = (int) (getenv('DB_PORT') ?: 3306);

$conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName, $dbPort);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
