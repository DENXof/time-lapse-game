{{--ШАБЛОН ДЛЯ АДМИН-ПАНЕЛИ--}}

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Админ-панель - TimeLapse Games')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ time() }}">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* ЛЕВОЕ МЕНЮ (САЙДБАР) */
        .admin-sidebar {
            background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
            min-height: 100vh;
            color: white;
            padding: 0;
            box-shadow: 3px 0 15px rgba(0,0,0,0.1);
            position: fixed;
            width: 280px;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 25px 20px;
            background: #1a252f;
            text-align: center;
            border-bottom: 1px solid #34495e;
        }

        .sidebar-header h4 {
            margin: 0;
            font-weight: bold;
        }

        .admin-sidebar .nav-link {
            color: #bdc3c7;
            padding: 12px 20px;
            transition: all 0.3s ease;
            margin: 5px 10px;
            border-radius: 8px;
            font-size: 14px;
        }

        .admin-sidebar .nav-link:hover {
            color: white;
            background: rgba(52, 73, 94, 0.8);
            transform: translateX(5px);
        }

        .admin-sidebar .nav-link.active {
            color: white;
            background: linear-gradient(90deg, #3498db, #2980b9);
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }

        .admin-sidebar .nav-link i {
            width: 25px;
            text-align: center;
            margin-right: 10px;
        }

        /* ОСНОВНОЙ КОНТЕНТ */
        .admin-content {
            margin-left: 280px;
            padding: 20px;
            min-height: 100vh;
        }

        .admin-header {
            background: white;
            padding: 20px 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border-left: 5px solid #3498db;
        }

        /* КАРТОЧКИ СТАТИСТИКИ */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            border-top: 4px solid;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .stat-card.games { border-color: #3498db; }
        .stat-card.genres { border-color: #2ecc71; }
        .stat-card.views { border-color: #e74c3c; }
        .stat-card.users { border-color: #9b59b6; }
        .stat-card.comments { border-color: #f39c12; }
        .stat-card.ratings { border-color: #e67e22; }

        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.7;
        }

        .stat-card h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        /* АНИМАЦИИ */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                position: relative;
                width: 100%;
                min-height: auto;
            }
            .admin-content {
                margin-left: 0;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">

            {{-- ЛЕВОЕ МЕНЮ (САЙДБАР) --}}
            <div class="col-12 col-md-auto px-0 admin-sidebar">
                <div class="sidebar-header">
                    <h4 class="mb-0">
                        <i class="fas fa-gamepad"></i> TimeLapse
                    </h4>
                    <small class="text-muted">Админ-панель</small>
                </div>

                <nav class="nav flex-column pt-3">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                       href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Дашборд
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.games.*') ? 'active' : '' }}"
                       href="{{ route('admin.games.index') }}">
                        <i class="fas fa-gamepad"></i> Игры
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.genres.*') ? 'active' : '' }}"
                       href="{{ route('admin.genres.index') }}">
                        <i class="fas fa-tags"></i> Жанры
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                       href="{{ route('admin.users.index') }}">
                        <i class="fas fa-users"></i> Пользователи
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}"
                       href="{{ route('admin.comments.index') }}">
                        <i class="fas fa-comments"></i> Комментарии
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}"
                       href="{{ route('admin.logs.index') }}">
                        <i class="fas fa-history"></i> Логи
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
                       href="{{ route('admin.settings.index') }}">
                        <i class="fas fa-cog"></i> Настройки
                    </a>

                    <div class="mt-4 pt-3 border-top border-secondary">
                        <a class="nav-link" href="{{ route('home') }}" target="_blank">
                            <i class="fas fa-external-link-alt"></i> На сайт
                        </a>
                        <a class="nav-link text-danger" href="{{ route('admin.logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i> Выйти
                        </a>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </nav>
            </div>

            {{-- ОСНОВНОЙ КОНТЕНТ --}}
            <div class="col admin-content">
                <div class="admin-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">@yield('page-title', 'Админ-панель')</h3>
                            <p class="text-muted mb-0">@yield('page-subtitle', 'Панель управления TimeLapse Games')</p>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-light rounded-circle d-flex align-items-center justify-content-center shadow-sm dropdown-toggle"
                                    style="width: 45px; height: 45px;"
                                    data-bs-toggle="dropdown">
                                <i class="fas fa-user text-primary"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li><h6 class="dropdown-header">{{ Auth::user()->name ?? 'Администратор' }}</h6></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('admin.profile.edit') }}">✏️ Редактировать профиль</a></li>
                                <li><a class="dropdown-item" href="{{ route('home') }}">🌐 Перейти на сайт</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="{{ route('admin.logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    🚪 Выйти
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show fade-in" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show fade-in" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="fade-in">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Подсветка активного пункта меню
            const currentUrl = window.location.pathname;
            $('.admin-sidebar .nav-link').each(function() {
                const linkUrl = $(this).attr('href');
                if (linkUrl && linkUrl !== '#' && currentUrl.includes(linkUrl)) {
                    $(this).addClass('active');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
