{{--СТРАНИЦА ДОБАВЛЕНИЯ НОВОЙ ИГРЫ В АДМИНКЕ--}}

@extends('layouts.admin')

{{-- Устанавливаем заголовки страницы --}}
@section('title', 'Добавление игры')
@section('page-title', 'Добавление игры')

{{-- Начинаем секцию контента --}}
@section('content')

{{-- Центрируем форму по горизонтали --}}
<div class="row justify-content-center">
    {{-- На средних экранах 10 колонок из 12 --}}
    <div class="col-md-10">

        {{-- КАРТОЧКА С ФОРМОЙ --}}
        <div class="card border-0 shadow">

            {{-- ШАПКА КАРТОЧКИ --}}
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-plus me-2"></i>  {{-- Иконка плюса --}}
                    Добавить игру
                </h5>
            </div>

            {{-- ТЕЛО КАРТОЧКИ (ФОРМА) --}}
            <div class="card-body">

                {{--
                    ФОРМА ДОБАВЛЕНИЯ ИГРЫ
                    action: admin.games.store (POST запрос)
                    enctype="multipart/form-data" - нужно для загрузки файлов (обложки)
                --}}
                <form action="{{ route('admin.games.store') }}" method="POST" enctype="multipart/form-data">

                    {{-- CSRF-токен для защиты --}}
                    @csrf

                    {{-- ПОЛЕ: НАЗВАНИЕ ИГРЫ --}}
                    <div class="mb-3">
                        <label for="title" class="form-label">Название игры *</label>
                        <input type="text"
                               class="form-control @error('title') is-invalid @enderror"
                               id="title"
                               name="title"
                               value="{{ old('title') }}"  {{-- Старое значение при ошибке --}}
                               required>  {{-- Обязательное поле --}}

                        {{-- Показываем ошибку, если есть --}}
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ПОЛЕ: ОПИСАНИЕ --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Описание *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description"
                                  name="description"
                                  rows="5"
                                  required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- СТРОКА С ДВУМЯ ПОЛЯМИ: ЖАНР И ГОД --}}
                    <div class="row">

                        {{-- ЖАНР (выпадающий список) --}}
                        <div class="col-md-6 mb-3">
                            <label for="genre_id" class="form-label">Жанр *</label>
                            <select class="form-select @error('genre_id') is-invalid @enderror"
                                    id="genre_id" name="genre_id" required>
                                <option value="">Выберите жанр</option>

                                {{-- Перебираем все жанры из контроллера --}}
                                @foreach($genres as $genre)
                                    <option value="{{ $genre->id }}"
                                            {{ old('genre_id') == $genre->id ? 'selected' : '' }}>
                                        {{ $genre->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('genre_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ГОД ВЫПУСКА --}}
                        <div class="col-md-6 mb-3">
                            <label for="release_year" class="form-label">Год выхода *</label>
                            <input type="number"
                                   class="form-control @error('release_year') is-invalid @enderror"
                                   id="release_year"
                                   name="release_year"
                                   value="{{ old('release_year', date('Y')) }}"  {{-- По умолчанию текущий год --}}
                                   min="1900"           {{-- Минимум 1900 --}}
                                   max="{{ date('Y') + 5 }}"  {{-- Максимум текущий год +5 --}}
                                   required>
                            @error('release_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- СТРОКА С ДВУМЯ ПОЛЯМИ: РАЗРАБОТЧИК И ИЗДАТЕЛЬ --}}
                    <div class="row">

                        {{-- РАЗРАБОТЧИК --}}
                        <div class="col-md-6 mb-3">
                            <label for="developer" class="form-label">Разработчик *</label>
                            <input type="text"
                                   class="form-control @error('developer') is-invalid @enderror"
                                   id="developer"
                                   name="developer"
                                   value="{{ old('developer') }}"
                                   required>
                            @error('developer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ИЗДАТЕЛЬ (необязательное) --}}
                        <div class="col-md-6 mb-3">
                            <label for="publisher" class="form-label">Издатель</label>
                            <input type="text"
                                   class="form-control @error('publisher') is-invalid @enderror"
                                   id="publisher"
                                   name="publisher"
                                   value="{{ old('publisher') }}">
                            @error('publisher')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- ПОЛЕ: ПЛАТФОРМА --}}
                    <div class="mb-3">
                        <label for="platform" class="form-label">Платформа *</label>
                        <input type="text"
                               class="form-control @error('platform') is-invalid @enderror"
                               id="platform"
                               name="platform"
                               value="{{ old('platform', 'PC') }}"  {{-- По умолчанию PC --}}
                               required>
                        {{-- Подсказка под полем --}}
                        <small class="text-muted">Например: PC, PlayStation 5, Xbox Series X, Nintendo Switch</small>
                        @error('platform')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ПОЛЕ: ЗАГРУЗКА ОБЛОЖКИ --}}
                    <div class="mb-3">
                        <label for="cover_image" class="form-label">Обложка игры</label>
                        {{-- type="file" - для загрузки файлов --}}
                        <input type="file"
                               class="form-control @error('cover_image') is-invalid @enderror"
                               id="cover_image"
                               name="cover_image"
                               accept="image/*">  {{-- Только изображения --}}
                        <small class="text-muted">Рекомендуемый размер: 600x800px, макс. размер: 2MB</small>
                        @error('cover_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- КНОПКИ --}}
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        {{-- Кнопка "Отмена" - ведет на список игр --}}
                        <a href="{{ route('games.index') }}" class="btn btn-secondary me-md-2">
                            Отмена
                        </a>
                        {{-- Кнопка "Добавить игру" - отправляет форму --}}
                        <button type="submit" class="btn btn-primary">
                            Добавить игру
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
