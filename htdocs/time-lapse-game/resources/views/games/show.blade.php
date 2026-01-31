@extends('layouts.app')

@section('title', $game->title . ' - TimeLapse Games')

@section('content')
<div class="container py-4">
    <!-- Хлебные крошки -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('games.index') }}">Игры</a></li>
            <li class="breadcrumb-item active">{{ $game->title }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Левая колонка: Информация об игре -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="row g-0">
                    <div class="col-md-4">
                        @if($game->cover_image)
                            <img src="{{ Storage::url($game->cover_image) }}"
                                 class="img-fluid rounded-start"
                                 alt="{{ $game->title }}"
                                 style="height: 100%; object-fit: cover;">
                        @else
                            <div class="bg-secondary d-flex align-items-center justify-content-center"
                                 style="height: 300px;">
                                <i class="fas fa-gamepad fa-5x text-light"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h1 class="card-title">{{ $game->title }}</h1>
                            <div class="mb-3">
                                <span class="badge bg-primary fs-6">{{ $game->genre->name ?? 'Без жанра' }}</span>
                                <span class="badge bg-secondary fs-6">{{ $game->release_year }} год</span>
                                <span class="badge bg-info fs-6">
                                    <i class="fas fa-eye"></i> {{ $game->views_count ?? 0 }} просмотров
                                </span>
                            </div>

                            <div class="mb-4">
                                <h5>Разработчик</h5>
                                <p class="fs-5">{{ $game->developer }}</p>

                                @if($game->publisher)
                                <h5>Издатель</h5>
                                <p class="fs-5">{{ $game->publisher }}</p>
                                @endif

                                <h5>Платформа</h5>
                                <p class="fs-5">{{ $game->platform }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Описание игры -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h3 class="mb-0">Описание</h3>
                </div>
                <div class="card-body">
                    <p class="card-text fs-5">{{ $game->description }}</p>
                </div>
            </div>
        </div>

        <!-- Правая колонка: Похожие игры -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h4 class="mb-0">Похожие игры</h4>
                </div>
                <div class="card-body">
                    @if($relatedGames->count() > 0)
                        @foreach($relatedGames as $relatedGame)
                            <div class="card mb-3">
                                <div class="row g-0">
                                    <div class="col-4">
                                        @if($relatedGame->cover_image)
                                            <img src="{{ Storage::url($relatedGame->cover_image) }}"
                                                 class="img-fluid rounded-start"
                                                 alt="{{ $relatedGame->title }}"
                                                 style="height: 80px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary d-flex align-items-center justify-content-center"
                                                 style="height: 80px;">
                                                <i class="fas fa-gamepad text-light"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-8">
                                        <div class="card-body p-2">
                                            <h6 class="card-title mb-1">{{ Str::limit($relatedGame->title, 20) }}</h6>
                                            <p class="card-text text-muted mb-1 small">{{ $relatedGame->release_year }}</p>
                                            <a href="{{ route('games.show', $relatedGame->slug) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                Смотреть
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Нет похожих игр</p>
                    @endif
                </div>
            </div>

            <!-- Статистика -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h4 class="mb-0">Информация</h4>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-calendar text-primary me-2"></i>
                            <strong>Год выхода:</strong> {{ $game->release_year }}
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-tag text-primary me-2"></i>
                            <strong>Жанр:</strong> {{ $game->genre->name ?? 'Не указан' }}
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-eye text-primary me-2"></i>
                            <strong>Просмотров:</strong> {{ $game->views_count ?? 0 }}
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-gamepad text-primary me-2"></i>
                            <strong>Платформа:</strong> {{ $game->platform }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Кнопка назад -->
    <div class="mt-4">
        <a href="{{ route('games.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Вернуться к списку игр
        </a>
    </div>
</div>
@endsection
