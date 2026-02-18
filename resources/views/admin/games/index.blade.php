{{--ГЛАВНАЯ СТРАНИЦА УПРАВЛЕНИЯ ИГРАМИ В АДМИНКЕ--}}

@extends('layouts.admin')

{{-- Устанавливаем заголовки страницы --}}
@section('title', 'Управление играми')
@section('page-title', 'Управление играми')

{{-- Начинаем секцию контента --}}
@section('content')

{{-- КАРТОЧКА СО СПИСКОМ ИГР --}}
<div class="card border-0 shadow">

    {{-- ШАПКА КАРТОЧКИ (заголовок + кнопка) --}}
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        {{-- Левая часть: заголовок и количество --}}
        <div>
            <h5 class="mb-0">
                <i class="fas fa-gamepad me-2"></i>  {{-- Иконка игр --}}
                Все игры
            </h5>
            <small class="text-muted">
                {{-- Показываем общее количество игр --}}
                Найдено: {{ $games->total() }} игр

                {{-- Если искали - показываем поисковый запрос --}}
                @if(request('search'))
                    по запросу "{{ request('search') }}"
                @endif
            </small>
        </div>

        {{-- Правая часть: кнопка "Добавить игру" --}}
        <a href="{{ route('admin.games.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Добавить игру
        </a>
    </div>

    {{-- ФОРМА ПОИСКА --}}
    <div class="card-body border-bottom">
        {{-- Форма отправляется методом GET (параметры в URL) --}}
        <form action="{{ route('admin.games.index') }}" method="GET" class="row g-3">

            {{-- Поле поиска (10 колонок) --}}
            <div class="col-md-10">
                <div class="input-group">
                    {{-- Иконка лупы перед полем --}}
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>

                    {{-- Поле ввода --}}
                    <input type="text"
                           class="form-control"
                           name="search"
                           placeholder="Поиск по названию игры..."
                           value="{{ request('search') }}"  {{-- Сохраняем введенное значение --}}
                           autocomplete="off">  {{-- Отключаем автозаполнение --}}

                    {{-- Если есть поисковый запрос - показываем кнопку "Сбросить" --}}
                    @if(request('search'))
                        <a href="{{ route('admin.games.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Сбросить
                        </a>
                    @endif
                </div>
            </div>

            {{-- Кнопка "Найти" (2 колонки) --}}
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Найти
                </button>
            </div>
        </form>
    </div>

    {{-- ОСНОВНОЕ СОДЕРЖИМОЕ --}}
    <div class="card-body">

        {{-- ЕСЛИ ИГР НЕТ --}}
        @if($games->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-gamepad fa-3x text-muted mb-3"></i>
                <h5>Игры не найдены</h5>
                <p class="text-muted">
                    @if(request('search'))
                        По запросу "{{ request('search') }}" ничего не найдено
                    @else
                        Игр пока нет
                    @endif
                </p>
                <a href="{{ route('admin.games.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Добавить игру
                </a>
            </div>

        {{-- ЕСЛИ ИГРЫ ЕСТЬ --}}
        @else
            {{-- Контейнер с горизонтальным скроллом для мобильных --}}
            <div class="table-responsive">
                <table class="table table-hover">

                    {{-- ЗАГОЛОВКИ ТАБЛИЦЫ --}}
                    <thead class="table-light">
                        <tr>
                            <th width="50">ID</th>
                            <th>Название</th>
                            <th>Жанр</th>
                            <th>Год</th>
                            <th>Просмотры</th>
                            <th>Дата</th>
                            <th width="150">Действия</th>
                        </tr>
                    </thead>

                    {{-- ТЕЛО ТАБЛИЦЫ (перебираем игры) --}}
                    <tbody>
                        @foreach($games as $game)
                            <tr>
                                {{-- ID игры --}}
                                <td>{{ $game->id }}</td>

                                {{-- Название и slug --}}
                                <td>
                                    <strong>{{ $game->title }}</strong>
                                    <br>
                                    <small class="text-muted">/{{ $game->slug }}</small>
                                </td>

                                {{-- Жанр с иконкой и цветом --}}
                                <td>
                                    @if($game->genre)
                                        <span class="badge" style="background: {{ $game->genre->color }}; color: white;">
                                            {{ $game->genre->icon }} {{ $game->genre->name }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Без жанра</span>
                                    @endif
                                </td>

                                {{-- Год выпуска --}}
                                <td>{{ $game->release_year }}</td>

                                {{-- Количество просмотров --}}
                                <td>
                                    <span class="badge bg-info">{{ $game->views_count }}</span>
                                </td>

                                {{-- Дата создания --}}
                                <td>
                                    <small>{{ $game->created_at->format('d.m.Y') }}</small>
                                </td>

                                {{-- КНОПКИ ДЕЙСТВИЙ --}}
                                <td>
                                    {{-- Группа кнопок --}}
                                    <div class="btn-group btn-group-sm" role="group">

                                        {{-- Кнопка "Просмотр" (ведет на публичную страницу игры) --}}
                                        <a href="{{ route('games.show', $game->slug) }}"
                                           class="btn btn-outline-info"
                                           title="Просмотреть на сайте"
                                           target="_blank">  {{-- Открыть в новой вкладке --}}
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        {{-- Кнопка "Редактировать" --}}
                                        <a href="{{ route('admin.games.edit', $game->id) }}"
                                           class="btn btn-outline-primary"
                                           title="Редактировать">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        {{-- Кнопка "Удалить" (отправляет DELETE запрос) --}}
                                        <form action="{{ route('admin.games.destroy', $game->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Удалить игру «{{ $game->title }}»?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Удалить">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ПАГИНАЦИЯ (ссылки на страницы) --}}
            {{-- appends сохраняет параметры поиска при переходе между страницами --}}
            {{ $games->appends(request()->query())->links() }}
        @endif
    </div>
</div>

{{--
    JAVASCRIPT ДЛЯ УЛУЧШЕНИЯ ПОИСКА
    Добавляет автофокус и обработку Enter
--}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Находим поле поиска и форму
    const searchInput = document.querySelector('input[name="search"]');
    const searchForm = document.querySelector('form');

    // Автофокус на поле поиска (если оно пустое)
    if (searchInput && !searchInput.value) {
        searchInput.focus();
    }

    // Отправка формы при нажатии Enter (без перезагрузки страницы)
    searchInput?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();  // Отменяем стандартное поведение
            this.form.submit();   // Отправляем форму
        }
    });

    // Очистка поиска при клике на кнопку сброса
    document.querySelector('.btn-outline-secondary')?.addEventListener('click', function(e) {
        searchInput.value = '';  // Очищаем поле
        e.preventDefault();       // Не переходим по ссылке
        searchForm.submit();      // Отправляем пустую форму (сброс поиска)
    });
});
</script>

{{--
    ДОПОЛНИТЕЛЬНЫЕ СТИЛИ
    Для красивого оформления формы поиска
--}}
<style>
/* Убираем правую границу у иконки поиска */
.input-group-text {
    background-color: #f8f9fa;
    border-right: none;
}

/* Стили для поля ввода в фокусе */
.form-control:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
}

/* Убираем левую границу у иконки когда поле в фокусе */
.form-control:focus + .input-group-text {
    border-color: #3498db;
    border-left: none;
}

/* Стиль для подсветки найденного текста (пока не используется) */
.highlight {
    background-color: #fff3cd;
    font-weight: bold;
    padding: 2px 4px;
    border-radius: 3px;
}
</style>
@endsection
