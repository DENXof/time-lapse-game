<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Админ-панель - TimeLapse Games')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .admin-sidebar {
            background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
            min-height: 100vh;
            color: white;
            padding: 0;
            box-shadow: 3px 0 15px rgba(0,0,0,0.1);
            position: fixed;
            width: 250px;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 25px 20px;
            background: #1a252f;
            text-align: center;
            border-bottom: 1px solid #34495e;
        }

        .admin-sidebar .nav-link {
            color: #bdc3c7;
            padding: 15px 20px;
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
            margin: 5px 10px;
            border-radius: 8px;
            position: relative;
            z-index: 100;
            cursor: pointer !important;
            pointer-events: auto !important;
            user-select: none;
            font-size: 15px;
        }

        .admin-sidebar .nav-link:hover {
            color: white;
            background: rgba(52, 73, 94, 0.8);
            border-left-color: #3498db;
            transform: translateX(5px);
        }

        .admin-sidebar .nav-link.active {
            color: white;
            background: linear-gradient(90deg, #3498db, #2980b9);
            border-left-color: #1abc9c;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }

        .admin-sidebar .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: 10px;
            font-size: 16px;
        }

        .admin-content {
            padding: 30px;
            margin-left: 250px;
            min-height: 100vh;
        }

        .admin-header {
            background: white;
            padding: 25px 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border-left: 5px solid #3498db;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            border-top: 4px solid;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .stat-card.games { border-color: #3498db; }
        .stat-card.genres { border-color: #2ecc71; }
        .stat-card.views { border-color: #e74c3c; }
        .stat-card.recent { border-color: #f39c12; }

        .stat-icon {
            font-size: 2.8rem;
            opacity: 0.9;
            margin-bottom: 15px;
            color: inherit;
        }

        /* Улучшения для форм */
        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #3498db, #2980b9);
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #2980b9, #1c6ea4);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
        }

        /* Анимации */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        /* Для мобильных устройств */
        @media (max-width: 768px) {
            .admin-sidebar {
                position: relative;
                width: 100%;
                min-height: auto;
            }

            .admin-content {
                margin-left: 0;
                padding: 20px;
            }

            .admin-sidebar .nav-link {
                margin: 3px 5px;
                padding: 12px 15px;
                font-size: 14px;
            }
        }

        /* Убираем возможные перекрытия */
        .admin-sidebar .nav-link::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1;
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 px-0 admin-sidebar">
                <!-- Заголовок сайдбара -->
                <div class="sidebar-header">
                    <h4 class="mb-1">
                        <i class="fas fa-gamepad"></i>
                    </h4>
                    <h5 class="mb-0">TimeLapse</h5>
                    <small class="text-muted">Админ-панель</small>
                </div>

                <!-- Навигация -->
                <nav class="nav flex-column pt-3">
                    <a class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}"
                       href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Дашборд
                    </a>

                    <a class="nav-link {{ Request::is('admin/genres*') ? 'active' : '' }}"
                       href="{{ route('admin.genres.index') }}">
                        <i class="fas fa-tags"></i> Жанры игр
                    </a>

                    <a class="nav-link {{ Request::is('admin/games*') ? 'active' : '' }}"
                       href="{{ route('admin.games.index') }}">
                        <i class="fas fa-gamepad"></i> Управление играми
                    </a>

                    <a class="nav-link {{ Request::is('timeline') ? 'active' : '' }}"
                       href="{{ route('timeline') }}">
                        <i class="fas fa-timeline"></i> Таймлайн
                    </a>

                    <div class="mt-4 pt-3 border-top border-secondary">
                        <a class="nav-link" href="{{ route('home') }}">
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

            <!-- Main content -->
            <div class="col-lg-10 admin-content">
                <!-- Header -->
                <div class="admin-header fade-in">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">@yield('page-title', 'Админ-панель')</h3>
                            <p class="text-muted mb-0">@yield('page-subtitle', 'Панель управления TimeLapse Games')</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="me-3 text-end">
                                <div class="fw-bold">
                                    <i class="fas fa-user-shield text-primary me-1"></i>
                                    {{ auth()->guard('admin')->user()->name ?? 'Администратор' }}
                                </div>
                                <small class="text-muted">Администратор</small>
                            </div>
                            <div class="avatar bg-primary rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 45px; height: 45px;">
                                <i class="fas fa-user text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Уведомления -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show fade-in" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show fade-in" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Основной контент -->
                <div class="fade-in">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery ОБЯЗАТЕЛЬНО ПЕРЕД Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Скрипты для админ-панели -->
    <script>
        $(document).ready(function() {
            console.log('✅ Админ-панель загружена');
            console.log('✅ jQuery версия:', $.fn.jquery);

            // 1. Инициализация бокового меню
            initSidebarMenu();

            // 2. Инициализация Bootstrap компонентов
            initBootstrapComponents();

            // 3. Улучшение UI
            enhanceAdminUI();
        });

        function initSidebarMenu() {
            console.log('🔄 Инициализация бокового меню...');

            // Делегирование событий для меню
            $(document).on('click', '.admin-sidebar .nav-link', function(e) {
                console.log('📌 Клик по меню:', $(this).text().trim());

                // Убираем активный класс у всех элементов
                $('.admin-sidebar .nav-link').removeClass('active');

                // Добавляем активный класс текущему
                $(this).addClass('active');

                // Добавляем анимацию
                $(this).css({
                    'transform': 'translateX(0)'
                });

                // Логируем переход
                const href = $(this).attr('href');
                if (href && href !== '#') {
                    console.log('➡️ Переход к:', href);
                }

                return true;
            });

            // Автоматически активируем текущую страницу
            highlightCurrentPage();

            console.log('✅ Боковое меню инициализировано');
        }

        function highlightCurrentPage() {
            const currentUrl = window.location.pathname;
            console.log('📍 Текущая страница:', currentUrl);

            $('.admin-sidebar .nav-link').each(function() {
                const linkUrl = $(this).attr('href');
                if (linkUrl) {
                    const cleanLink = linkUrl.replace(window.location.origin, '');
                    if (currentUrl.includes(cleanLink) ||
                        (cleanLink === '/' && currentUrl === '/admin/dashboard')) {
                        $(this).addClass('active');
                        console.log('🎯 Активное меню:', $(this).text().trim());
                    }
                }
            });
        }

        function initBootstrapComponents() {
            // Tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    trigger: 'hover'
                });
            });

            // Инициализация алертов
            $('.alert').alert();

            // Инициализация dropdown
            $('.dropdown-toggle').dropdown();

            console.log('✅ Bootstrap компоненты инициализированы');
        }

        function enhanceAdminUI() {
            // Плавная прокрутка для якорей
            $('a[href^="#"]').on('click', function(e) {
                if (this.hash !== '') {
                    e.preventDefault();
                    const hash = this.hash;
                    $('html, body').animate({
                        scrollTop: $(hash).offset().top - 70
                    }, 600, 'swing');
                }
            });

            // Добавляем классы для таблиц
            $('table:not(.table)').addClass('table table-hover table-striped');

            // Подтверждение для опасных действий
            $('form[data-confirm], button[data-confirm]').on('submit click', function(e) {
                const message = $(this).data('confirm') || 'Вы уверены, что хотите выполнить это действие?';
                if (!confirm(message)) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    return false;
                }
            });

            // Анимация для карточек при загрузке
            $('.stat-card').each(function(i) {
                $(this).css({
                    'animation-delay': (i * 0.1) + 's',
                    'opacity': '0'
                }).animate({
                    'opacity': '1'
                }, 500);
            });

            console.log('✅ UI улучшения применены');
        }

        // Глобальные функции для админки
        window.adminUtils = {
            showLoader: function() {
                $('body').append('<div class="admin-loader"><div class="spinner"></div></div>');
            },
            hideLoader: function() {
                $('.admin-loader').remove();
            },
            showToast: function(message, type = 'success') {
                const toast = $('<div class="admin-toast alert alert-' + type + '">' + message + '</div>');
                $('body').append(toast);
                setTimeout(() => toast.remove(), 3000);
            }
        };
    </script>

    @stack('scripts')
</body>
</html>
