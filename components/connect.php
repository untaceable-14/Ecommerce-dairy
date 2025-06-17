<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// echo "Starting database connection...<br>";

$db_name = 'mysql:host=localhost;dbname=m';
$user_name = 'root';
$user_password = '';

try {
    $conn = new PDO($db_name, $user_name, $user_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "✅ Database connected successfully!";
} catch (PDOException $e) {
    // die("❌ Connection failed: " . $e->getMessage());
}
?>
