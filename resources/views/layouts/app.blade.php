<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TimeLapse Games')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome для иконок -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Favicon -->
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
    <!-- Навигация -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-gamepad me-2"></i>TimeLapse Games
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i> Главная
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('games*') ? 'active' : '' }}" href="{{ route('games.index') }}">
                            <i class="fas fa-gamepad me-1"></i> Игры
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('timeline') ? 'active' : '' }}" href="{{ route('timeline') }}">
                            <i class="fas fa-timeline me-1"></i> Таймлайн
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Основное содержимое -->
    <main class="py-4">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <!-- Футер -->
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Ваши скрипты -->
    @stack('scripts')
</body>
</html>
