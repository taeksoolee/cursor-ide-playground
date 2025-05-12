<?php
define('DB_HOST', 'db');
define('DB_USER', 'todo_user');
define('DB_PASS', 'todo_password');
define('DB_NAME', 'todo_db');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
} 