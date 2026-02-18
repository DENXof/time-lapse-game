{{--ГЛАВНАЯ СТРАНИЦА САЙТА--}}
@extends('layouts.app')

{{-- Устанавливаем заголовок страницы --}}
@section('title', 'Главная - TimeLapse Games')

{{-- Начинаем секцию контента --}}
@section('content')

{{--
    БЛОК 1: ГЕРОЙ (HERO) - большая красивая шапка
    С градиентным фоном и призывом к действию
--}}
<div class="hero">
    <div class="container text-center">
        {{-- Главный заголовок сайта --}}
        <h1 class="display-4 fw-bold">TimeLapse Games</h1>

        {{-- Подзаголовок --}}
        <p class="lead">История игровой индустрии в одном месте</p>

        {{-- Большая кнопка "Смотреть игры" --}}
        <a href="{{ route('games.index') }}" class="btn btn-light btn-lg mt-3">
            <i class="fas fa-gamepad me-2"></i>Смотреть игры
        </a>
    </div>
</div>

{{--
    БЛОК 2: ТРИ КАРТОЧКИ С ОПИСАНИЕМ РАЗДЕЛОВ
    Показывают посетителю, что есть на сайте
--}}
<div class="container py-5">
    <div class="row">

        {{-- КАРТОЧКА 1: ИГРЫ --}}
        <div class="col-md-4 mb-4">
            <div class="card stats-card">
                {{-- Иконка с игровым контроллером (синяя) --}}
                <div class="stats-icon text-primary">
                    <i class="fas fa-gamepad"></i>
                </div>
                <h3>Игры</h3>
                <p class="text-muted">Вся история игровой индустрии</p>
            </div>
        </div>

        {{-- КАРТОЧКА 2: ТАЙМЛАЙН --}}
        <div class="col-md-4 mb-4">
            <div class="card stats-card">
                {{-- Иконка календаря (зеленая) --}}
                <div class="stats-icon text-success">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3>Таймлайн</h3>
                <p class="text-muted">Хронология развития игр</p>
            </div>
        </div>

        {{-- КАРТОЧКА 3: ЖАНРЫ --}}
        <div class="col-md-4 mb-4">
            <div class="card stats-card">
                {{-- Иконка тегов (желтая) --}}
                <div class="stats-icon text-warning">
                    <i class="fas fa-tags"></i>
                </div>
                <h3>Жанры</h3>
                <p class="text-muted">От RPG до шутеров</p>
            </div>
        </div>
    </div>

    {{--
        БЛОК 3: ПРИЗЫВ К ДЕЙСТВИЮ
        Две большие кнопки для перехода в основные разделы
    --}}
    <div class="text-center mt-5">
        {{-- Заголовок --}}
        <h2>Начните исследовать</h2>

        {{-- Описание --}}
        <p class="lead mb-4">Откройте для себя историю видеоигр через годы и жанры</p>

        {{-- Кнопка "Перейти к играм" (синяя) --}}
        <a href="{{ route('games.index') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-gamepad me-2"></i>Перейти к играм
        </a>

        {{-- Кнопка "Посмотреть таймлайн" (прозрачная с синей обводкой) --}}
        <a href="{{ route('timeline') }}" class="btn btn-outline-primary btn-lg ms-2">
            <i class="fas fa-timeline me-2"></i>Посмотреть таймлайн
        </a>
    </div>
</div>
@endsection
