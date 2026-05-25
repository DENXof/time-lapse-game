{{--ГЛАВНАЯ СТРАНИЦА АДМИН-ПАНЕЛИ (ДАШБОРД)--}}
@extends('layouts.admin')

@section('title', 'Дашборд - Админ-панель TimeLapse Games')
@section('page-title', 'Дашборд')
@section('page-subtitle', 'Обзор статистики и управление контентом')

@section('content')

{{-- КНОПКА ОЧИСТКИ КЕША --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm bg-light">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-database me-2 text-primary"></i>
                        Управление кешем
                    </h5>
                    <small class="text-muted">Очистка кеша ускорит загрузку сайта при изменениях</small>
                </div>
                <a href="{{ route('admin.clear-cache') }}"
                   class="btn btn-warning"
                   onclick="return confirm('Очистить весь кеш сайта? Это может временно замедлить загрузку страниц.')">
                    <i class="fas fa-trash-alt me-1"></i>Очистить кеш
                </a>
            </div>
        </div>
    </div>
</div>

{{-- СТРОКА СО СТАТИСТИКОЙ (6 карточек) --}}
<div class="row">
    <div class="col-md-4 col-lg-2 mb-3">
        <div class="stat-card games">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">{{ $stats['total_games'] }}</h2>
                    <p class="text-muted mb-0">Всего игр</p>
                </div>
                <i class="fas fa-gamepad stat-icon text-primary"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-lg-2 mb-3">
        <div class="stat-card genres">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">{{ $stats['total_genres'] }}</h2>
                    <p class="text-muted mb-0">Жанров</p>
                </div>
                <i class="fas fa-tags stat-icon text-success"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-lg-2 mb-3">
        <div class="stat-card views">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">{{ number_format($stats['total_views']) }}</h2>
                    <p class="text-muted mb-0">Просмотров</p>
                </div>
                <i class="fas fa-eye stat-icon text-danger"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-lg-2 mb-3">
        <div class="stat-card users">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">{{ $stats['total_users'] }}</h2>
                    <p class="text-muted mb-0">Пользователей</p>
                    <small>+{{ $stats['new_users_today'] }} сегодня</small>
                </div>
                <i class="fas fa-users stat-icon text-info"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-lg-2 mb-3">
        <div class="stat-card comments">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">{{ $stats['total_comments'] }}</h2>
                    <p class="text-muted mb-0">Комментариев</p>
                    <small>+{{ $stats['new_comments_today'] }} сегодня</small>
                </div>
                <i class="fas fa-comments stat-icon text-warning"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-lg-2 mb-3">
        <div class="stat-card ratings">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">{{ $stats['total_ratings'] }}</h2>
                    <p class="text-muted mb-0">Оценок</p>
                </div>
                <i class="fas fa-star stat-icon text-warning"></i>
            </div>
        </div>
    </div>
</div>

{{-- ГРАФИКИ АКТИВНОСТИ --}}
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2 text-primary"></i>Регистрации (последние 7 дней)</h5>
            </div>
            <div class="card-body">
                <canvas id="registrationsChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2 text-success"></i>Комментарии (последние 7 дней)</h5>
            </div>
            <div class="card-body">
                <canvas id="commentsChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- ПОСЛЕДНИЕ ИГРЫ И ПОПУЛЯРНЫЕ --}}
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-clock me-2 text-info"></i>Последние добавленные игры</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @foreach($recentGames as $game)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $game->title }}</strong>
                                <br>
                                <small class="text-muted">{{ $game->genre->name ?? 'Без жанра' }} • {{ $game->release_year }}</small>
                            </div>
                            <a href="{{ route('admin.games.edit', $game->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-fire me-2 text-danger"></i>Популярные игры</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @foreach($popularGames as $game)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $game->title }}</strong>
                                <br>
                                <small class="text-muted">{{ number_format($game->views_count) }} просмотров</small>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $game->views_count }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- ТОП РЕЙТИНГ И БЫСТРЫЕ ДЕЙСТВИЯ --}}
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-star me-2 text-warning"></i>Топ рейтинг</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @foreach($topRatedGames as $game)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $game->title }}</strong>
                                <br>
                                <small class="text-muted">{{ $game->rating_count }} оценок</small>
                            </div>
                            <span class="badge bg-warning rounded-pill">{{ number_format($game->rating_avg, 1) }} ⭐</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-bolt me-2 text-primary"></i>Быстрые действия</h5>
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
                        <a href="{{ route('admin.users.index') }}" class="btn btn-info w-100 py-3">
                            <i class="fas fa-users fa-2x mb-2 d-block"></i>
                            Пользователи
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.comments.index') }}" class="btn btn-warning w-100 py-3">
                            <i class="fas fa-comments fa-2x mb-2 d-block"></i>
                            Комментарии
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.logs.index') }}" class="btn btn-secondary w-100 py-3">
                            <i class="fas fa-history fa-2x mb-2 d-block"></i>
                            Логи
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-success w-100 py-3">
                            <i class="fas fa-cog fa-2x mb-2 d-block"></i>
                            Настройки
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.clear-cache') }}" class="btn btn-warning w-100 py-3"
                           onclick="return confirm('Очистить весь кеш сайта?')">
                            <i class="fas fa-trash-alt fa-2x mb-2 d-block"></i>
                            Очистить кеш
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ПОСЛЕДНИЕ ДЕЙСТВИЯ АДМИНОВ --}}
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-history me-2 text-secondary"></i>Последние действия администраторов</h5>
            </div>
            <div class="card-body p-0">
                @if($recentLogs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Администратор</th>
                                    <th>Действие</th>
                                    <th>Объект</th>
                                    <th>Время</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentLogs as $log)
                                    <tr>
                                        <td>{{ $log->admin->name ?? 'Неизвестно' }}</td>
                                        <td>
                                            @if($log->action === 'create')
                                                <span class="badge bg-success">Создание</span>
                                            @elseif($log->action === 'update')
                                                <span class="badge bg-info">Обновление</span>
                                            @elseif($log->action === 'delete')
                                                <span class="badge bg-danger">Удаление</span>
                                            @elseif($log->action === 'ban_user')
                                                <span class="badge bg-warning">Бан</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $log->action }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $log->target_type }} #{{ $log->target_id }}</td>
                                        <td>{{ $log->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-4 mb-0">Нет записей</p>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
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
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // График регистраций
    const registrationsCtx = document.getElementById('registrationsChart').getContext('2d');
    new Chart(registrationsCtx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Новые пользователи',
                data: @json($chartRegistrations),
                borderColor: '#3498db',
                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true
        }
    });

    // График комментариев
    const commentsCtx = document.getElementById('commentsChart').getContext('2d');
    new Chart(commentsCtx, {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Комментарии',
                data: @json($chartComments),
                backgroundColor: '#2ecc71',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true
        }
    });
</script>
@endpush
