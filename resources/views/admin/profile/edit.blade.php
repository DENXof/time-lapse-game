{{--СТРАНИЦА РЕДАКТИРОВАНИЯ ПРОФИЛЯ АДМИНИСТРАТОРА--}}

{{-- Говорим, что этот шаблон использует макет админки layouts/admin --}}
@extends('layouts.admin')

{{-- Устанавливаем заголовки страницы --}}
@section('title', 'Редактирование профиля')
@section('page-title', 'Редактирование профиля')

{{-- Начинаем секцию контента --}}
@section('content')

{{-- ДВЕ КОЛОНКИ: основная информация (8 колонок) и смена пароля (4 колонки) --}}
<div class="row">

    {{-- ЛЕВАЯ КОЛОНКА (ОСНОВНАЯ ИНФОРМАЦИЯ) --}}
    <div class="col-md-8">
        <div class="card border-0 shadow">

            {{-- ШАПКА КАРТОЧКИ --}}
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-user-edit me-2"></i>  {{-- Иконка редактирования пользователя --}}
                    Основная информация
                </h5>
            </div>

            {{-- ТЕЛО КАРТОЧКИ --}}
            <div class="card-body">

                {{-- УВЕДОМЛЕНИЕ ОБ УСПЕХЕ (зеленое) --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}  {{-- Текст успеха --}}
                        {{-- Кнопка закрытия --}}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- УВЕДОМЛЕНИЕ ОБ ОШИБКАХ (красное) --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            {{-- Перебираем все ошибки и показываем каждую --}}
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- ФОРМА РЕДАКТИРОВАНИЯ ПРОФИЛЯ --}}
                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')  {{-- Подмена метода на PUT для обновления --}}

                    {{-- ПОЛЕ: ИМЯ --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Имя</label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name', $user->name) }}"  {{-- Старое ИЛИ текущее --}}
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ПОЛЕ: EMAIL --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Email адрес</label>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email', $user->email) }}"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ПОЛЕ: РОЛЬ (только для чтения) --}}
                    <div class="mb-3">
                        <label class="form-label">Роль</label>
                        <input type="text"
                               class="form-control bg-light"  {{-- Светлый фон --}}
                               value="{{ $user->is_admin ? 'Администратор' : 'Пользователь' }}"
                               readonly>  {{-- Нельзя редактировать --}}
                    </div>

                    {{-- ПОЛЕ: ДАТА РЕГИСТРАЦИИ (только для чтения) --}}
                    <div class="mb-3">
                        <label class="form-label">Дата регистрации</label>
                        <input type="text"
                               class="form-control bg-light"
                               value="{{ $user->created_at->format('d.m.Y H:i') }}"  {{-- Форматируем дату --}}
                               readonly>
                    </div>

                    {{-- КНОПКА СОХРАНЕНИЯ --}}
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Сохранить изменения
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ПРАВАЯ КОЛОНКА (СМЕНА ПАРОЛЯ) --}}
    <div class="col-md-4">
        <div class="card border-0 shadow">

            {{-- ШАПКА КАРТОЧКИ --}}
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-key me-2"></i>  {{-- Иконка ключа --}}
                    Смена пароля
                </h5>
            </div>

            {{-- ТЕЛО КАРТОЧКИ --}}
            <div class="card-body">

                {{-- ОШИБКИ ПАРОЛЯ (отдельный пакет ошибок) --}}
                @if($errors->password->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->password->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- УСПЕХ СМЕНЫ ПАРОЛЯ --}}
                @if(session('success') && !session()->has('errors'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- ФОРМА СМЕНЫ ПАРОЛЯ --}}
                <form action="{{ route('admin.profile.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- ПОЛЕ: ТЕКУЩИЙ ПАРОЛЬ --}}
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Текущий пароль</label>
                        <input type="password"
                               class="form-control @error('current_password', 'password') is-invalid @enderror"
                               id="current_password"
                               name="current_password"
                               required>
                        {{-- Ошибка из пакета 'password' --}}
                        @error('current_password', 'password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ПОЛЕ: НОВЫЙ ПАРОЛЬ --}}
                    <div class="mb-3">
                        <label for="password" class="form-label">Новый пароль</label>
                        <input type="password"
                               class="form-control @error('password', 'password') is-invalid @enderror"
                               id="password"
                               name="password"
                               required>
                        @error('password', 'password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ПОЛЕ: ПОДТВЕРЖДЕНИЕ ПАРОЛЯ --}}
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Подтверждение пароля</label>
                        <input type="password"
                               class="form-control"
                               id="password_confirmation"
                               name="password_confirmation"
                               required>
                    </div>

                    {{-- КНОПКА СМЕНЫ ПАРОЛЯ (желтая, на всю ширину) --}}
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="fas fa-key me-1"></i>Сменить пароль
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
