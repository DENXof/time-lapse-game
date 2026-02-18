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
            <li class="breadcrumb-item active">{{ $game->title }}</li>  {{-- Текущая страница --}}
        </ol>
    </nav>

    {{-- ДВЕ КОЛОНКИ: основная информация (8) и похожие игры (4) --}}
    <div class="row">

        {{-- ЛЕВАЯ КОЛОНКА (ОСНОВНАЯ ИНФОРМАЦИЯ) --}}
        <div class="col-lg-8">

            {{-- КАРТОЧКА С ОСНОВНОЙ ИНФОРМАЦИЕЙ --}}
            <div class="card shadow-sm mb-4">
                <div class="row g-0">  {{-- Без отступов между колонками --}}

                    {{-- КОЛОНКА С ОБЛОЖКОЙ (4 колонки) --}}
                    <div class="col-md-4">
                        @if($game->cover_image)
                            {{-- Если есть обложка - показываем её --}}
                            <img src="{{ Storage::url($game->cover_image) }}"
                                 class="img-fluid rounded-start"
                                 alt="{{ $game->title }}"
                                 style="height: 100%; object-fit: cover;">  {{-- Растягиваем по высоте --}}
                        @else
                            {{-- Если нет обложки - заглушка --}}
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

                            {{-- БЕЙДЖИ (жанр, год, просмотры) --}}
                            <div class="mb-3">
                                {{-- Жанр (синий) --}}
                                <span class="badge bg-primary fs-6">{{ $game->genre->name ?? 'Без жанра' }}</span>
                                {{-- Год (серый) --}}
                                <span class="badge bg-secondary fs-6">{{ $game->release_year }} год</span>
                                {{-- Просмотры (голубой) --}}
                                <span class="badge bg-info fs-6">
                                    <i class="fas fa-eye"></i> {{ $game->views_count ?? 0 }} просмотров
                                </span>
                            </div>

                            {{-- ДЕТАЛЬНАЯ ИНФОРМАЦИЯ --}}
                            <div class="mb-4">
                                {{-- Разработчик --}}
                                <h5>Разработчик</h5>
                                <p class="fs-5">{{ $game->developer }}</p>

                                {{-- Издатель (если есть) --}}
                                @if($game->publisher)
                                <h5>Издатель</h5>
                                <p class="fs-5">{{ $game->publisher }}</p>
                                @endif

                                {{-- Платформа --}}
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
                    {{-- Полное описание игры --}}
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

                    {{-- Если есть похожие игры --}}
                    @if($relatedGames->count() > 0)
                        @foreach($relatedGames as $relatedGame)
                            {{-- Карточка похожей игры --}}
                            <div class="card mb-3">
                                <div class="row g-0">

                                    {{-- Мини-обложка (4 колонки) --}}
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

                                    {{-- Информация о похожей игре (8 колонок) --}}
                                    <div class="col-8">
                                        <div class="card-body p-2">
                                            {{-- Название (обрезанное до 20 символов) --}}
                                            <h6 class="card-title mb-1">{{ Str::limit($relatedGame->title, 20) }}</h6>
                                            {{-- Год --}}
                                            <p class="card-text text-muted mb-1 small">{{ $relatedGame->release_year }}</p>
                                            {{-- Кнопка "Смотреть" --}}
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
                        {{-- Если похожих игр нет --}}
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
                    {{-- Список без маркеров --}}
                    <ul class="list-unstyled">
                        {{-- Год выхода --}}
                        <li class="mb-2">
                            <i class="fas fa-calendar text-primary me-2"></i>
                            <strong>Год выхода:</strong> {{ $game->release_year }}
                        </li>
                        {{-- Жанр --}}
                        <li class="mb-2">
                            <i class="fas fa-tag text-primary me-2"></i>
                            <strong>Жанр:</strong> {{ $game->genre->name ?? 'Не указан' }}
                        </li>
                        {{-- Просмотры --}}
                        <li class="mb-2">
                            <i class="fas fa-eye text-primary me-2"></i>
                            <strong>Просмотров:</strong> {{ $game->views_count ?? 0 }}
                        </li>
                        {{-- Платформа --}}
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
