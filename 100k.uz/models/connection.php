<?php
$host = '127.0.0.1';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $dbName = 'manaku';
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbName");

    $pdo->exec("USE $dbName");

    $pdo->exec("CREATE TABLE IF NOT EXISTS leads (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        contact VARCHAR(255) NOT NULL,
        product VARCHAR(255) NOT NULL,  
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        site VARCHAR(255) NOT NULL
    )");
} catch (PDOException $e) {
    echo "Xatolik: " . $e->getMessage();
}
?>
