<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../models/Todo.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $todoId = $data['todo_id'] ?? null;
    $content = trim($data['content'] ?? '');
    $userId = getCurrentUserId();

    if ($todoId && $userId && $content !== '') {
        $todoModel = new Todo($pdo);
        $result = $todoModel->addComment($todoId, $userId, $content);
        if ($result) {
            echo json_encode(['success' => true]);
            exit;
        }
    }
    echo json_encode(['success' => false, 'error' => 'Invalid input or DB error']);
    exit;
}
echo json_encode(['success' => false, 'error' => 'Invalid request']); 