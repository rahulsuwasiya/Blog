<?php
// db.php - Database connection
$host = 'localhost';   // Your database host (usually localhost)
$dbname = 'blog_platform';  // Your database name
$username = 'root';   // Your MySQL username
$password = '';   // Your MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
