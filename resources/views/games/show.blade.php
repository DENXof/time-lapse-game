{{--ДЕТАЛЬНАЯ СТРАНИЦА ИГРЫ--}}
@extends('layouts.app')

@section('title', $game->title . ' - TimeLapse Games')

{{-- Начинаем секцию контента --}}
@section('content')

{{-- Контейнер с отступами --}}
<div class="container py-4">

    {{-- ХЛЕБНЫЕ КРОШКИ (навигация) --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('games.index') }}">Игры</a></li>
            <li class="breadcrumb-item active">{{ $game->title }}</li>
        </ol>
    </nav>

    {{-- ДВЕ КОЛОНКИ: основная информация (8) и похожие игры (4) --}}
    <div class="row">

        {{-- ЛЕВАЯ КОЛОНКА (ОСНОВНАЯ ИНФОРМАЦИЯ) --}}
        <div class="col-lg-8">

            {{-- КАРТОЧКА С ОСНОВНОЙ ИНФОРМАЦИЕЙ --}}
            <div class="card shadow-sm mb-4">
                <div class="row g-0">

                    {{-- КОЛОНКА С ОБЛОЖКОЙ (4 колонки) --}}
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

                    {{-- КОЛОНКА С ТЕКСТОМ (8 колонок) --}}
                    <div class="col-md-8">
                        <div class="card-body">
                            {{-- Название игры --}}
                            <h1 class="card-title">{{ $game->title }}</h1>

                            {{-- БЕЙДЖИ (жанр, год, просмотры, рейтинг) --}}
                            <div class="mb-3">
                                <span class="badge bg-primary fs-6">{{ $game->genre->name ?? 'Без жанра' }}</span>
                                <span class="badge bg-secondary fs-6">{{ $game->release_year }} год</span>
                                <span class="badge bg-info fs-6">
                                    <i class="fas fa-eye"></i> {{ $game->views_count ?? 0 }} просмотров
                                </span>
                                @if($game->rating_avg > 0)
                                    <span class="badge bg-warning text-dark fs-6">
                                        <i class="fas fa-star"></i> {{ number_format($game->rating_avg, 1) }} ({{ $game->rating_count }})
                                    </span>
                                @endif
                            </div>

                            {{-- КНОПКА "В ИЗБРАННОЕ" (только для авторизованных) --}}
                            @auth
                                <form action="{{ route('favorites.toggle', $game) }}" method="POST" class="d-inline-block mb-3">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="fas fa-heart {{ Auth::user()->hasFavorited($game->id) ? 'text-danger' : '' }}"></i>
                                        {{ Auth::user()->hasFavorited($game->id) ? 'В избранном' : 'В избранное' }}
                                    </button>
                                </form>
                            @endauth

                            {{-- БЛОК ОЦЕНКИ (только для авторизованных) --}}
                            @auth
                                <div class="mt-3 mb-3 p-3 bg-light rounded">
                                    <p class="mb-2"><strong>Оценить игру:</strong></p>
                                    <form action="{{ route('ratings.store', $game) }}" method="POST" id="rating-form">
                                        @csrf
                                        <div class="rating-stars d-flex gap-2 mb-2">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <label class="cursor-pointer">
                                                    <input type="radio" name="value" value="{{ $i }}"
                                                           style="display: none;"
                                                           onchange="document.getElementById('rating-form').submit()"
                                                           {{ optional($game->userRating(Auth::user()))->value == $i ? 'checked' : '' }}>
                                                    <i class="fas fa-star fs-3 {{ optional($game->userRating(Auth::user()))->value >= $i ? 'text-warning' : 'text-secondary' }}"></i>
                                                </label>
                                            @endfor
                                        </div>
                                    </form>
                                    <small class="text-muted">Нажмите на звезду, чтобы оценить</small>
                                </div>
                            @endauth

                            {{-- ДЕТАЛЬНАЯ ИНФОРМАЦИЯ --}}
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

            {{-- КАРТОЧКА С ОПИСАНИЕМ --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h3 class="mb-0">Описание</h3>
                </div>
                <div class="card-body">
                    <p class="card-text fs-5">{{ $game->description }}</p>
                </div>
            </div>
        </div>

        {{-- ПРАВАЯ КОЛОНКА (ПОХОЖИЕ ИГРЫ И СТАТИСТИКА) --}}
        <div class="col-lg-4">

            {{-- КАРТОЧКА: ПОХОЖИЕ ИГРЫ --}}
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

            {{-- КАРТОЧКА: СТАТИСТИКА --}}
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

    {{-- КНОПКА НАЗАД --}}
    <div class="mt-4">
        <a href="{{ route('games.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Вернуться к списку игр
        </a>
    </div>
</div>
@endsection
