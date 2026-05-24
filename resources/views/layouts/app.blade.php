{{--ОСНОВНОЙ ШАБЛОН ДЛЯ ПУБЛИЧНОЙ ЧАСТИ САЙТА--}}

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'TimeLapse Games'))</title>
    <meta name="description" content="@yield('meta_description', 'История компьютерных игр: от ретро до современности. Описание игр, рейтинги, обзоры и обсуждения.')">
    <meta name="keywords" content="@yield('meta_keywords', 'игры, видеоигры, история игр, ретро игры, игровая индустрия')">
    <meta name="author" content="TimeLapse Games">
    <meta name="robots" content="index, follow">

    <link rel="canonical" href="@yield('canonical_url', url()->current())">

    <meta property="og:title" content="@yield('og_title', config('app.name'))">
    <meta property="og:description" content="@yield('og_description', 'История компьютерных игр в одном месте')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-image.jpg'))">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="TimeLapse Games">
    <meta property="og:locale" content="ru_RU">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', config('app.name'))">
    <meta name="twitter:description" content="@yield('og_description', 'История компьютерных игр в одном месте')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/og-image.jpg'))">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ========== ОСНОВНЫЕ СТИЛИ ========== */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 60px;
            background-color: #f8f9fa;
        }

        /* ========== МОБИЛЬНЫЕ НАСТРОЙКИ ========== */
        @media (max-width: 768px) {
            body {
                padding-top: 56px;
            }

            h1 {
                font-size: 1.75rem;
            }

            h2, h3 {
                font-size: 1.5rem;
            }

            .container {
                padding-left: 15px;
                padding-right: 15px;
            }

            /* Улучшаем отступы на мобильных */
            .py-4 {
                padding-top: 1rem !important;
                padding-bottom: 1rem !important;
            }

            /* Карточки игр на мобильных */
            .game-card-img {
                height: 150px;
            }

            /* Бейджи на мобильных */
            .badge {
                font-size: 0.7rem;
                padding: 4px 8px;
                margin-bottom: 4px;
                display: inline-block;
            }

            /* Хлебные крошки */
            .breadcrumb {
                font-size: 0.85rem;
            }

            /* Кнопки */
            .btn {
                padding: 8px 16px;
                font-size: 0.9rem;
            }

            /* Модальные окна */
            .modal-dialog {
                margin: 10px;
            }
        }

        @media (max-width: 576px) {
            h1 {
                font-size: 1.5rem;
            }

            h2, h3 {
                font-size: 1.25rem;
            }

            .badge {
                font-size: 0.65rem;
                padding: 3px 6px;
            }

            /* Скрываем некоторые тексты на очень маленьких экранах */
            .d-mobile-none {
                display: none;
            }
        }

        /* ========== НАВИГАЦИЯ ========== */
        .navbar-brand {
            font-weight: bold;
            color: #6366f1 !important;
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 0.95rem;
            }

            .navbar-nav {
                margin-top: 10px;
            }

            .nav-link {
                padding: 8px 0 !important;
            }
        }

        /* ========== ГЕРОЙ-СЕКЦИЯ ========== */
        .hero {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            padding: 60px 0;
            margin-bottom: 30px;
        }

        @media (max-width: 768px) {
            .hero {
                padding: 40px 0;
                margin-bottom: 20px;
            }

            .hero h1 {
                font-size: 1.75rem;
            }

            .hero p {
                font-size: 1rem;
            }
        }

        /* ========== КАРТОЧКИ ========== */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        @media (max-width: 768px) {
            .card:hover {
                transform: none;
            }

            /* Уменьшаем внутренние отступы */
            .card-body {
                padding: 1rem;
            }

            .card-header {
                padding: 0.75rem 1rem;
            }
        }

        /* ========== КАРТОЧКИ СТАТИСТИКИ ========== */
        .stats-card {
            text-align: center;
            padding: 20px 15px;
        }

        @media (max-width: 768px) {
            .stats-card {
                padding: 15px 10px;
            }

            .stats-icon {
                font-size: 2rem;
            }

            .stats-card h3 {
                font-size: 1.5rem;
            }
        }

        /* ========== ИЗОБРАЖЕНИЯ ========== */
        .game-card-img {
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        @media (max-width: 768px) {
            .game-card-img {
                height: 180px;
            }
        }

        /* ========== КОММЕНТАРИИ ========== */
        .comment-item {
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .comment-item .flex-shrink-0 {
                margin-right: 12px;
            }

            .comment-item .avatar-md {
                width: 40px;
                height: 40px;
            }

            .comment-item .card-body {
                padding: 12px;
            }

            .reply-form {
                margin-left: 20px !important;
            }

            .replies-list {
                margin-left: 20px !important;
            }
        }

        /* ========== ФИЛЬТРЫ ========== */
        @media (max-width: 768px) {
            .filters-sidebar {
                margin-bottom: 20px;
            }

            .filter-buttons {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }
        }

        /* ========== ТАБЛИЦЫ ========== */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        @media (max-width: 768px) {
            .table td,
            .table th {
                padding: 8px;
                font-size: 0.85rem;
                white-space: nowrap;
            }
        }

        /* ========== ПАГИНАЦИЯ ========== */
        @media (max-width: 768px) {
            .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }

            .page-link {
                padding: 6px 12px;
                font-size: 0.85rem;
            }
        }

        /* ========== ФОРМЫ ========== */
        @media (max-width: 768px) {
            .form-control,
            .form-select {
                font-size: 16px; /* Предотвращает масштабирование на iOS */
            }

            label {
                font-size: 0.9rem;
            }
        }

        /* ========== ПОДВАЛ ========== */
        footer {
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            footer {
                margin-top: 20px;
            }

            footer .col-md-6 {
                text-align: center !important;
            }

            footer h5 {
                font-size: 1.1rem;
            }

            footer p {
                font-size: 0.85rem;
            }
        }

        /* ========== ДРУГИЕ СТИЛИ ========== */
        .card-footer {
            background-color: white;
            border-top: 1px solid #eee;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .container {
            max-width: 1200px;
        }

        .toast-container {
            z-index: 1100;
        }

        /* Улучшенные отступы для сетки */
        .row {
            margin-right: -12px;
            margin-left: -12px;
        }

        .row > [class*="col-"] {
            padding-right: 12px;
            padding-left: 12px;
        }

        /* Стили для аватаров */
        .avatar-sm {
            width: 32px;
            height: 32px;
            object-fit: cover;
        }

        .avatar-md {
            width: 48px;
            height: 48px;
            object-fit: cover;
        }

        .avatar-lg {
            width: 80px;
            height: 80px;
            object-fit: cover;
        }

        /* Ленивая загрузка изображений */
        img[loading="lazy"] {
            background-color: #f0f0f0;
        }

        /* Улучшенный скролл для мобильных */
        .overflow-auto {
            -webkit-overflow-scrolling: touch;
        }
    </style>

    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-gamepad me-2"></i><span class="d-none d-sm-inline">История компьютерных игр</span>
                <span class="d-inline d-sm-none">TimeLapse</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i> Главная
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ Request::is('games*') ? 'active' : '' }}"
                           href="#" id="gamesDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-gamepad me-1"></i> Игры
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('games.index') }}">📚 Все игры</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('games.top') }}">🏆 Топ-100</a></li>
                            <li><a class="dropdown-item" href="{{ route('games.new') }}">🆕 Новинки</a></li>
                            <li><a class="dropdown-item" href="{{ route('games.calendar') }}">📅 Календарь релизов</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('games.random') }}">🎲 Случайная игра</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('timeline') ? 'active' : '' }}" href="{{ route('timeline') }}">
                            <i class="fas fa-timeline me-1"></i> Таймлайн
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('achievements.index') }}">
                            <i class="fas fa-trophy me-1"></i> Достижения
                        </a>
                    </li>
                </ul>

                {{-- БЫСТРЫЙ ПОИСК (адаптирован для мобильных) --}}
                <form action="{{ route('games.index') }}" method="GET" class="d-flex my-2 my-lg-0">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control"
                               placeholder="Поиск игр..." value="{{ request('search') }}"
                               style="min-width: 120px;">
                        <button type="submit" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                {{-- БЛОК АВТОРИЗАЦИИ (адаптирован для мобильных) --}}
                @auth
                    <li class="nav-item dropdown" style="list-style: none;">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            <span class="d-none d-sm-inline">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down fa-xs d-inline d-sm-none"></i>
                            @if(Auth::user()->new_achievements->count() > 0)
                                <span class="badge bg-danger rounded-pill">new</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="fas fa-user me-2"></i> Мой профиль</a></li>
                            <li><a class="dropdown-item" href="{{ route('favorites.index') }}"><i class="fas fa-heart text-danger me-2"></i> Избранное</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.ratings') }}"><i class="fas fa-star text-warning me-2"></i> Мои оценки</a></li>
                            <li><a class="dropdown-item" href="{{ route('friends.index') }}"><i class="fas fa-users me-2"></i> Мои друзья</a></li>
                            <li><a class="dropdown-item" href="{{ route('activity.feed') }}"><i class="fas fa-rss me-2"></i> Лента активности</a></li>
                            <li><a class="dropdown-item" href="{{ route('activity.my') }}"><i class="fas fa-history me-2"></i> Моя активность</a></li>
                            <li><a class="dropdown-item" href="{{ route('achievements.index') }}"><i class="fas fa-trophy text-warning me-2"></i> Достижения</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i> Выйти</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <div class="d-flex flex-column flex-sm-row gap-2 ms-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Вход</a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Регистрация</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    @foreach($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="bg-white border-top py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <h5>TimeLapse Games</h5>
                    <p class="text-muted mb-0">История игровой индустрии в одном месте</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="text-muted mb-0">&copy; {{ date('Y') }} TimeLapse Games. Все права защищены.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    @if(session('new_achievements'))
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            @foreach(session('new_achievements') as $achievement)
                <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="5000">
                    <div class="toast-header bg-success text-white">
                        <i class="fas fa-trophy me-2"></i>
                        <strong class="me-auto">Новое достижение!</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                    </div>
                    <div class="toast-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0"><i class="{{ $achievement->icon }} fa-3x text-warning"></i></div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $achievement->name }}</h6>
                                <small>{{ $achievement->description }}</small>
                                <br><small class="text-success">+{{ $achievement->points }} очков</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toasts = document.querySelectorAll('.toast');
            toasts.forEach(function(toast) {
                setTimeout(function() { toast.classList.remove('show'); }, 5000);
            });

            var alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.classList.remove('show');
                    setTimeout(function() { alert.remove(); }, 150);
                }, 5000);
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
