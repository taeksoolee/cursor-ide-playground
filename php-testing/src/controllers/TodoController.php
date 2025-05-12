<?php
require_once __DIR__ . '/../models/Todo.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Tag.php';

class TodoController {
    private $todo;

    public function __construct($pdo) {
        $this->todo = new Todo($pdo);
    }

    public function index() {
        requireLogin();
        
        $filters = [
            'search' => isset($_GET['search']) && $_GET['search'] !== '' ? $_GET['search'] : null,
            'priority' => isset($_GET['priority']) && $_GET['priority'] !== '' ? $_GET['priority'] : null,
            'completed' => (isset($_GET['completed']) && $_GET['completed'] !== '') ? (int)$_GET['completed'] : null,
            'due_date' => isset($_GET['due_date']) && $_GET['due_date'] !== '' ? $_GET['due_date'] : null,
            'category_id' => isset($_GET['category_id']) && $_GET['category_id'] !== '' ? $_GET['category_id'] : null,
            'tag_id' => isset($_GET['tag_id']) && $_GET['tag_id'] !== '' ? $_GET['tag_id'] : null
        ];

        $todos = $this->todo->getAllByUserId(getCurrentUserId(), $filters);
        error_log('DEBUG $todos: ' . print_r($todos, true));
        $stats = $this->todo->getStats(getCurrentUserId());

        $categoryModel = new Category($GLOBALS['pdo']);
        $categories = $categoryModel->getAllByUserId(getCurrentUserId());

        $tagModel = new Tag($GLOBALS['pdo']);
        $tags = $tagModel->getAllByUserId(getCurrentUserId());

        view('todo/index', [
            'todos' => $todos,
            'stats' => $stats,
            'filters' => $filters,
            'categories' => $categories,
            'tags' => $tags
        ]);
    }

    public function add() {
        requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['title'])) {
            $this->todo->create(
                getCurrentUserId(),
                $_POST['title'],
                $_POST['description'] ?? '',
                $_POST['priority'] ?? 'medium',
                !empty($_POST['due_date']) ? $_POST['due_date'] : null
            );
        }
        redirect('/index.php');
    }

    public function edit() {
        requireLogin();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            redirect('/index.php');
        }

        $todo = $this->todo->getById($id, getCurrentUserId());
        if (!$todo) {
            redirect('/index.php');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->todo->update(
                $id,
                getCurrentUserId(),
                $_POST['title'],
                $_POST['description'] ?? '',
                $_POST['priority'] ?? 'medium',
                !empty($_POST['due_date']) ? $_POST['due_date'] : null
            );
            redirect('/index.php');
        }

        view('todo/edit', ['todo' => $todo]);
    }

    public function toggle() {
        requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $this->todo->toggle($_POST['id'], getCurrentUserId());
        }
        redirect('/index.php');
    }

    public function delete() {
        requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $this->todo->delete($_POST['id'], getCurrentUserId());
        }
        redirect('/index.php');
    }
} 