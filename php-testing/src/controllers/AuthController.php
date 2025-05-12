<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $user;

    public function __construct($pdo) {
        $this->user = new User($pdo);
    }

    public function login() {
        if (isLoggedIn()) {
            redirect('/dashboard.php');
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if (!empty($username) && !empty($password)) {
                $user = $this->user->findByUsername($username);

                if ($user && $this->user->verifyPassword($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    redirect('/dashboard.php');
                } else {
                    $error = "아이디 또는 비밀번호가 올바르지 않습니다.";
                    require __DIR__ . '/../views/auth/login.php';
                    return;
                }
            } else {
                $error = 'Please fill in all fields';
            }
        }

        view('auth/login', ['error' => $error]);
    }

    public function register() {
        if (isLoggedIn()) {
            redirect('/dashboard.php');
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if (empty($username) || empty($password) || empty($confirm_password)) {
                $error = 'Please fill in all fields';
            } elseif ($password !== $confirm_password) {
                $error = 'Passwords do not match';
            } else {
                if ($this->user->findByUsername($username)) {
                    $error = 'Username already exists';
                } else {
                    if ($this->user->create($username, $password)) {
                        $_SESSION['user_id'] = $GLOBALS['pdo']->lastInsertId();
                        redirect('/dashboard.php');
                    } else {
                        $error = 'Registration failed';
                    }
                }
            }
        }

        view('auth/register', ['error' => $error]);
    }

    public function logout() {
        session_destroy();
        redirect('/login.php');
    }
} 