{{--ОСНОВНОЙ ШАБЛОН ДЛЯ ПУБЛИЧНОЙ ЧАСТИ САЙТА--}}

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TimeLapse Games')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ time() }}">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 60px;
            background-color: #f8f9fa;
        }

        .navbar-brand {
            font-weight: bold;
            color: #6366f1 !important;
        }

        .hero {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            padding: 80px 0;
            margin-bottom: 40px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .stats-card {
            text-align: center;
            padding: 30px 20px;
        }

        .stats-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .game-card-img {
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .card-footer {
            background-color: white;
            border-top: 1px solid #eee;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .badge {
            font-size: 0.8em;
            padding: 5px 10px;
        }

        .container {
            max-width: 1200px;
        }

        h1 {
            color: #333;
            margin-bottom: 30px;
        }
    </style>

    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-gamepad me-2"></i>История компьютерных игр
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

                    <!-- ========= ОБНОВЛЁННЫЙ ПУНКТ "ИГРЫ" С ВЫПАДАЮЩИМ МЕНЮ ========= -->
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
                    <!-- =========================================================== -->

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('timeline') ? 'active' : '' }}" href="{{ route('timeline') }}">
                            <i class="fas fa-timeline me-1"></i> Таймлайн
                        </a>
                    </li>
                </ul>

                <!-- БЫСТРЫЙ ПОИСК В ШАПКЕ -->
                <form action="{{ route('games.index') }}" method="GET" class="d-flex me-3">
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Поиск игр..." value="{{ request('search') }}"
                           style="width: 200px;">
                    <button type="submit" class="btn btn-sm btn-outline-primary ms-2">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                <!-- БЛОК АВТОРИЗАЦИИ -->
                @auth
                    <li class="nav-item dropdown" style="list-style: none;">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.index') }}">
                                    <i class="fas fa-user me-2"></i> Мой профиль
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('favorites.index') }}">
                                    <i class="fas fa-heart text-danger me-2"></i> Избранное
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.ratings') }}">
                                    <i class="fas fa-star text-warning me-2"></i> Мои оценки
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i> Выйти
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm me-2">Вход</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Регистрация</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <footer class="bg-white border-top py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>TimeLapse Games</h5>
                    <p class="text-muted">История игровой индустрии в одном месте</p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="text-muted mb-0">
                        &copy; {{ date('Y') }} TimeLapse Games. Все права защищены.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
