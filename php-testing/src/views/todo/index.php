<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List - Todo App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    <style>
        .completed { text-decoration: line-through; color: #888; }
        .priority-high { border-left: 4px solid #dc3545; }
        .priority-medium { border-left: 4px solid #ffc107; }
        .priority-low { border-left: 4px solid #28a745; }
        .overdue { background-color: var(--bs-danger-bg-subtle); }
        .nav-link { color: var(--bs-body-color); }
        .nav-link.active { color: var(--bs-primary); }
        .todo-item { 
            transition: all 0.2s;
            cursor: pointer;
        }
        .todo-item:hover { transform: translateX(5px); }
        .add-form { transition: all 0.3s; }
        .add-form.collapsed { display: none; }
        .sortable-ghost { opacity: 0.4; }
        .shortcut-key {
            display: inline-block;
            background: var(--bs-secondary-bg);
            border: 1px solid var(--bs-border-color);
            border-radius: 4px;
            padding: 2px 7px 2px 7px;
            font-size: 0.95em;
            font-family: 'Menlo', 'Monaco', 'Consolas', 'Liberation Mono', 'Courier New', monospace;
            font-weight: 600;
            margin: 0 2px;
            color: var(--bs-body-color);
            vertical-align: middle;
            letter-spacing: 0.5px;
        }
        [data-bs-theme="dark"] .shortcut-key {
            background: #23272b;
            border-color: #343a40;
            color: #fff;
        }
        [data-bs-theme="light"] .shortcut-key {
            background: #f8f9fa;
            border-color: #dee2e6;
            color: #212529;
        }
        .shortcut-hint {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            background: var(--bs-body-bg);
            border: 1px solid var(--bs-border-color);
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: none;
        }
        .shortcut-hint.show {
            display: block;
        }
        .tag {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            margin: 2px;
            color: white;
        }
        .category-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            margin: 2px;
            color: white;
        }
        .comment {
            border-left: 3px solid var(--bs-primary);
            padding-left: 10px;
            margin-bottom: 10px;
        }
        .comment-meta {
            font-size: 0.8em;
            color: var(--bs-secondary);
        }
        .navbar {
            background-color: var(--bs-primary) !important;
        }
        .navbar-brand, .navbar .nav-link, .navbar .btn {
            color: white !important;
        }
        .navbar .btn-outline-danger {
            color: white !important;
            border-color: white !important;
        }
        .navbar .btn-outline-danger:hover {
            background-color: white !important;
            color: var(--bs-danger) !important;
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
        [data-bs-theme="dark"] .card-header,
        [data-bs-theme="dark"] .card,
        [data-bs-theme="dark"] .form-control,
        [data-bs-theme="dark"] .input-group-text,
        [data-bs-theme="dark"] .dropdown-menu {
            background-color: #23272b !important;
            color: #fff !important;
            border-color: #343a40 !important;
        }
        [data-bs-theme="dark"] .card-header {
            border-bottom: 1px solid #343a40 !important;
        }
        [data-bs-theme="dark"] .form-control::placeholder {
            color: #bbb !important;
        }
        [data-bs-theme="dark"] .btn-primary,
        [data-bs-theme="dark"] .btn-secondary,
        [data-bs-theme="dark"] .btn-outline-primary,
        [data-bs-theme="dark"] .btn-outline-secondary {
            color: #fff !important;
        }
        [data-bs-theme="dark"] .btn-primary {
            background-color: #2563eb !important;
            border-color: #2563eb !important;
        }
        [data-bs-theme="dark"] .btn-primary:hover {
            background-color: #1d4ed8 !important;
            border-color: #1d4ed8 !important;
        }
        [data-bs-theme="dark"] .btn-outline-primary {
            border-color: #2563eb !important;
        }
        [data-bs-theme="dark"] .btn-outline-primary:hover {
            background-color: #2563eb !important;
            color: #fff !important;
        }
        [data-bs-theme="dark"] .btn-outline-secondary {
            border-color: #6c757d !important;
        }
        [data-bs-theme="dark"] .btn-outline-secondary:hover {
            background-color: #6c757d !important;
            color: #fff !important;
        }
        [data-bs-theme="dark"] .bg-light,
        [data-bs-theme="dark"] .bg-white {
            background-color: #23272b !important;
        }
        [data-bs-theme="dark"] .navbar {
            background-color: #2563eb !important;
        }
        [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-selection,
        [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-selection--multiple,
        [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-dropdown,
        [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-search__field,
        [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-results__option {
            background-color: #23272b !important;
            color: #fff !important;
            border-color: #343a40 !important;
        }
        [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-selection__choice {
            background-color: #343a40 !important;
            color: #fff !important;
            border-color: #495057 !important;
        }
        [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-results__option--highlighted {
            background-color: #2563eb !important;
            color: #fff !important;
        }
        [data-bs-theme="dark"] .select2-container--bootstrap-5 .select2-search__field::placeholder {
            color: #bbb !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="/dashboard.php">Todo App</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard.php">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/index.php">
                            <i class="bi bi-list-check"></i> Todo List
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-secondary me-2" id="toggleTheme">
                        <i class="bi bi-moon-stars"></i>
                    </button>
                    <button class="btn btn-outline-secondary me-2" id="toggleShortcuts">
                        <i class="bi bi-keyboard"></i>
                    </button>
                    <form method="POST" action="logout.php" class="d-inline">
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Todo List</h1>
            <div>
                <button type="button" class="btn btn-primary me-2" id="toggleAddForm">
                    <i class="bi bi-plus-circle"></i> Add New Todo
                    <span class="shortcut-key ms-2">Ctrl + N</span>
                </button>
                <a href="/dashboard.php" class="btn btn-outline-primary">
                    <i class="bi bi-speedometer2"></i> Back to Dashboard
                </a>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-funnel"></i> Filters
                    <span class="shortcut-key ms-2">Ctrl + F</span>
                </h5>
                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                    <i class="bi bi-chevron-down"></i>
                </button>
            </div>
            <div class="collapse show" id="filterCollapse">
                <div class="card-body">
                    <form method="GET" class="mb-0">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Search todos..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
                                    <span class="input-group-text">
                                        <span class="shortcut-key">Ctrl + S</span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <select name="category_id" class="form-select">
                                    <option value="">All Categories</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>" <?= $filters['category_id'] == $category['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="priority" class="form-select">
                                    <option value="">All Priorities</option>
                                    <option value="high" <?= $filters['priority'] === 'high' ? 'selected' : '' ?>>High</option>
                                    <option value="medium" <?= $filters['priority'] === 'medium' ? 'selected' : '' ?>>Medium</option>
                                    <option value="low" <?= $filters['priority'] === 'low' ? 'selected' : '' ?>>Low</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="completed" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="1" <?= $filters['completed'] === true ? 'selected' : '' ?>>Completed</option>
                                    <option value="0" <?= $filters['completed'] === false ? 'selected' : '' ?>>Pending</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="due_date" class="form-select">
                                    <option value="">All Dates</option>
                                    <option value="today" <?= $filters['due_date'] === 'today' ? 'selected' : '' ?>>Due Today</option>
                                    <option value="week" <?= $filters['due_date'] === 'week' ? 'selected' : '' ?>>Due This Week</option>
                                    <option value="overdue" <?= $filters['due_date'] === 'overdue' ? 'selected' : '' ?>>Overdue</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Category/Tag Add Buttons -->
        <div class="mb-3 d-flex gap-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="bi bi-folder-plus"></i> Add Category
            </button>
            <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addTagModal">
                <i class="bi bi-tag"></i> Add Tag
            </button>
        </div>

        <!-- Add Category Modal -->
        <div class="modal fade" id="addCategoryModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="add_category.php">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="categoryName" class="form-label">Name</label>
                                <input type="text" class="form-control" id="categoryName" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="categoryColor" class="form-label">Color</label>
                                <input type="color" class="form-control form-control-color" id="categoryColor" name="color" value="#6c757d">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add Tag Modal -->
        <div class="modal fade" id="addTagModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="add_tag.php">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Tag</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="tagName" class="form-label">Name</label>
                                <input type="text" class="form-control" id="tagName" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="tagColor" class="form-label">Color</label>
                                <input type="color" class="form-control form-control-color" id="tagColor" name="color" value="#6c757d">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Add Tag</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add Todo Form -->
        <div class="card mb-4 add-form collapsed" id="addTodoForm">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="bi bi-plus-circle"></i> Add New Todo
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="add.php">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="title" class="form-control" placeholder="Todo title" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="description" class="form-control" placeholder="Description (optional)">
                        </div>
                        <div class="col-md-2">
                            <select name="category_id" class="form-select">
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="priority" class="form-select">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <input type="datetime-local" name="due_date" class="form-control">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <select name="tags[]" class="form-select" multiple="multiple">
                                <?php foreach ($tags as $tag): ?>
                                    <option value="<?= $tag['id'] ?>"><?= htmlspecialchars($tag['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Todo List -->
        <div class="list-group" id="todoList">
            <?php if (empty($todos)): ?>
                <div class="card text-center p-5 my-4 bg-light border-0 shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title mb-2"><i class="bi bi-emoji-neutral"></i> 등록된 할 일이 없습니다.</h4>
                        <p class="card-text text-muted">상단의 <b>"Add New Todo"</b> 버튼을 눌러 새로운 할 일을 추가해보세요!</p>
                    </div>
                </div>
            <?php else: ?>
            <?php foreach ($todos as $todo): 
                $isOverdue = $todo['due_date'] && strtotime($todo['due_date']) < time() && !$todo['completed'];
                $priorityClass = 'priority-' . $todo['priority'];
                $listItemClass = "list-group-item d-flex justify-content-between align-items-center $priorityClass todo-item";
                if ($isOverdue) {
                    $listItemClass .= ' overdue';
                }
            ?>
                <div class="<?= $listItemClass ?>" data-todo-id="<?= $todo['id'] ?>" data-bs-toggle="modal" data-bs-target="#todoModal" data-todo='<?= json_encode($todo) ?>'>
                    <div class="d-flex align-items-center flex-grow-1">
                        <form method="POST" action="toggle.php" class="me-2" onclick="event.stopPropagation()">
                            <input type="hidden" name="id" value="<?= $todo['id'] ?>">
                            <button type="submit" class="btn btn-sm <?= $todo['completed'] ? 'btn-success' : 'btn-outline-success' ?>">
                                <?= $todo['completed'] ? '✓' : '○' ?>
                            </button>
                        </form>
                        <div class="<?= $todo['completed'] ? 'completed' : '' ?>">
                            <h5 class="mb-1"><?= htmlspecialchars($todo['title']) ?></h5>
                            <?php if (!empty($todo['description'])): ?>
                                <small class="text-muted"><?= htmlspecialchars($todo['description']) ?></small>
                            <?php endif; ?>
                            <div class="mt-1">
                                <?php if ($todo['category_name']): ?>
                                    <span class="category-badge" style="background-color: <?= $todo['category_color'] ?>">
                                        <?= htmlspecialchars($todo['category_name']) ?>
                                    </span>
                                <?php endif; ?>
                                <span class="badge bg-<?= $todo['priority'] === 'high' ? 'danger' : ($todo['priority'] === 'medium' ? 'warning' : 'success') ?>">
                                    <?= ucfirst($todo['priority']) ?> Priority
                                </span>
                                <?php foreach ($todo['tags'] as $tag): ?>
                                    <span class="tag" style="background-color: <?= $tag['color'] ?>">
                                        <?= htmlspecialchars($tag['name']) ?>
                                    </span>
                                <?php endforeach; ?>
                                <?php if ($todo['due_date']): ?>
                                    <small class="text-muted ms-2">
                                        <i class="bi bi-calendar"></i>
                                        Due: <?= date('M j, Y g:i A', strtotime($todo['due_date'])) ?>
                                        <?php if ($isOverdue): ?>
                                            <span class="text-danger">(Overdue)</span>
                                        <?php endif; ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <button class="btn btn-outline-secondary btn-sm me-2" onclick="shareTodoClick(event, <?= $todo['id'] ?>)">
                            <i class="bi bi-share"></i>
                        </button>
                        <a href="edit.php?id=<?= $todo['id'] ?>" class="btn btn-outline-primary btn-sm me-2" onclick="event.stopPropagation()">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="delete.php" class="ms-2" onclick="event.stopPropagation()">
                            <input type="hidden" name="id" value="<?= $todo['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Todo Detail Modal -->
    <div class="modal fade" id="todoModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Todo Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 id="modalTodoTitle"></h4>
                            <p id="modalTodoDescription" class="text-muted"></p>
                            <div class="mb-3">
                                <strong>Category:</strong>
                                <span id="modalTodoCategory" class="category-badge"></span>
                            </div>
                            <div class="mb-3">
                                <strong>Priority:</strong>
                                <span id="modalTodoPriority" class="badge"></span>
                            </div>
                            <div class="mb-3">
                                <strong>Tags:</strong>
                                <div id="modalTodoTags"></div>
                            </div>
                            <div class="mb-3">
                                <strong>Due Date:</strong>
                                <span id="modalTodoDueDate"></span>
                            </div>
                            <div class="mb-3">
                                <strong>Status:</strong>
                                <span id="modalTodoStatus"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h5>Comments</h5>
                            <div id="modalTodoComments" class="mb-3"></div>
                            <form id="commentForm" class="mt-3">
                                <div class="mb-3">
                                    <textarea class="form-control" id="commentContent" rows="3" placeholder="Add a comment..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Add Comment</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="#" id="modalEditButton" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Share Todo Modal -->
    <div class="modal fade" id="shareModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Share Todo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="shareForm">
                        <input type="hidden" id="shareTodoId">
                        <div class="mb-3">
                            <label class="form-label">Share with</label>
                            <select class="form-select" id="shareWithUser" required>
                                <option value="">Select user...</option>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['username']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="canEdit">
                                <label class="form-check-label" for="canEdit">
                                    Allow editing
                                </label>
                            </div>
                        </div>
                    </form>
                    <div id="sharedUsers" class="mt-3">
                        <h6>Currently shared with:</h6>
                        <ul class="list-group" id="sharedUsersList"></ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="shareButton">Share</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Keyboard Shortcuts Hint -->
    <div class="shortcut-hint" id="shortcutHint">
        <h5 class="mb-3">Keyboard Shortcuts</h5>
        <div class="mb-2">
            <span class="shortcut-key">Ctrl</span> + <span class="shortcut-key">N</span>
            <span class="ms-2">Add New Todo</span>
        </div>
        <div class="mb-2">
            <span class="shortcut-key">Ctrl</span> + <span class="shortcut-key">F</span>
            <span class="ms-2">Toggle Filters</span>
        </div>
        <div class="mb-2">
            <span class="shortcut-key">Ctrl</span> + <span class="shortcut-key">S</span>
            <span class="ms-2">Focus Search</span>
        </div>
        <div class="mb-2">
            <span class="shortcut-key">?</span>
            <span class="ms-2">Show/Hide Shortcuts</span>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

        // Add Todo Form Toggle
        const toggleAddForm = document.getElementById('toggleAddForm');
        const addTodoForm = document.getElementById('addTodoForm');
        
        toggleAddForm.addEventListener('click', function() {
            addTodoForm.classList.toggle('collapsed');
            this.innerHTML = addTodoForm.classList.contains('collapsed') 
                ? '<i class="bi bi-plus-circle"></i> Add New Todo <span class="shortcut-key ms-2">Ctrl + N</span>'
                : '<i class="bi bi-x-circle"></i> Cancel';
        });

        // Keyboard Shortcuts
        const shortcutHint = document.getElementById('shortcutHint');
        const toggleShortcuts = document.getElementById('toggleShortcuts');

        function isMac() {
            return navigator.platform.toUpperCase().indexOf('MAC') >= 0;
        }
        function isCtrlOrCmd(e) {
            return isMac() ? e.metaKey : e.ctrlKey;
        }

        document.addEventListener('keydown', (e) => {
            const active = document.activeElement;
            const isInput = active && (
                active.tagName === 'INPUT' ||
                active.tagName === 'TEXTAREA' ||
                active.isContentEditable
            );
            if (isInput) return; // 입력란에서는 단축키 무시
            if (isCtrlOrCmd(e)) {
                switch(e.key.toLowerCase()) {
                    case 'n':
                        e.preventDefault();
                        toggleAddForm.click();
                        break;
                    case 'f':
                        e.preventDefault();
                        document.getElementById('filterCollapse').classList.toggle('show');
                        break;
                    case 's':
                        e.preventDefault();
                        document.querySelector('input[name="search"]').focus();
                        break;
                }
            } else if (e.key === '?') {
                e.preventDefault();
                shortcutHint.classList.toggle('show');
            }
        });

        toggleShortcuts.addEventListener('click', () => {
            shortcutHint.classList.toggle('show');
        });

        // 단축키 안내 텍스트도 Mac/Windows에 따라 동적으로 변경
        document.addEventListener('DOMContentLoaded', function() {
            const shortcutKeys = document.querySelectorAll('.shortcut-key');
            shortcutKeys.forEach(function(el) {
                if (el.textContent.includes('Ctrl')) {
                    el.textContent = isMac() ? '⌘' + el.textContent.replace('Ctrl', '') : el.textContent;
                }
            });
            // 힌트 영역도 변경
            if (isMac()) {
                document.querySelectorAll('.shortcut-hint .shortcut-key').forEach(function(el) {
                    if (el.textContent === 'Ctrl') el.textContent = '⌘';
                });
            }
        });

        // Drag and Drop
        new Sortable(document.getElementById('todoList'), {
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: function(evt) {
                const todoId = evt.item.dataset.todoId;
                const newIndex = evt.newIndex;
                // Here you would typically send an AJAX request to update the order
                console.log(`Moved todo ${todoId} to position ${newIndex}`);
            }
        });

        // Initialize Select2
        $(document).ready(function() {
            $('select[name="tags[]"]').select2({
                theme: 'bootstrap-5',
                placeholder: 'Select tags...',
                allowClear: true
            });
        });

        // Todo Modal
        const todoModal = document.getElementById('todoModal');
        todoModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const todo = JSON.parse(button.dataset.todo);
            
            document.getElementById('modalTodoTitle').textContent = todo.title;
            document.getElementById('modalTodoDescription').textContent = todo.description || 'No description';
            
            const categoryBadge = document.getElementById('modalTodoCategory');
            if (todo.category_name) {
                categoryBadge.textContent = todo.category_name;
                categoryBadge.style.backgroundColor = todo.category_color;
                categoryBadge.style.display = 'inline-block';
            } else {
                categoryBadge.style.display = 'none';
            }

            document.getElementById('modalTodoPriority').textContent = todo.priority.charAt(0).toUpperCase() + todo.priority.slice(1);
            document.getElementById('modalTodoPriority').className = `badge bg-${todo.priority === 'high' ? 'danger' : (todo.priority === 'medium' ? 'warning' : 'success')}`;
            
            const tagsContainer = document.getElementById('modalTodoTags');
            tagsContainer.innerHTML = '';
            todo.tags.forEach(tag => {
                const tagSpan = document.createElement('span');
                tagSpan.className = 'tag';
                tagSpan.style.backgroundColor = tag.color;
                tagSpan.textContent = tag.name;
                tagsContainer.appendChild(tagSpan);
            });

            document.getElementById('modalTodoDueDate').textContent = todo.due_date ? new Date(todo.due_date).toLocaleString() : 'No due date';
            document.getElementById('modalTodoStatus').textContent = todo.completed ? 'Completed' : 'Pending';
            document.getElementById('modalEditButton').href = `edit.php?id=${todo.id}`;

            // Load comments
            fetch(`get_comments.php?todo_id=${todo.id}`)
                .then(response => response.json())
                .then(comments => {
                    const commentsContainer = document.getElementById('modalTodoComments');
                    commentsContainer.innerHTML = '';
                    comments.forEach(comment => {
                        const commentDiv = document.createElement('div');
                        commentDiv.className = 'comment';
                        commentDiv.innerHTML = `
                            <div class="comment-meta">
                                <strong>${comment.username}</strong> - ${new Date(comment.created_at).toLocaleString()}
                            </div>
                            <div class="comment-content">${comment.content}</div>
                        `;
                        commentsContainer.appendChild(commentDiv);
                    });
                });
        });

        // Comment Form
        document.getElementById('commentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const todoId = document.querySelector('#todoModal .modal-content').dataset.todoId;
            const content = document.getElementById('commentContent').value;
            
            fetch('add_comment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    todo_id: todoId,
                    content: content
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        });

        // Share Todo
        function shareTodo(todoId) {
            document.getElementById('shareTodoId').value = todoId;
            fetch(`get_shared_users.php?todo_id=${todoId}`)
                .then(response => response.json())
                .then(users => {
                    const sharedUsersList = document.getElementById('sharedUsersList');
                    sharedUsersList.innerHTML = '';
                    users.forEach(user => {
                        const li = document.createElement('li');
                        li.className = 'list-group-item d-flex justify-content-between align-items-center';
                        li.innerHTML = `
                            ${user.username}
                            <div>
                                <span class="badge bg-${user.can_edit ? 'success' : 'secondary'} me-2">
                                    ${user.can_edit ? 'Can edit' : 'View only'}
                                </span>
                                <button class="btn btn-sm btn-danger" onclick="unshareTodo(${todoId}, ${user.id})">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        `;
                        sharedUsersList.appendChild(li);
                    });
                });
            new bootstrap.Modal(document.getElementById('shareModal')).show();
        }

        document.getElementById('shareButton').addEventListener('click', function() {
            const todoId = document.getElementById('shareTodoId').value;
            const userId = document.getElementById('shareWithUser').value;
            const canEdit = document.getElementById('canEdit').checked;

            fetch('share_todo.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    todo_id: todoId,
                    user_id: userId,
                    can_edit: canEdit
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        });

        function unshareTodo(todoId, userId) {
            fetch('unshare_todo.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    todo_id: todoId,
                    user_id: userId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }

        function shareTodoClick(e, todoId) {
            e.stopPropagation();
            e.preventDefault();
            shareTodo(todoId);
        }
    </script>
</body>
</html> 