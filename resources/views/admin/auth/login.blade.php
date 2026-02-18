{{--СТРАНИЦА ВХОДА В АДМИН-ПАНЕЛЬ
    Показывает форму, где админ вводит email и пароль
--}}

@extends('layouts.app')

{{-- Устанавливаем заголовок страницы --}}
@section('title', 'Вход в админ-панель - TimeLapse Games')

{{-- Начинаем секцию контента, которая вставится в макет --}}
@section('content')

{{-- Контейнер с отступами сверху/снизу --}}
<div class="container py-5">
    {{-- Центрируем форму по горизонтали --}}
    <div class="row justify-content-center">
        {{-- На маленьких экранах 6 колонок, на больших 5 --}}
        <div class="col-md-6 col-lg-5">

            {{-- Карточка с тенью и без границ --}}
            <div class="card shadow-lg border-0">

                {{-- ШАПКА КАРТОЧКИ (синий фон) --}}
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-lock me-2"></i>  {{-- Иконка замка --}}
                        Вход в админ-панель
                    </h3>
                    <p class="mb-0 mt-2 opacity-75">TimeLapse Games - Панель управления</p>
                </div>

                {{-- ТЕЛО КАРТОЧКИ (форма) --}}
                <div class="card-body p-4">

                    {{-- ФОРМА ВХОДА --}}
                    {{-- action куда отправлять: admin.login.post --}}
                    {{-- method POST - данные не светятся в URL --}}
                    <form method="POST" action="{{ route('admin.login.post') }}">

                        {{-- CSRF-токен - защита от подделки запросов --}}
                        @csrf

                        {{-- ПОЛЕ EMAIL --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">Email адрес</label>

                            {{-- Группа с иконкой и полем --}}
                            <div class="input-group">
                                {{-- Иконка конверта перед полем --}}
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>

                                {{-- Поле ввода email --}}
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}"  {{-- Старое значение при ошибке --}}
                                       required                    {{-- Обязательное поле --}}
                                       autofocus                    {{-- Курсор сразу в этом поле --}}
                                       placeholder="admin@timelapse.com">

                                {{-- Если есть ошибка валидации для email --}}
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- ПОЛЕ ПАРОЛЯ --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">Пароль</label>

                            {{-- Группа с иконкой и полем --}}
                            <div class="input-group">
                                {{-- Иконка ключа перед полем --}}
                                <span class="input-group-text">
                                    <i class="fas fa-key"></i>
                                </span>

                                {{-- Поле ввода пароля --}}
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       required
                                       placeholder="••••••">  {{-- Точки вместо символов --}}

                                {{-- Если есть ошибка валидации для пароля --}}
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- ГАЛОЧКА "ЗАПОМНИТЬ МЕНЯ" --}}
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Запомнить меня</label>
                        </div>

                        {{-- КНОПКИ --}}
                        <div class="d-grid gap-2">
                            {{-- Кнопка входа (синяя, большая) --}}
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Войти
                            </button>

                            {{-- Ссылка на главную страницу (серая) --}}
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-home me-2"></i>На главную
                            </a>
                        </div>
                    </form>
                </div>

                {{-- ПОДВАЛ КАРТОЧКИ (предупреждение) --}}
                <div class="card-footer text-center text-muted py-3">
                    <small>
                        <i class="fas fa-info-circle me-1"></i>
                        Доступ только для администраторов системы
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
