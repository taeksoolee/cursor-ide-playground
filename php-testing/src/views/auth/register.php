<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Todo App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .navbar {
            background-color: var(--bs-primary) !important;
        }
        .navbar-brand, .navbar .nav-link, .navbar .btn {
            color: white !important;
        }
        .navbar .btn-outline-secondary {
            color: white !important;
            border-color: white !important;
        }
        .navbar .btn-outline-secondary:hover {
            background-color: white !important;
            color: var(--bs-secondary) !important;
        }
        [data-bs-theme="dark"] body {
            background: #181a1b !important;
            color: #fff !important;
        }
        [data-bs-theme="dark"] .card, [data-bs-theme="dark"] .card-header {
            background: #23272b !important;
            color: #fff !important;
            border-color: #343a40 !important;
        }
        [data-bs-theme="dark"] .form-control,
        [data-bs-theme="dark"] .form-select {
            background: #23272b !important;
            color: #fff !important;
            border-color: #343a40 !important;
        }
        [data-bs-theme="dark"] .form-control::placeholder {
            color: #bbb !important;
        }
        [data-bs-theme="dark"] .btn-primary {
            background-color: #2563eb !important;
            border-color: #2563eb !important;
            color: #fff !important;
        }
        [data-bs-theme="dark"] .btn-primary:hover {
            background-color: #1d4ed8 !important;
            border-color: #1d4ed8 !important;
        }
        [data-bs-theme="dark"] .btn-outline-secondary {
            border-color: #6c757d !important;
            color: #fff !important;
        }
        [data-bs-theme="dark"] .btn-outline-secondary:hover {
            background-color: #6c757d !important;
            color: #fff !important;
        }
        [data-bs-theme="dark"] .alert-danger {
            background-color: #4b1e1e !important;
            color: #fff !important;
            border-color: #dc3545 !important;
        }
    </style>
    <script>
        // 모든 페이지에서 다크모드 일치
        (function() {
            const html = document.documentElement;
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                html.setAttribute('data-bs-theme', savedTheme);
            } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                html.setAttribute('data-bs-theme', 'dark');
            }
            window.setTheme = function(theme) {
                html.setAttribute('data-bs-theme', theme);
                localStorage.setItem('theme', theme);
                // 아이콘도 즉시 변경
                const toggleTheme = document.getElementById('toggleTheme');
                if (toggleTheme) {
                    toggleTheme.innerHTML = theme === 'dark'
                        ? '<i class="bi bi-sun"></i>'
                        : '<i class="bi bi-moon-stars"></i>';
                }
            }
        })();
        // 토글 버튼 클릭 시 테마 변경
        document.addEventListener('DOMContentLoaded', function() {
            const toggleTheme = document.getElementById('toggleTheme');
            if (toggleTheme) {
                // 페이지 로드시 아이콘 세팅
                const html = document.documentElement;
                const currentTheme = html.getAttribute('data-bs-theme');
                toggleTheme.innerHTML = currentTheme === 'dark'
                    ? '<i class="bi bi-sun"></i>'
                    : '<i class="bi bi-moon-stars"></i>';
                toggleTheme.addEventListener('click', function() {
                    const html = document.documentElement;
                    const currentTheme = html.getAttribute('data-bs-theme');
                    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                    setTheme(newTheme);
                });
            }
        });
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="/">Todo App</a>
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-secondary me-2" id="toggleTheme">
                    <i class="bi bi-moon-stars"></i>
                </button>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Register</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        <form method="POST" action="register.php">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Register</button>
                            <a href="login.php" class="btn btn-link">Already have an account? Login</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 