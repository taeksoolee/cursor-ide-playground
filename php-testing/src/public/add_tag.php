<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../models/Todo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['name'])) {
    $todoModel = new Todo($pdo);
    $userId = getCurrentUserId();
    $name = $_POST['name'];
    $color = $_POST['color'] ?? '#6c757d';
    $todoModel->addTag($userId, $name, $color);
}
redirect('/index.php'); 