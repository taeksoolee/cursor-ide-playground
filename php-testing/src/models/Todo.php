<?php
require_once __DIR__ . '/Category.php';
require_once __DIR__ . '/Tag.php';

class Todo {
    private $pdo;
    private $categoryModel;
    private $tagModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->categoryModel = new Category($pdo);
        $this->tagModel = new Tag($pdo);
    }

    public function getAllByUserId($userId, $filters = []) {
        $conditions = ['(t.user_id = ? OR t.id IN (SELECT todo_id FROM shared_todos WHERE shared_with_user_id = ?))'];
        $params = [$userId, $userId];

        if (!empty($filters['search'])) {
            $conditions[] = '(t.title LIKE ? OR t.description LIKE ?)';
            $search = '%' . $filters['search'] . '%';
            $params[] = $search;
            $params[] = $search;
        }

        if (isset($filters['priority']) && $filters['priority'] !== null && $filters['priority'] !== '') {
            $conditions[] = 't.priority = ?';
            $params[] = $filters['priority'];
        }

        if (isset($filters['completed']) && $filters['completed'] !== null && $filters['completed'] !== '') {
            $conditions[] = 't.completed = ?';
            $params[] = $filters['completed'];
        }

        if (!empty($filters['due_date'])) {
            switch ($filters['due_date']) {
                case 'today':
                    $conditions[] = 'DATE(t.due_date) = CURDATE()';
                    break;
                case 'week':
                    $conditions[] = 't.due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)';
                    break;
                case 'overdue':
                    $conditions[] = 't.due_date < NOW() AND t.completed = 0';
                    break;
            }
        }

        if (isset($filters['category_id']) && $filters['category_id'] !== null && $filters['category_id'] !== '') {
            $conditions[] = 't.category_id = ?';
            $params[] = $filters['category_id'];
        }

        if (isset($filters['tag_id']) && $filters['tag_id'] !== null && $filters['tag_id'] !== '') {
            $conditions[] = 't.id IN (SELECT todo_id FROM todo_tags WHERE tag_id = ?)';
            $params[] = $filters['tag_id'];
        }

        $sql = '
            SELECT t.*, c.name as category_name, c.color as category_color,
                   COALESCE(GROUP_CONCAT(DISTINCT tg.id, ":", tg.name, ":", tg.color), "") as tags
            FROM todos t
            LEFT JOIN categories c ON t.category_id = c.id
            LEFT JOIN todo_tags tt ON t.id = tt.todo_id
            LEFT JOIN tags tg ON tt.tag_id = tg.id
            WHERE ' . implode(' AND ', $conditions) . '
            GROUP BY t.id, t.user_id, t.category_id, t.title, t.description, t.priority, t.due_date, t.completed, t.created_at, c.name, c.color
            ORDER BY 
                CASE 
                    WHEN t.due_date < NOW() AND t.completed = 0 THEN 0
                    ELSE 1
                END,
                CASE t.priority
                    WHEN "high" THEN 0
                    WHEN "medium" THEN 1
                    WHEN "low" THEN 2
                END,
                t.due_date ASC,
                t.created_at DESC
        ';

        error_log('DEBUG SQL: ' . $sql);
        error_log('DEBUG PARAMS: ' . print_r($params, true));
        error_log('DEBUG FILTERS: ' . print_r($filters, true));

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $todos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log('DEBUG TODOS COUNT: ' . count($todos));

        // Process tags and attach category/tag objects
        foreach ($todos as &$todo) {
            if (!empty($todo['category_id'])) {
                $todo['category'] = $this->categoryModel->getById($todo['category_id']);
            } else {
                $todo['category'] = null;
            }
            if (!empty($todo['tags'])) {
                $tags = [];
                foreach (explode(',', $todo['tags']) as $tag) {
                    if (strpos($tag, ':') !== false) {
                        list($id, $name, $color) = explode(':', $tag);
                        $tags[] = $this->tagModel->getById($id);
                    }
                }
                $todo['tags'] = $tags;
            } else {
                $todo['tags'] = [];
            }
        }

        return $todos;
    }

    public function create($userId, $title, $description = '', $priority = 'medium', $dueDate = null, $categoryId = null, $tags = []) {
        $stmt = $this->pdo->prepare('
            INSERT INTO todos (user_id, category_id, title, description, priority, due_date)
            VALUES (?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([$userId, $categoryId, $title, $description, $priority, $dueDate]);
        $todoId = $this->pdo->lastInsertId();
        if (!empty($tags)) {
            $this->addTags($todoId, $tags);
        }
        return $todoId;
    }

    public function update($id, $userId, $data, $tags = null) {
        $allowedFields = ['title', 'description', 'priority', 'due_date', 'completed', 'category_id'];
        $updates = [];
        $values = [];
        foreach ($data as $field => $value) {
            if (in_array($field, $allowedFields)) {
                $updates[] = "$field = ?";
                $values[] = $value;
            }
        }
        if (empty($updates)) {
            return false;
        }
        $values[] = $id;
        $values[] = $userId;
        $stmt = $this->pdo->prepare('
            UPDATE todos 
            SET ' . implode(', ', $updates) . '
            WHERE id = ? AND (user_id = ? OR id IN (SELECT todo_id FROM shared_todos WHERE shared_with_user_id = ? AND can_edit = 1))
        ');
        $values[] = $userId;
        return $stmt->execute($values);
    }

    public function delete($id, $userId) {
        $stmt = $this->pdo->prepare('
            DELETE FROM todos 
            WHERE id = ? AND (user_id = ? OR id IN (SELECT todo_id FROM shared_todos WHERE shared_with_user_id = ? AND can_edit = 1))
        ');
        return $stmt->execute([$id, $userId, $userId]);
    }

    public function toggle($id, $userId) {
        $stmt = $this->pdo->prepare('
            UPDATE todos 
            SET completed = NOT completed 
            WHERE id = ? AND (user_id = ? OR id IN (SELECT todo_id FROM shared_todos WHERE shared_with_user_id = ?))
        ');
        return $stmt->execute([$id, $userId, $userId]);
    }

    public function getById($id, $userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM todos WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStats($userId) {
        $stmt = $this->pdo->prepare('
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN completed = 1 THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN due_date < NOW() AND completed = 0 THEN 1 ELSE 0 END) as overdue,
                SUM(CASE WHEN DATE(due_date) = CURDATE() THEN 1 ELSE 0 END) as due_today,
                SUM(CASE WHEN due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) THEN 1 ELSE 0 END) as due_week
            FROM todos
            WHERE user_id = ? OR id IN (SELECT todo_id FROM shared_todos WHERE shared_with_user_id = ?)
        ');
        
        $stmt->execute([$userId, $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addTags($todoId, $tagIds) {
        $stmt = $this->pdo->prepare('INSERT IGNORE INTO todo_tags (todo_id, tag_id) VALUES (?, ?)');
        foreach ($tagIds as $tagId) {
            $stmt->execute([$todoId, $tagId]);
        }
    }

    public function removeTags($todoId, $tagIds) {
        $stmt = $this->pdo->prepare('DELETE FROM todo_tags WHERE todo_id = ? AND tag_id = ?');
        foreach ($tagIds as $tagId) {
            $stmt->execute([$todoId, $tagId]);
        }
    }

    public function getComments($todoId) {
        $stmt = $this->pdo->prepare('
            SELECT c.*, u.username
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.todo_id = ?
            ORDER BY c.created_at DESC
        ');
        $stmt->execute([$todoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addComment($todoId, $userId, $content) {
        $stmt = $this->pdo->prepare('
            INSERT INTO comments (todo_id, user_id, content)
            VALUES (?, ?, ?)
        ');
        return $stmt->execute([$todoId, $userId, $content]);
    }

    public function share($todoId, $userId, $sharedWithUserId, $canEdit = false) {
        $stmt = $this->pdo->prepare('
            INSERT INTO shared_todos (todo_id, shared_with_user_id, can_edit)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE can_edit = ?
        ');
        return $stmt->execute([$todoId, $sharedWithUserId, $canEdit, $canEdit]);
    }

    public function unshare($todoId, $userId, $sharedWithUserId) {
        $stmt = $this->pdo->prepare('
            DELETE FROM shared_todos
            WHERE todo_id = ? AND shared_with_user_id = ?
        ');
        return $stmt->execute([$todoId, $sharedWithUserId]);
    }

    public function getSharedUsers($todoId) {
        $stmt = $this->pdo->prepare('
            SELECT u.id, u.username, st.can_edit
            FROM shared_todos st
            JOIN users u ON st.shared_with_user_id = u.id
            WHERE st.todo_id = ?
        ');
        $stmt->execute([$todoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTagIds($todoId) {
        $stmt = $this->pdo->prepare('SELECT tag_id FROM todo_tags WHERE todo_id = ?');
        $stmt->execute([$todoId]);
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'tag_id');
    }

    // Add a new category for a user
    public function addCategory($userId, $name, $color = '#6c757d') {
        $stmt = $this->pdo->prepare('INSERT INTO categories (user_id, name, color) VALUES (?, ?, ?)');
        $stmt->execute([$userId, $name, $color]);
        return $this->pdo->lastInsertId();
    }

    // Add a new tag for a user
    public function addTag($userId, $name, $color = '#6c757d') {
        $stmt = $this->pdo->prepare('INSERT INTO tags (user_id, name, color) VALUES (?, ?, ?)');
        $stmt->execute([$userId, $name, $color]);
        return $this->pdo->lastInsertId();
    }
} 