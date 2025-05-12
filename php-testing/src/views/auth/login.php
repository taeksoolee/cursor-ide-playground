<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Todo App</title>
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
        /* Card header dark mode fix */
        [data-bs-theme="dark"] .card-header {
            background-color: #23272b !important;
            color: #fff !important;
            border-bottom: 1px solid #343a40 !important;
        }
    </style>
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
            <div class="col-md-6"></div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Login</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        <form method="POST" action="login.php">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Login</button>
                            <a href="register.php" class="btn btn-link">Don't have an account? Register</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Theme Toggle
        const toggleTheme = document.getElementById('toggleTheme');
        const html = document.documentElement;
        
        // Check system preference
        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            html.setAttribute('data-bs-theme', 'dark');
            toggleTheme.innerHTML = '<i class="bi bi-sun"></i>';
        }

        toggleTheme.addEventListener('click', () => {
            const currentTheme = html.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-bs-theme', newTheme);
            toggleTheme.innerHTML = newTheme === 'dark' ? '<i class="bi bi-sun"></i>' : '<i class="bi bi-moon-stars"></i>';
            localStorage.setItem('theme', newTheme);
        });

        // Load saved theme
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            html.setAttribute('data-bs-theme', savedTheme);
            toggleTheme.innerHTML = savedTheme === 'dark' ? '<i class="bi bi-sun"></i>' : '<i class="bi bi-moon-stars"></i>';
        }
    </script>
</body>
</html> 