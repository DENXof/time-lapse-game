{{--ПУБЛИЧНАЯ СТРАНИЦА СО ВСЕМИ ИГРАМИ--}}
@extends('layouts.app')

{{-- Устанавливаем заголовок страницы --}}
@section('title', 'Все игры')

{{-- Начинаем секцию контента --}}
@section('content')

{{-- Контейнер с отступами --}}
<div class="container py-4">

    {{-- ЕСЛИ ИГРЫ ЕСТЬ --}}
    @if($games->count() > 0)
        {{-- Сетка из 3 колонок --}}
        <div class="row">

            {{-- Перебираем все игры --}}
            @foreach($games as $game)
                <div class="col-md-4 mb-4">
                    {{-- Карточка игры с тенью --}}
                    <div class="card h-100 shadow-sm">

                        {{-- ОБЛОЖКА ИГРЫ --}}
                        @if($game->cover_image)
                            {{-- Если есть обложка - показываем её --}}
                            <img src="{{ Storage::url($game->cover_image) }}"
                                 class="card-img-top"
                                 alt="{{ $game->title }}"
                                 style="height: 200px; object-fit: cover;">  {{-- Фиксированная высота --}}
                        @else
                            {{-- Если нет обложки - показываем заглушку --}}
                            <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center"
                                 style="height: 200px;">
                                <i class="fas fa-gamepad fa-3x text-light"></i>  {{-- Иконка контроллера --}}
                            </div>
                        @endif

                        {{-- ТЕЛО КАРТОЧКИ --}}
                        <div class="card-body">
                            {{-- Название игры --}}
                            <h5 class="card-title">{{ $game->title }}</h5>

                            {{-- Описание (до 100 символов) --}}
                            <p class="card-text text-muted">{{ Str::limit($game->description, 100) }}</p>

                            {{-- БЕЙДЖИ (жанр и год) --}}
                            <div class="mb-2">
                                {{-- Жанр (синий) --}}
                                <span class="badge bg-primary">{{ $game->genre->name ?? 'Без жанра' }}</span>
                                {{-- Год (серый) --}}
                                <span class="badge bg-secondary">{{ $game->release_year }}</span>
                            </div>
                        </div>

                        {{-- ПОДВАЛ КАРТОЧКИ (кнопки) --}}
                        <div class="card-footer bg-white d-flex justify-content-between">

                            {{-- Кнопка "Подробнее" для всех --}}
                            <a href="{{ route('games.show', $game->slug) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ПАГИНАЦИЯ (если страниц больше одной) --}}
        @if($games->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $games->links() }}  {{-- Стандартные кнопки пагинации --}}
            </div>
        @endif

    {{-- ЕСЛИ ИГР НЕТ --}}
    @else
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-gamepad fa-3x mb-3"></i>
            <h4>Игр пока нет</h4>
        </div>
    @endif
</div>
@endsection
