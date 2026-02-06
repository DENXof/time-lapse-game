@extends('layouts.app')

@section('title', 'Главная - TimeLapse Games')

@section('content')
<div class="hero">
    <div class="container text-center">
        <h1 class="display-4 fw-bold">TimeLapse Games</h1>
        <p class="lead">История игровой индустрии в одном месте</p>
        <a href="{{ route('games.index') }}" class="btn btn-light btn-lg mt-3">
            <i class="fas fa-gamepad me-2"></i>Смотреть игры
        </a>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card stats-card">
                <div class="stats-icon text-primary">
                    <i class="fas fa-gamepad"></i>
                </div>
                <h3>Игры</h3>
                <p class="text-muted">Вся история игровой индустрии</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card stats-card">
                <div class="stats-icon text-success">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3>Таймлайн</h3>
                <p class="text-muted">Хронология развития игр</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card stats-card">
                <div class="stats-icon text-warning">
                    <i class="fas fa-tags"></i>
                </div>
                <h3>Жанры</h3>
                <p class="text-muted">От RPG до шутеров</p>
            </div>
        </div>
    </div>

    <div class="text-center mt-5">
        <h2>Начните исследовать</h2>
        <p class="lead mb-4">Откройте для себя историю видеоигр через годы и жанры</p>
        <a href="{{ route('games.index') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-gamepad me-2"></i>Перейти к играм
        </a>
        <a href="{{ route('timeline') }}" class="btn btn-outline-primary btn-lg ms-2">
            <i class="fas fa-timeline me-2"></i>Посмотреть таймлайн
        </a>
    </div>
</div>
@endsection
