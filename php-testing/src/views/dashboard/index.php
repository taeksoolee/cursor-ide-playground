<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Todo App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
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
        .stat-card {
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        /* Card header dark mode fix */
        [data-bs-theme="dark"] .card-header {
            background-color: #23272b !important;
            color: #fff !important;
            border-bottom: 1px solid #343a40 !important;
        }
        .dashboard-cards-row {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            justify-content: stretch;
            width: 100%;
        }
        .dashboard-cards-row .dashboard-card-link {
            flex: 1 1 0;
            min-width: 0;
            max-width: none;
        }
        .dashboard-card-link {
            display: block;
            transition: transform 0.12s, box-shadow 0.12s;
            border-radius: 1rem;
            height: 100%;
            text-decoration: none !important;
        }
        .dashboard-card-link:hover {
            transform: translateY(-6px) scale(1.04);
            box-shadow: 0 6px 24px rgba(0,0,0,0.10);
        }
        .dashboard-card {
            border-radius: 1rem;
            border: 1px solid #e5e7eb;
            background: #fff;
            min-height: 140px;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            padding: 2.2rem 1.2rem 2.2rem 1.2rem;
        }
        .dashboard-card .icon {
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
        }
        .dashboard-card .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.2rem;
        }
        .dashboard-card .card-value {
            font-size: 2.1rem;
            font-weight: 700;
        }
        .dashboard-card .card-value.text-success { color: #22c55e !important; }
        .dashboard-card .card-value.text-danger { color: #ef4444 !important; }
        .dashboard-card .card-value.text-warning { color: #eab308 !important; }
        .dashboard-card .card-value.text-primary { color: #2563eb !important; }
        /* 차트 카드 */
        .dashboard-charts-row {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 2.5rem;
            justify-content: stretch;
            width: 100%;
        }
        .dashboard-chart-card {
            border-radius: 1.2rem;
            border: 1px solid #e5e7eb;
            background: #fff;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            padding: 1.5rem 1.2rem 1.2rem 1.2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 0;
            flex: 1 1 0;
            max-width: none;
        }
        .dashboard-chart-title {
            font-size: 1.08rem;
            font-weight: 600;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #2563eb;
        }
        .dashboard-chart-card canvas {
            max-width: 320px;
            max-height: 220px;
            width: 100% !important;
            height: 220px !important;
            margin: 0 auto;
            display: block;
        }
        @media (max-width: 1200px) {
            .dashboard-cards-row, .dashboard-charts-row { flex-direction: column; align-items: stretch; }
        }
        @media (max-width: 768px) {
            .dashboard-cards-row, .dashboard-charts-row { flex-direction: column; align-items: stretch; }
        }
        @media (max-width: 500px) {
            .dashboard-cards { grid-template-columns: 1fr; }
        }
        /* 다크모드 카드 배경 */
        [data-bs-theme="dark"] .dashboard-card, [data-bs-theme="dark"] .dashboard-chart-card {
            background: #23272b !important;
            border-color: #343a40 !important;
            color: #fff !important;
        }
        [data-bs-theme="dark"] .dashboard-chart-title {
            color: #60a5fa;
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
                        <a class="nav-link active" href="/dashboard.php">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/index.php">
                            <i class="bi bi-list-check"></i> Todo List
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-secondary me-2" id="toggleTheme">
                        <i class="bi bi-moon-stars"></i>
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
        <h1 class="mb-4">Dashboard</h1>
        
        <!-- Statistics -->
        <div class="dashboard-cards-row">
            <a href="/index.php" class="dashboard-card-link">
                <div class="dashboard-card text-center">
                    <div class="icon text-primary"><i class="bi bi-list-check"></i></div>
                    <div class="card-title">Total Todos</div>
                    <div class="card-value "><?= $stats['total'] ?></div>
                </div>
            </a>
            <a href="/index.php?completed=1" class="dashboard-card-link">
                <div class="dashboard-card text-center">
                    <div class="icon text-success"><i class="bi bi-check-circle"></i></div>
                    <div class="card-title">Completed</div>
                    <div class="card-value text-success"><?= $stats['completed'] ?></div>
                </div>
            </a>
        </div>
        <div class="dashboard-cards-row">
            <a href="/index.php?due_date=overdue" class="dashboard-card-link">
                <div class="dashboard-card text-center">
                    <div class="icon text-danger"><i class="bi bi-exclamation-circle"></i></div>
                    <div class="card-title">Overdue</div>
                    <div class="card-value text-danger"><?= $stats['overdue'] ?></div>
                </div>
            </a>
            <a href="/index.php?due_date=today" class="dashboard-card-link">
                <div class="dashboard-card text-center">
                    <div class="icon text-warning"><i class="bi bi-calendar-day"></i></div>
                    <div class="card-title">Due Today</div>
                    <div class="card-value text-warning"><?= $stats['due_today'] ?></div>
                </div>
            </a>
            <a href="/index.php?due_date=week" class="dashboard-card-link">
                <div class="dashboard-card text-center">
                    <div class="icon text-primary"><i class="bi bi-calendar-week"></i></div>
                    <div class="card-title">Due This Week</div>
                    <div class="card-value text-primary"><?= $stats['due_week'] ?></div>
                </div>
            </a>
        </div>

        <!-- Statistics Charts -->
        <div class="dashboard-charts-row">
            <div class="dashboard-chart-card">
                <div class="dashboard-chart-title"><i class="bi bi-pie-chart"></i> 완료/미완료 비율</div>
                <canvas id="completionDonut"></canvas>
            </div>
            <div class="dashboard-chart-card">
                <div class="dashboard-chart-title"><i class="bi bi-bar-chart"></i> 우선순위별 할 일 개수</div>
                <canvas id="priorityBar"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        // PHP -> JS 데이터 전달
        const stats = {
            total: <?= (int)$stats['total'] ?>,
            completed: <?= (int)$stats['completed'] ?>,
            overdue: <?= (int)$stats['overdue'] ?>,
            due_today: <?= (int)$stats['due_today'] ?>,
            due_week: <?= (int)$stats['due_week'] ?>
        };
        // 우선순위별 개수 (예시: PHP에서 계산해서 전달, 없으면 0)
        const priorityCounts = {
            high: <?= isset($stats['priority_high']) ? (int)$stats['priority_high'] : 0 ?>,
            medium: <?= isset($stats['priority_medium']) ? (int)$stats['priority_medium'] : 0 ?>,
            low: <?= isset($stats['priority_low']) ? (int)$stats['priority_low'] : 0 ?>
        };
        // 완료/미완료 도넛 차트
        new Chart(document.getElementById('completionDonut'), {
            type: 'doughnut',
            data: {
                labels: ['완료', '미완료'],
                datasets: [{
                    data: [stats.completed, Math.max(0, stats.total - stats.completed)],
                    backgroundColor: ['#22c55e', '#2563eb'],
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: { display: true, position: 'bottom' }
                }
            }
        });
        // 우선순위별 바 차트
        new Chart(document.getElementById('priorityBar'), {
            type: 'bar',
            data: {
                labels: ['High', 'Medium', 'Low'],
                datasets: [{
                    label: '할 일 개수',
                    data: [priorityCounts.high, priorityCounts.medium, priorityCounts.low],
                    backgroundColor: ['#ef4444', '#eab308', '#22c55e']
                }]
            },
            options: {
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    </script>
</body>
</html> 