{{--СТРАНИЦА СОЗДАНИЯ НОВОГО ЖАНРА В АДМИНКЕ--}}

@extends('layouts.admin')

{{-- Устанавливаем заголовки страницы --}}
@section('title', 'Создание жанра')
@section('page-title', 'Создание жанра')
@section('page-subtitle', 'Добавление нового игрового жанра')

{{-- Начинаем секцию контента --}}
@section('content')

{{-- Центрируем форму по горизонтали --}}
<div class="row justify-content-center">
    {{-- На средних экранах 8 колонок из 12 --}}
    <div class="col-md-8">

        {{-- КАРТОЧКА С ФОРМОЙ --}}
        <div class="card border-0 shadow">

            {{-- ШАПКА КАРТОЧКИ --}}
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-plus me-2"></i>  {{-- Иконка плюса --}}
                    Новый жанр
                </h5>
            </div>

            {{-- ТЕЛО КАРТОЧКИ (ФОРМА) --}}
            <div class="card-body">

                {{--
                    ФОРМА СОЗДАНИЯ ЖАНРА
                    action: admin.genres.store (POST запрос)
                --}}
                <form action="{{ route('admin.genres.store') }}" method="POST">

                    {{-- CSRF-токен для защиты --}}
                    @csrf

                    {{-- ПОЛЕ: НАЗВАНИЕ ЖАНРА --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Название жанра *</label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"  {{-- Старое значение при ошибке --}}
                               required
                               placeholder="Например: Экшен, RPG, Стратегия">

                        {{-- Показываем ошибку, если есть --}}
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ПОЛЕ: ОПИСАНИЕ --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Описание</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description"
                                  name="description"
                                  rows="3"
                                  placeholder="Краткое описание жанра...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- СТРОКА С ПОЛЕМ ИКОНКИ --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="icon" class="form-label">Иконка *</label>
                                <input type="text"
                                       class="form-control @error('icon') is-invalid @enderror"
                                       id="icon"
                                       name="icon"
                                       value="{{ old('icon', '🎮') }}"  {{-- По умолчанию 🎮 --}}
                                       required
                                       placeholder="Вставьте эмодзи">
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                {{-- Подсказка под полем --}}
                                <small class="text-muted">Используйте эмодзи, например: 🎮, ⚔️, 👻, 🚗</small>

                                {{-- КНОПКИ БЫСТРОГО ВЫБОРА ИКОНОК --}}
                                <div class="mt-2">
                                    <small>Популярные иконки:</small>
                                    <div class="d-flex gap-2 mt-1">
                                        <button type="button" class="btn btn-sm btn-outline-secondary icon-btn" data-icon="🎮">🎮</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary icon-btn" data-icon="⚔️">⚔️</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary icon-btn" data-icon="👻">👻</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary icon-btn" data-icon="🚗">🚗</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary icon-btn" data-icon="⚽">⚽</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary icon-btn" data-icon="🧙">🧙</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- СТРОКА С ДВУМЯ ПОЛЯМИ: СОРТИРОВКА И АКТИВНОСТЬ --}}
                    <div class="row">

                        {{-- ПОЛЕ: ПОРЯДОК СОРТИРОВКИ --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sort_order" class="form-label">Порядок сортировки</label>
                                <input type="number"
                                       class="form-control @error('sort_order') is-invalid @enderror"
                                       id="sort_order"
                                       name="sort_order"
                                       value="{{ old('sort_order', 0) }}"  {{-- По умолчанию 0 --}}
                                       min="0"
                                       placeholder="0">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Чем меньше число, тем выше в списке</small>
                            </div>
                        </div>

                        {{-- ПОЛЕ: АКТИВНОСТЬ (переключатель) --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                {{-- form-check form-switch - стилизованный переключатель --}}
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="is_active"
                                           name="is_active"
                                           value="1"
                                           {{-- По умолчанию включен --}}
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Активный жанр
                                    </label>
                                </div>
                                <small class="text-muted">Неактивные жанры не отображаются на сайте</small>
                            </div>
                        </div>
                    </div>

                    {{-- КНОПКИ --}}
                    <div class="mt-4">
                        {{-- Кнопка "Создать жанр" --}}
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i>Создать жанр
                        </button>

                        {{-- Кнопка "Отмена" - ведет на список жанров --}}
                        <a href="{{ route('admin.genres.index') }}" class="btn btn-secondary ms-2">
                            <i class="fas fa-times me-1"></i>Отмена
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

{{--
    СКРИПТЫ, КОТОРЫЕ ЗАГРУЗЯТСЯ В КОНЦЕ СТРАНИЦЫ
    @push('scripts') - добавляет код в секцию scripts в макете
--}}
@push('scripts')
<script>
    // Ждем загрузки страницы
    $(document).ready(function() {

        // Выбор иконки при клике на кнопку
        $('.icon-btn').click(function() {
            // Берем иконку из data-icon (например "🎮")
            const icon = $(this).data('icon');

            // Вставляем ее в поле ввода
            $('#icon').val(icon);
        });
    });
</script>
@endpush
