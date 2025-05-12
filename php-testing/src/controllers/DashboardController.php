<?php
require_once __DIR__ . '/../models/Todo.php';

class DashboardController {
    private $todo;

    public function __construct($pdo) {
        $this->todo = new Todo($pdo);
    }

    public function index() {
        requireLogin();
        $stats = $this->todo->getStats(getCurrentUserId());
        view('dashboard/index', ['stats' => $stats]);
    }
} 