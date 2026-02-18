{{--СТРАНИЦА РЕДАКТИРОВАНИЯ ИГРЫ В АДМИНКЕ--}}

@extends('layouts.admin')

{{-- Устанавливаем заголовки страницы --}}
@section('title', 'Редактирование игры')
@section('page-title', 'Редактирование игры')

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
                    <i class="fas fa-edit me-2"></i>  {{-- Иконка карандаша (редактирование) --}}
                    Редактировать игру
                </h5>
            </div>

            {{-- ТЕЛО КАРТОЧКИ (ФОРМА) --}}
            <div class="card-body">

                {{--
                    ФОРМА РЕДАКТИРОВАНИЯ ИГРЫ
                    action: admin.games.update (с ID игры)
                    enctype="multipart/form-data" - для загрузки файлов
                --}}
                <form action="{{ route('admin.games.update', $game->id) }}" method="POST" enctype="multipart/form-data">

                    {{-- CSRF-токен для защиты --}}
                    @csrf

                    {{--
                        @method('PUT') - подмена HTTP-метода
                        Формы HTML поддерживают только GET и POST,
                        а нам нужен PUT для обновления
                        PUT — метод протокола HTTP, который используется для обновления существующих ресурсов или создания новых, если они не существуют. Клиент передаёт серверу новое представление ресурса, а сервер либо создаёт новый ресурс, либо полностью заменяет старый
                    --}}
                    @method('PUT')

                    {{-- ПОЛЕ: НАЗВАНИЕ ИГРЫ --}}
                    <div class="mb-3">
                        <label for="title" class="form-label">Название игры *</label>
                        <input type="text"
                               class="form-control @error('title') is-invalid @enderror"
                               id="title"
                               name="title"
                               value="{{ old('title', $game->title) }}"  {{-- Старое значение ИЛИ текущее из БД --}}
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ПОЛЕ: ОПИСАНИЕ --}}
                    {{-- Description — это метатег, который содержит краткое описание содержимого веб-страницы. --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Описание *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description"
                                  name="description"
                                  rows="5"
                                  required>{{ old('description', $game->description) }}</textarea>
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

                                {{-- Перебираем все жанры --}}
                                @foreach($genres as $genre)
                                    <option value="{{ $genre->id }}"
                                            {{-- Если это текущий жанр игры или выбрали при ошибке --}}
                                            {{ old('genre_id', $game->genre_id) == $genre->id ? 'selected' : '' }}>
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
                                   value="{{ old('release_year', $game->release_year) }}"
                                   min="1900"
                                   max="{{ date('Y') + 5 }}"
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
                                   value="{{ old('developer', $game->developer) }}"
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
                                   value="{{ old('publisher', $game->publisher) }}">
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
                               value="{{ old('platform', $game->platform) }}"
                               required>
                        @error('platform')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ПОЛЕ: ЗАГРУЗКА ОБЛОЖКИ --}}
                    <div class="mb-3">
                        <label for="cover_image" class="form-label">Обложка игры</label>

                        {{-- Если у игры уже есть обложка --}}
                        @if($game->cover_image)
                            <div class="mb-2">
                                <p>Текущая обложка:</p>
                                {{-- Показываем миниатюру текущей обложки --}}
                                <img src="{{ Storage::url($game->cover_image) }}"
                                     alt="{{ $game->title }}"
                                     class="img-thumbnail" style="max-height: 200px;">
                            </div>

                            {{-- Чекбокс "Удалить текущую обложку" --}}
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox"
                                       id="remove_cover" name="remove_cover" value="1">
                                <label class="form-check-label" for="remove_cover">
                                    Удалить текущую обложку
                                </label>
                            </div>
                        @endif

                        {{-- Поле для загрузки новой обложки --}}
                        <input type="file"
                               class="form-control @error('cover_image') is-invalid @enderror"
                               id="cover_image"
                               name="cover_image"
                               accept="image/*">
                        <small class="text-muted">Оставьте пустым, чтобы сохранить текущую обложку</small>
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
                        {{-- Кнопка "Обновить игру" (желтая) - отправляет форму --}}
                        <button type="submit" class="btn btn-warning">
                            Обновить игру
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
