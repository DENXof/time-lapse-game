{{--ШАБЛОН ДЛЯ АДМИН-ПАНЕЛИ--}}

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>@yield('title', 'Админ-панель - TimeLapse Games')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

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
            box-shadow: 3px 0 15px rgba(0,0,0,0.1);
            position: fixed;
            width: 280px;
            z-index: 1000;
            transition: all 0.3s;
        }

        .sidebar-header {
            padding: 25px 20px;
            background: #1a252f;
            text-align: center;
            border-bottom: 1px solid #34495e;
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
            transition: all 0.3s;
        }

        /* АДАПТИВНОСТЬ ДЛЯ АДМИНКИ */
        @media (max-width: 992px) {
            .admin-sidebar {
                width: 240px;
            }
            .admin-content {
                margin-left: 240px;
            }
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                position: relative;
                width: 100%;
                min-height: auto;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: block;
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 1100;
                background: #3498db;
                border: none;
                border-radius: 50%;
                width: 50px;
                height: 50px;
                color: white;
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            }
        }

        @media (min-width: 769px) {
            .sidebar-toggle {
                display: none;
            }
        }

        .admin-header {
            background: white;
            padding: 20px 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border-left: 5px solid #3498db;
        }

        /* Таблицы на мобильных */
        @media (max-width: 768px) {
            .table td,
            .table th {
                padding: 8px;
                font-size: 0.8rem;
                white-space: nowrap;
            }

            .btn-group-sm > .btn {
                padding: 4px 8px;
            }

            .card-header {
                flex-direction: column;
                gap: 10px;
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Кнопка для показа/скрытия меню на мобильных -->
    <button class="sidebar-toggle btn btn-primary" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-auto px-0 admin-sidebar" id="adminSidebar">
                <div class="sidebar-header">
                    <h4 class="mb-0">
                        <i class="fas fa-gamepad"></i> TimeLapse
                    </h4>
                    <small class="text-muted">Админ-панель</small>
                </div>

                <nav class="nav flex-column pt-3">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Дашборд
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.games.*') ? 'active' : '' }}" href="{{ route('admin.games.index') }}">
                        <i class="fas fa-gamepad"></i> Игры
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.genres.*') ? 'active' : '' }}" href="{{ route('admin.genres.index') }}">
                        <i class="fas fa-tags"></i> Жанры
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="fas fa-users"></i> Пользователи
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}" href="{{ route('admin.comments.index') }}">
                        <i class="fas fa-comments"></i> Комментарии
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}" href="{{ route('admin.logs.index') }}">
                        <i class="fas fa-history"></i> Логи
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                        <i class="fas fa-cog"></i> Настройки
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.import-games') ? 'active' : '' }}" href="{{ route('admin.import-games') }}">
    <i class="fas fa-database me-2"></i> Импорт игр
</a>

                    <div class="mt-4 pt-3 border-top border-secondary">
                        <a class="nav-link" href="{{ route('home') }}" target="_blank">
                            <i class="fas fa-external-link-alt"></i> На сайт
                        </a>
                        <a class="nav-link text-danger" href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i> Выйти
                        </a>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">@csrf</form>
                    </div>
                </nav>
            </div>

            <div class="col admin-content">
                <div class="admin-header">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
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
                                <li><a class="dropdown-item text-danger" href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">🚪 Выйти</a></li>
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

            // Мобильное меню
            $('#sidebarToggle').on('click', function() {
                $('#adminSidebar').toggleClass('show');
            });

            // Закрытие меню при клике вне его на мобильных
            $(document).on('click', function(event) {
                if ($(window).width() <= 768) {
                    if (!$(event.target).closest('#adminSidebar').length && !$(event.target).closest('#sidebarToggle').length) {
                        $('#adminSidebar').removeClass('show');
                    }
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
