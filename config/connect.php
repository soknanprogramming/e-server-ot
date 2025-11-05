<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'e_server';

try {
    $conn = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $user, $password);
    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Connection successful
    // You can now use $conn for your queries
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
