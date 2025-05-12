<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../controllers/TodoController.php';

$controller = new TodoController($pdo);
$controller->edit(); 