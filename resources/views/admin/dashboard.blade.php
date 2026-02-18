{{--ГЛАВНАЯ СТРАНИЦА АДМИН-ПАНЕЛИ (ДАШБОРД)--}}
@extends('layouts.admin')

@section('title', 'Дашборд - Админ-панель TimeLapse Games')
@section('page-title', 'Дашборд')
@section('page-subtitle', 'Обзор статистики и управление контентом')

{{-- Начинаем секцию контента --}}
@section('content')

{{-- СТРОКА СО СТАТИСТИКОЙ (4 карточки) --}}
<div class="row">

    {{-- КАРТОЧКА 1: Количество игр --}}
    <div class="col-md-3 col-lg-3">
        <div class="stat-card games position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    {{-- Большая цифра --}}
                    <h2 class="mb-0">{{ $stats['total_games'] }}</h2>
                    <p class="text-muted mb-0">Всего игр</p>
                </div>
                {{-- Иконка игр (синяя) --}}
                <i class="fas fa-gamepad stat-icon text-primary"></i>
            </div>
        </div>
    </div>

    {{-- КАРТОЧКА 2: Количество жанров --}}
    <div class="col-md-3 col-lg-3">
        <div class="stat-card genres position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">{{ $stats['total_genres'] }}</h2>
                    <p class="text-muted mb-0">Жанров</p>
                </div>
                {{-- Иконка тегов (зеленая) --}}
                <i class="fas fa-tags stat-icon text-success"></i>
            </div>
        </div>
    </div>

    {{-- КАРТОЧКА 3: Количество просмотров --}}
    <div class="col-md-3 col-lg-3">
        <div class="stat-card views position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    {{-- Форматируем число с разделителями (1000 -> 1,000) --}}
                    <h2 class="mb-0">{{ number_format($stats['total_views']) }}</h2>
                    <p class="text-muted mb-0">Просмотров</p>
                </div>
                {{-- Иконка глаза (красная) --}}
                <i class="fas fa-eye stat-icon text-danger"></i>
            </div>
        </div>
    </div>
</div>

{{-- ВТОРАЯ СТРОКА: Быстрые действия и последние игры --}}
<div class="row mt-4">

    {{-- ЛЕВАЯ КОЛОНКА (8 колонок) --}}
    <div class="col-md-8">

        {{-- КАРТОЧКА: БЫСТРЫЕ ДЕЙСТВИЯ --}}
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>  {{-- Иконка молнии --}}
                    Быстрые действия
                </h5>
            </div>
            <div class="card-body">
                {{-- Сетка 2x2 для кнопок --}}
                <div class="row g-3">

                    {{-- КНОПКА: Добавить игру --}}
                    <div class="col-md-6">
                        <a href="{{ route('admin.games.create') }}" class="btn btn-primary w-100 py-3">
                            <i class="fas fa-plus-circle fa-2x mb-2 d-block"></i>  {{-- Большая иконка --}}
                            Добавить игру
                        </a>
                    </div>

                    {{-- КНОПКА: Все игры --}}
                    <div class="col-md-6">
                        <a href="{{ route('games.index') }}" class="btn btn-success w-100 py-3">
                            <i class="fas fa-list fa-2x mb-2 d-block"></i>  {{-- Иконка списка --}}
                            Все игры
                        </a>
                    </div>

                    {{-- КНОПКА: Таймлайн --}}
                    <div class="col-md-6">
                        <a href="{{ route('timeline') }}" class="btn btn-info w-100 py-3">
                            <i class="fas fa-timeline fa-2x mb-2 d-block"></i>  {{-- Иконка таймлайна --}}
                            Таймлайн
                        </a>
                    </div>

                    {{-- КНОПКА: На сайт --}}
                    <div class="col-md-6">
                        <a href="{{ route('home') }}" class="btn btn-outline-primary w-100 py-3">
                            <i class="fas fa-external-link-alt fa-2x mb-2 d-block"></i>  {{-- Иконка внешней ссылки --}}
                            На сайт
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
