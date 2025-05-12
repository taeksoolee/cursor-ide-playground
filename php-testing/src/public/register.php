<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../controllers/AuthController.php';

$controller = new AuthController($pdo);
$controller->register(); 