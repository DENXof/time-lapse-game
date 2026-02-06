@extends('layouts.admin')

@section('title', 'Дашборд - Админ-панель TimeLapse Games')
@section('page-title', 'Дашборд')
@section('page-subtitle', 'Обзор статистики и управление контентом')

@section('content')
<div class="row">
    <!-- Статистика -->
    <div class="col-md-3 col-lg-3">
        <div class="stat-card games position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">{{ $stats['total_games'] }}</h2>
                    <p class="text-muted mb-0">Всего игр</p>
                </div>
                <i class="fas fa-gamepad stat-icon text-primary"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-lg-3">
        <div class="stat-card genres position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">{{ $stats['total_genres'] }}</h2>
                    <p class="text-muted mb-0">Жанров</p>
                </div>
                <i class="fas fa-tags stat-icon text-success"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-lg-3">
        <div class="stat-card views position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">{{ number_format($stats['total_views']) }}</h2>
                    <p class="text-muted mb-0">Просмотров</p>
                </div>
                <i class="fas fa-eye stat-icon text-danger"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-lg-3">
        <div class="stat-card recent position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">{{ $stats['recent_games']->count() }}</h2>
                    <p class="text-muted mb-0">Новых игр</p>
                </div>
                <i class="fas fa-clock stat-icon text-warning"></i>
            </div>
        </div>
    </div>
</div>

<!-- Быстрые действия -->
<div class="row mt-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>Быстрые действия
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="{{ route('admin.games.create') }}" class="btn btn-primary w-100 py-3">
                            <i class="fas fa-plus-circle fa-2x mb-2 d-block"></i>
                            Добавить игру
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('games.index') }}" class="btn btn-success w-100 py-3">
                            <i class="fas fa-list fa-2x mb-2 d-block"></i>
                            Все игры
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('timeline') }}" class="btn btn-info w-100 py-3">
                            <i class="fas fa-timeline fa-2x mb-2 d-block"></i>
                            Таймлайн
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('home') }}" class="btn btn-outline-primary w-100 py-3">
                            <i class="fas fa-external-link-alt fa-2x mb-2 d-block"></i>
                            На сайт
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Последние добавленные игры -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>Последние добавленные игры
                </h5>
            </div>
            <div class="card-body">
                @if($stats['recent_games']->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Название</th>
                                    <th>Жанр</th>
                                    <th>Год</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['recent_games'] as $game)
                                <tr>
                                    <td>{{ $game->title }}</td>
                                    <td>
                                        @if($game->genre)
                                            <span class="badge bg-primary">{{ $game->genre->name }}</span>
                                        @else
                                            <span class="text-muted">Без жанра</span>
                                        @endif
                                    </td>
                                    <td>{{ $game->release_year }}</td>
                                    <td>
                                        <a href="{{ route('admin.games.edit', $game->id) }}"
                                           class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('games.show', $game->slug) }}"
                                           class="btn btn-sm btn-outline-info ms-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3">Игр пока нет</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Правая колонка -->
    <div class="col-md-4">
        <!-- Статистика по жанрам -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Статистика по жанрам
                </h5>
            </div>
            <div class="card-body">
                @if($stats['popular_genres']->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($stats['popular_genres'] as $genre)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $genre->name }}
                            <span class="badge bg-primary rounded-pill">{{ $genre->games_count }}</span>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted text-center">Жанров пока нет</p>
                @endif
            </div>
        </div>

        <!-- Информация о системе -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Информация о системе
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-user text-primary me-2"></i>
                        <strong>Администратор:</strong> {{ auth()->guard('admin')->user()->name }}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-calendar text-primary me-2"></i>
                        <strong>Дата:</strong> {{ now()->format('d.m.Y') }}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-clock text-primary me-2"></i>
                        <strong>Время:</strong> {{ now()->format('H:i') }}
                    </li>
                    <li>
                        <i class="fas fa-database text-primary me-2"></i>
                        <strong>База данных:</strong> {{ $stats['total_games'] + $stats['total_genres'] }} записей
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
