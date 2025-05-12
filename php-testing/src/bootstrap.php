<?php
ob_start();
session_start();

require_once __DIR__ . '/config/database.php';

// Helper functions
function redirect($path) {
    header("Location: $path");
    exit;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect('/login.php');
    }
}

function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

function view($name, $data = []) {
    extract($data);
    require_once __DIR__ . "/views/$name.php";
} 