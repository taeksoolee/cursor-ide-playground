<?php
class Tag {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }
    public function getAllByUserId($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM tags WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function add($userId, $name, $color = '#6c757d') {
        $stmt = $this->pdo->prepare("INSERT INTO tags (user_id, name, color) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $name, $color]);
        return $this->pdo->lastInsertId();
    }
    public function delete($tagId, $userId) {
        $stmt = $this->pdo->prepare("DELETE FROM tags WHERE id = ? AND user_id = ?");
        return $stmt->execute([$tagId, $userId]);
    }
    public function getById($tagId) {
        $stmt = $this->pdo->prepare("SELECT * FROM tags WHERE id = ?");
        $stmt->execute([$tagId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getByTodoId($todoId) {
        $stmt = $this->pdo->prepare("SELECT t.* FROM tags t JOIN todo_tags tt ON t.id = tt.tag_id WHERE tt.todo_id = ?");
        $stmt->execute([$todoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 