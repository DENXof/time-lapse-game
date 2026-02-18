{{--СТРАНИЦА РЕДАКТИРОВАНИЯ ЖАНРА В АДМИНКЕ--}}
@extends('layouts.admin')

{{-- Устанавливаем заголовки страницы --}}
@section('title', 'Редактирование жанра')
@section('page-title', 'Редактирование жанра')
@section('page-subtitle', 'Изменение данных жанра')

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
                    <i class="fas fa-edit me-2"></i>  {{-- Иконка карандаша (редактирование) --}}
                    Редактирование жанра
                </h5>
            </div>

            {{-- ТЕЛО КАРТОЧКИ (ФОРМА) --}}
            <div class="card-body">

                {{--
                    ФОРМА РЕДАКТИРОВАНИЯ ЖАНРА
                    action: admin.genres.update (с ID жанра)
                --}}
                <form action="{{ route('admin.genres.update', $genre) }}" method="POST">

                    {{-- CSRF-токен для защиты --}}
                    @csrf

                    {{--
                        @method('PUT') - подмена HTTP-метода
                        Формы HTML поддерживают только GET и POST,
                        PUT для обновления
                    --}}
                    @method('PUT')

                    {{-- ПОЛЕ: НАЗВАНИЕ ЖАНРА --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Название жанра *</label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name', $genre->name) }}"  {{-- Старое значение ИЛИ текущее из БД --}}
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
                                  placeholder="Краткое описание жанра...">{{ old('description', $genre->description) }}</textarea>
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
                                       value="{{ old('icon', $genre->icon) }}"  {{-- Текущая иконка из БД --}}
                                       required>
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

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
                                       value="{{ old('sort_order', $genre->sort_order) }}"  {{-- Текущее значение --}}
                                       min="0">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- ПОЛЕ: АКТИВНОСТЬ (переключатель) --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="is_active"
                                           name="is_active"
                                           value="1"
                                           {{-- Если жанр активен - ставим галочку --}}
                                           {{ old('is_active', $genre->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Активный жанр
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- КНОПКИ --}}
                    <div class="mt-4">
                        {{-- Кнопка "Обновить жанр" (синяя) --}}
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i>Обновить жанр
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

{{-- СКРИПТЫ ДЛЯ БЫСТРОГО ВЫБОРА ИКОНОК --}}
@push('scripts')
<script>
    // Ждем загрузки страницы
    $(document).ready(function() {

        // При клике на кнопку с иконкой
        $('.icon-btn').click(function() {
            // Берем иконку из атрибута data-icon
            const icon = $(this).data('icon');

            // Вставляем ее в поле ввода
            $('#icon').val(icon);
        });
    });
</script>
@endpush
