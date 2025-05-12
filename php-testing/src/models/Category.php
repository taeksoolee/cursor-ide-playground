<?php
class Category {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }
    public function getAllByUserId($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function add($userId, $name, $color = '#6c757d') {
        $stmt = $this->pdo->prepare("INSERT INTO categories (user_id, name, color) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $name, $color]);
        return $this->pdo->lastInsertId();
    }
    public function delete($categoryId, $userId) {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ? AND user_id = ?");
        return $stmt->execute([$categoryId, $userId]);
    }
    public function getById($categoryId) {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$categoryId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
} 