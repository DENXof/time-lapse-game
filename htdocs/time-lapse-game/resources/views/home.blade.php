@extends('layouts.app')

@section('title', 'TimeLapse Games - Главная')

@section('content')
    <!-- Герой секция -->
    <section class="hero">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">История видеоигр в движении</h1>
            <p class="lead mb-4">
                Исследуйте эволюцию игровой индустрии через интерактивный таймлайн
            </p>
            <div class="d-flex gap-3 justify-content-center">
                <a href="{{ route('games.index') }}" class="btn btn-light btn-lg px-4">
                    🎮 Смотреть игры
                </a>
                <a href="{{ route('timeline') }}" class="btn btn-outline-light btn-lg px-4">
                    📜 Открыть таймлайн
                </a>
            </div>
        </div>
    </section>

    <!-- Статистика -->
    <section class="container py-5">
        <h2 class="text-center mb-5">Наша платформа</h2>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card stats-card">
                    <div class="stats-icon text-primary">
                        🎮
                    </div>
                    <h3>{{ $stats['games'] ?? 0 }}</h3>
                    <p class="text-muted">Игр в базе</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card stats-card">
                    <div class="stats-icon text-success">
                        🏷️
                    </div>
                    <h3>{{ $stats['genres'] ?? 0 }}</h3>
                    <p class="text-muted">Жанров</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card stats-card">
                    <div class="stats-icon text-warning">
                        ⏳
                    </div>
                    <h3>{{ $stats['eras'] ?? 0 }}</h3>
                    <p class="text-muted">Исторических периодов</p>
                </div>
            </div>
        </div>

        <!-- Информация -->
        <div class="row mt-5">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">О проекте</h4>
                        <p class="card-text">
                            <strong>TimeLapse Games</strong> - это веб-платформа для изучения истории компьютерных игр.
                            Проект позволяет:
                        </p>
                        <ul>
                            <li>Просматривать игры по годам и жанрам</li>
                            <li>Изучать исторические периоды развития игровой индустрии</li>
                            <li>Оценивать игры и оставлять комментарии</li>
                            <li>Использовать интерактивный таймлайн для навигации</li>
                        </ul>
                        <p class="mb-0">
                            <strong>Статус:</strong> {{ $message ?? 'Проект в разработке' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
