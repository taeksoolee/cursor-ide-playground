<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Todo - Todo App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        [data-bs-theme="dark"] body {
            background: #181a1b !important;
            color: #fff !important;
        }
        [data-bs-theme="dark"] .card {
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
            }
        })();
    </script>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3>Edit Todo</h3>
                        <a href="/index.php" class="btn btn-outline-secondary">Back to List</a>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($todo['title']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($todo['description']) ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="priority" class="form-label">Priority</label>
                                <select class="form-select" id="priority" name="priority">
                                    <option value="low" <?= $todo['priority'] === 'low' ? 'selected' : '' ?>>Low</option>
                                    <option value="medium" <?= $todo['priority'] === 'medium' ? 'selected' : '' ?>>Medium</option>
                                    <option value="high" <?= $todo['priority'] === 'high' ? 'selected' : '' ?>>High</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="datetime-local" class="form-control" id="due_date" name="due_date" 
                                    value="<?= $todo['due_date'] ? date('Y-m-d\TH:i', strtotime($todo['due_date'])) : '' ?>">
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Update Todo</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 