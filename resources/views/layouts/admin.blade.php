{{--ШАБЛОН ДЛЯ АДМИН-ПАНЕЛИ
    Здесь задается общая структура всех страниц админки:
    - левое меню (сайдбар)
    - верхняя панель с пользователем
    - место для контента
--}}

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Заголовок страницы (можно менять в дочерних шаблонах) --}}
    <title>@yield('title', 'Админ-панель - TimeLapse Games')</title>

    {{-- Bootstrap 5 - для красивых кнопок, карточек, сетки --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome - для иконок --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Иконка сайта (фавикон) --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ time() }}">

    {{--
        ============================================
        СТИЛИ ДЛЯ АДМИН-ПАНЕЛИ
        ============================================
    --}}
    <style>
        /* Основные настройки страницы */
        body {
            background-color: #f8f9fa;  /* Светло-серый фон */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /*
            ЛЕВОЕ МЕНЮ (САЙДБАР)
            Фиксированное, темное, с градиентом
        */
        .admin-sidebar {
            background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);  /* Темный градиент */
            min-height: 100vh;  /* На всю высоту экрана */
            color: white;
            padding: 0;
            box-shadow: 3px 0 15px rgba(0,0,0,0.1);  /* Тень справа */
            position: fixed;  /* Приклеено к левому краю */
            width: 250px;  /* Фиксированная ширина */
            z-index: 1000;  /* Поверх всего */
        }

        /* Шапка сайдбара с логотипом */
        .sidebar-header {
            padding: 25px 20px;
            background: #1a252f;  /* Еще темнее */
            text-align: center;
            border-bottom: 1px solid #34495e;  /* Разделитель */
        }

        /*
            ССЫЛКИ В МЕНЮ
        */
        .admin-sidebar .nav-link {
            color: #bdc3c7;  /* Светло-серый текст */
            padding: 15px 20px;
            border-left: 4px solid transparent;  /* Полоска слева (прозрачная) */
            transition: all 0.3s ease;  /* Плавные анимации */
            margin: 5px 10px;
            border-radius: 8px;
            font-size: 15px;
        }

        /* При наведении */
        .admin-sidebar .nav-link:hover {
            color: white;
            background: rgba(52, 73, 94, 0.8);  /* Полупрозрачный фон */
            border-left-color: #3498db;  /* Синяя полоска слева */
            transform: translateX(5px);  /* Сдвиг вправо */
        }

        /* Активная страница */
        .admin-sidebar .nav-link.active {
            color: white;
            background: linear-gradient(90deg, #3498db, #2980b9);  /* Синий градиент */
            border-left-color: #1abc9c;  /* Бирюзовая полоска */
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);  /* Сияние */
        }

        /* Иконки в меню */
        .admin-sidebar .nav-link i {
            width: 20px;  /* Фиксированная ширина */
            text-align: center;
            margin-right: 10px;
            font-size: 16px;
        }

        /*
            ОСНОВНОЙ КОНТЕНТ (справа от меню)
        */
        .admin-content {
            padding: 30px;
            margin-left: 250px;  /* Отступ, чтобы не залезать под меню */
            min-height: 100vh;
        }

        /*
            ВЕРХНЯЯ ПАНЕЛЬ (HEADER)
            С заголовком страницы и профилем
        */
        .admin-header {
            background: white;
            padding: 25px 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border-left: 5px solid #3498db;  /* Синяя полоска слева */
        }

        /*
            КАРТОЧКИ СО СТАТИСТИКОЙ (на дашборде)
        */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            border-top: 4px solid;  /* Цветная полоска сверху */
            height: 100%;
        }

        /* Эффект при наведении на карточку */
        .stat-card:hover {
            transform: translateY(-8px);  /* Подпрыгивает вверх */
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);  /* Тень больше */
        }

        /* Цвета для разных карточек */
        .stat-card.games { border-color: #3498db; }  /* Синий - игры */
        .stat-card.genres { border-color: #2ecc71; }  /* Зеленый - жанры */
        .stat-card.views { border-color: #e74c3c; }  /* Красный - просмотры */
        .stat-card.recent { border-color: #f39c12; }  /* Оранжевый - новые */

        /* Иконки в карточках статистики */
        .stat-icon {
            font-size: 2.8rem;
            opacity: 0.9;
            margin-bottom: 15px;
        }

        /*
            КРАСИВЫЕ КНОПКИ
        */
        .btn-primary {
            background: linear-gradient(135deg, #3498db, #2980b9);
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #2980b9, #1c6ea4);
            transform: translateY(-2px);  /* Подпрыгивает */
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
        }

        /*
            АНИМАЦИЯ ПОЯВЛЕНИЯ
        */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        /*
            ДЛЯ МОБИЛЬНЫХ УСТРОЙСТВ
        */
        @media (max-width: 768px) {
            .admin-sidebar {
                position: relative;  /* Не фиксированное */
                width: 100%;  /* На всю ширину */
                min-height: auto;
            }

            .admin-content {
                margin-left: 0;  /* Убираем отступ */
                padding: 20px;
            }
        }

        /*
            СТИЛИ ДЛЯ ВЫПАДАЮЩЕГО МЕНЮ (ДРОПДАУН)
        */
        .dropdown-menu {
            min-width: 220px;
            border-radius: 10px;
        }

        .dropdown-item {
            padding: 10px 15px;
            border-radius: 6px;
            margin: 2px 5px;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            transform: translateX(3px);  /* Сдвиг вправо при наведении */
        }
    </style>

    {{-- Место для дополнительных стилей из дочерних шаблонов --}}
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">

            {{--
                ========================================
                ЛЕВОЕ МЕНЮ (САЙДБАР)
                ========================================
            --}}
            <div class="col-lg-2 px-0 admin-sidebar">

                {{-- ШАПКА САЙДБАРА --}}
                <div class="sidebar-header">
                    <h4 class="mb-1">
                        <i class="fas fa-gamepad"></i>  {{-- Иконка контроллера --}}
                    </h4>
                    <h5 class="mb-0">TimeLapse</h5>
                    <small class="text-muted">Админ-панель</small>
                </div>

                {{-- НАВИГАЦИЯ --}}
                <nav class="nav flex-column pt-3">

                    {{-- Дашборд (главная) --}}
                    <a class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}"
                       href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Дашборд
                    </a>

                    {{-- Жанры --}}
                    <a class="nav-link {{ Request::is('admin/genres*') ? 'active' : '' }}"
                       href="{{ route('admin.genres.index') }}">
                        <i class="fas fa-tags"></i> Жанры игр
                    </a>

                    {{-- Игры --}}
                    <a class="nav-link {{ Request::is('admin/games*') ? 'active' : '' }}"
                       href="{{ route('admin.games.index') }}">
                        <i class="fas fa-gamepad"></i> Управление играми
                    </a>

                    {{-- Профиль --}}
                    <a class="nav-link {{ Request::is('admin/profile*') ? 'active' : '' }}"
                       href="{{ route('admin.profile.edit') }}">
                        <i class="fas fa-user-cog"></i> Профиль
                    </a>

                    {{-- Таймлайн --}}
                    <a class="nav-link {{ Request::is('timeline') ? 'active' : '' }}"
                       href="{{ route('timeline') }}">
                        <i class="fas fa-timeline"></i> Таймлайн
                    </a>

                    {{-- РАЗДЕЛИТЕЛЬ --}}
                    <div class="mt-4 pt-3 border-top border-secondary">

                        {{-- Ссылка на сайт --}}
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-external-link-alt"></i> На сайт
                        </a>

                        {{-- Выход --}}
                        <a class="nav-link text-danger" href="{{ route('admin.logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i> Выйти
                        </a>

                        {{-- Скрытая форма для выхода --}}
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </nav>
            </div>

            {{--
                ========================================
                ОСНОВНОЙ КОНТЕНТ (СПРАВА)
                ========================================
            --}}
            <div class="col-lg-10 admin-content">

                {{-- ВЕРХНЯЯ ПАНЕЛЬ --}}
                <div class="admin-header fade-in">
                    <div class="d-flex justify-content-between align-items-center">

                        {{-- Заголовок страницы --}}
                        <div>
                            <h3 class="mb-1">@yield('page-title', 'Админ-панель')</h3>
                            <p class="text-muted mb-0">@yield('page-subtitle', 'Панель управления TimeLapse Games')</p>
                        </div>

                        {{-- БЛОК С ПОЛЬЗОВАТЕЛЕМ --}}
                        <div class="d-flex align-items-center">

                            {{-- Имя пользователя и ссылка на профиль --}}
                            <div class="me-3 text-end">
                                <div class="fw-bold">
                                    <i class="fas fa-user-shield text-primary me-1"></i>
                                    {{ auth()->guard('admin')->user()->name ?? 'Администратор' }}
                                </div>
                                <small class="text-muted">
                                    <a href="{{ route('admin.profile.edit') }}" class="text-decoration-none text-muted">
                                        <i class="fas fa-cog me-1"></i>Профиль
                                    </a>
                                </small>
                            </div>

                            {{--
                                ВЫПАДАЮЩЕЕ МЕНЮ ПОЛЬЗОВАТЕЛЯ
                                По клику на аватарку
                            --}}
                            <div class="dropdown">
                                {{-- Кнопка-аватарка --}}
                                <button class="btn btn-light rounded-circle d-flex align-items-center justify-content-center border-0 shadow-sm dropdown-toggle"
                                        style="width: 45px; height: 45px;"
                                        type="button"
                                        data-bs-toggle="dropdown">
                                    <i class="fas fa-user text-primary"></i>
                                </button>

                                {{-- Выпадающий список --}}
                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                    <li>
                                        <h6 class="dropdown-header">
                                            <i class="fas fa-user-circle me-2"></i>
                                            {{ auth()->guard('admin')->user()->name ?? 'Администратор' }}
                                        </h6>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>

                                    {{-- Пункты меню --}}
                                    <li><a class="dropdown-item" href="{{ route('admin.profile.edit') }}">✏️ Редактировать профиль</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">📊 Дашборд</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.games.index') }}">🎮 Управление играми</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.genres.index') }}">🏷️ Жанры игр</a></li>

                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('home') }}">🌐 Перейти на сайт</a></li>

                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="{{ route('admin.logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        🚪 Выйти из системы
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{--
                    УВЕДОМЛЕНИЯ
                    Показываются если в сессии есть success или error
                --}}
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

                {{--
                    МЕСТО ДЛЯ КОНТЕНТА
                    Сюда будет вставляться содержимое дочерних страниц
                --}}
                <div class="fade-in">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    {{--
        ========================================
        СКРИПТЫ
        ========================================
    --}}

    {{-- jQuery (обязательно до Bootstrap) --}}
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    {{--
        ДОПОЛНИТЕЛЬНЫЕ СКРИПТЫ ДЛЯ АДМИНКИ
        Инициализация меню, тултипов, анимаций
    --}}
    <script>
        // Ждем загрузки страницы
        $(document).ready(function() {
            console.log('✅ Админ-панель загружена');

            // Запускаем все функции
            initSidebarMenu();           // Боковое меню
            initBootstrapComponents();    // Тултипы и дропдауны
            enhanceAdminUI();             // Улучшения интерфейса
        });

        // Инициализация бокового меню
        function initSidebarMenu() {
            // При клике на пункт меню
            $(document).on('click', '.admin-sidebar .nav-link', function() {
                $('.admin-sidebar .nav-link').removeClass('active');
                $(this).addClass('active');
            });

            // Подсвечиваем текущую страницу
            highlightCurrentPage();
        }

        // Подсветка текущей страницы
        function highlightCurrentPage() {
            const currentUrl = window.location.pathname;

            $('.admin-sidebar .nav-link').each(function() {
                const linkUrl = $(this).attr('href');
                if (linkUrl && currentUrl.includes(linkUrl)) {
                    $(this).addClass('active');
                }
            });
        }

        // Инициализация компонентов Bootstrap
        function initBootstrapComponents() {
            // Тултипы (подсказки)
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(el) {
                return new bootstrap.Tooltip(el);
            });

            // Дропдауны (выпадающие меню)
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            dropdownElementList.map(function(el) {
                return new bootstrap.Dropdown(el);
            });
        }

        // Улучшения интерфейса
        function enhanceAdminUI() {
            // Анимация для карточек статистики
            $('.stat-card').each(function(i) {
                $(this).css('opacity', '0').animate({ 'opacity': '1' }, 500);
            });
        }
    </script>

    {{-- Место для дополнительных скриптов из дочерних шаблонов --}}
    @stack('scripts')
</body>
</html>
