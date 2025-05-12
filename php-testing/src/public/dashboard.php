<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../controllers/DashboardController.php';

$controller = new DashboardController($pdo);
$controller->index(); 