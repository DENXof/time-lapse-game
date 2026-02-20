{{--ОСНОВНОЙ ШАБЛОН ДЛЯ ПУБЛИЧНОЙ ЧАСТИ САЙТА
    Файл: resources/views/layouts/app.blade.php
    Здесь задается общая структура всех страниц, которые видят посетители:
    - шапка с меню
    - место для контента
    - подвал
--}}

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    {{-- Обеспечивает правильное отображение на мобильных устройствах --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{--
        Заголовок страницы.
        @yield('title', 'TimeLapse Games') - если дочерний шаблон не указал свой title,
        то будет "TimeLapse Games" по умолчанию
    --}}
    <title>@yield('title', 'TimeLapse Games')</title>

    {{-- Bootstrap 5 - библиотека для красивых компонентов (кнопки, карточки, сетка) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome - библиотека иконок (контроллеры, домики, глазки и т.д.) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Иконка сайта (favicon) - маленькая картинка во вкладке браузера --}}
    {{-- ?v={{ time() }} - добавляет версию, чтобы браузер не кешировал старую иконку --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ time() }}">

    {{--
        ========================================
        СТИЛИ ДЛЯ ПУБЛИЧНОЙ ЧАСТИ
        ========================================
    --}}
    <style>
        /* Основные настройки страницы */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;  /* Красивый шрифт */
            padding-top: 60px;  /* Отступ сверху, чтобы контент не прятался под шапку */
            background-color: #f8f9fa;  /* Светло-серый фон */
        }

        /*(логотип)*/
        .navbar-brand {
            font-weight: bold;  /* Жирный шрифт */
            color: #6366f1 !important;  /* Фиолетовый цвет (с приоритетом) */
        }

        /*
            ГЕРОЙ-СЕКЦИЯ (большой баннер на главной)
        */
        .hero {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);  /* Фиолетовый градиент */
            color: white;
            padding: 80px 0;  /* Внутренние отступы сверху/снизу */
            margin-bottom: 40px;  /* Отступ снизу */
        }

        /*
            КАРТОЧКИ (для игр, статистики и т.д.)
        */
        .card {
            border: none;  /* Убираем рамку */
            border-radius: 10px;  /* Скругленные углы */
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);  /* Легкая тень */
            transition: transform 0.3s;  /* Плавная анимация при наведении */
        }

        /* Эффект при наведении на карточку */
        .card:hover {
            transform: translateY(-5px);  /* Карточка подпрыгивает вверх */
        }

        /*
            КАРТОЧКИ СТАТИСТИКИ (на главной)
        */
        .stats-card {
            text-align: center;  /* Текст по центру */
            padding: 30px 20px;  /* Внутренние отступы */
        }

        /*
            ИКОНКИ В КАРТОЧКАХ СТАТИСТИКИ
        */
        .stats-icon {
            font-size: 3rem;  /* Большой размер */
            margin-bottom: 15px;  /* Отступ снизу */
        }

        /*
            КАРТИНКИ ИГР В КАРТОЧКАХ
        */
        .game-card-img {
            height: 200px;  /* Фиксированная высота */
            object-fit: cover;  /* Картинка обрезается, но не искажается */
            border-top-left-radius: 10px;  /* Скругление верхних углов */
            border-top-right-radius: 10px;
        }

        /*
            ПОДВАЛ КАРТОЧКИ
        */
        .card-footer {
            background-color: white;  /* Белый фон */
            border-top: 1px solid #eee;  /* Тонкая серая линия сверху */
            border-bottom-left-radius: 10px;  /* Скругление нижних углов */
            border-bottom-right-radius: 10px;
        }

        /*
            БЕЙДЖИ (жанр, год и т.д.)
        */
        .badge {
            font-size: 0.8em;  /* Немного меньше обычного текста */
            padding: 5px 10px;  /* Внутренние отступы */
        }

        /*
            КОНТЕЙНЕР
        */
        .container {
            max-width: 1200px;  /* Максимальная ширина для больших экранов */
        }

        /*
            ЗАГОЛОВКИ H1
        */
        h1 {
            color: #333;  /* Темно-серый */
            margin-bottom: 30px;  /* Отступ снизу */
        }
    </style>

    {{-- Место для дополнительных стилей из дочерних страниц --}}
    @stack('styles')
</head>
<body>
    {{--
        ========================================
        ШАПКА (НАВИГАЦИЯ)
        ========================================
        fixed-top - приклеена к верху
        shadow-sm - легкая тень
    --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container">

            {{-- Логотип (слева) --}}
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-gamepad me-2"></i>История компьюетрных игр  {{-- Иконка контроллера + название --}}
            </a>

            {{--
                Кнопка для мобильного меню
                Появляется на маленьких экранах
            --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>  {{-- Три полоски --}}
            </button>

            {{--
                МЕНЮ (справа)
                collapse - скрыто на мобильных, пока не нажмут бургер
            --}}
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">  {{-- ms-auto - прижимаем вправо --}}

                    {{-- Пункт "Главная" --}}
                    <li class="nav-item">
                        {{--
                            Request::is('/') - если мы на главной, добавляем класс active
                            active - подсвечивает активный пункт меню
                        --}}
                        <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i> Главная
                        </a>
                    </li>

                    {{-- Пункт "Игры" --}}
                    <li class="nav-item">
                        {{-- Request::is('games*') - если URL начинается с /games --}}
                        <a class="nav-link {{ Request::is('games*') ? 'active' : '' }}" href="{{ route('games.index') }}">
                            <i class="fas fa-gamepad me-1"></i> Игры
                        </a>
                    </li>

                    {{-- Пункт "Таймлайн" --}}
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('timeline') ? 'active' : '' }}" href="{{ route('timeline') }}">
                            <i class="fas fa-timeline me-1"></i> Таймлайн
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{--
        ========================================
        ОСНОВНОЕ СОДЕРЖИМОЕ
        ========================================
        Сюда будет вставляться контент из дочерних страниц
    --}}
    <main class="py-4">
        <div class="container">
            @yield('content')
        </div>
    </main>

    {{--
        ========================================
        ПОДВАЛ (ФУТЕР)
        ========================================
    --}}
    <footer class="bg-white border-top py-4 mt-5">
        <div class="container">
            <div class="row">
                {{-- Левая часть подвала --}}
                <div class="col-md-6">
                    <h5>TimeLapse Games</h5>
                    <p class="text-muted">История игровой индустрии в одном месте</p>
                </div>

                {{-- Правая часть подвала --}}
                <div class="col-md-6 text-end">
                    <p class="text-muted mb-0">
                        {{-- Знак копирайта + текущий год --}}
                        &copy; {{ date('Y') }} TimeLapse Games. Все права защищены.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    {{-- Bootstrap JS - для работы модальных окон, выпадашек и т.д. --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Место для дополнительных скриптов из дочерних страниц --}}
    @stack('scripts')
</body>
</html>
