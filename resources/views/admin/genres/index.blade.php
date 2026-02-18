{{--ГЛАВНАЯ СТРАНИЦА УПРАВЛЕНИЯ ЖАНРАМИ В АДМИНКЕ
    Здесь показан список всех жанров с возможностью редактирования и удаления
--}}
@extends('layouts.admin')

{{-- Устанавливаем заголовки страницы --}}
@section('title', 'Управление жанрами')
@section('page-title', 'Управление жанрами')
@section('page-subtitle', 'Список всех игровых жанров')

{{-- Начинаем секцию контента --}}
@section('content')

{{-- КАРТОЧКА СО СПИСКОМ ЖАНРОВ --}}
<div class="card border-0 shadow">

    {{-- ШАПКА КАРТОЧКИ (заголовок + кнопка) --}}
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        {{-- Заголовок слева --}}
        <h5 class="mb-0">
            <i class="fas fa-tags me-2"></i>  {{-- Иконка тегов --}}
            Все жанры
        </h5>

        {{-- Кнопка "Добавить жанр" справа --}}
        <a href="{{ route('admin.genres.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Добавить жанр
        </a>
    </div>

    {{-- ОСНОВНОЕ СОДЕРЖИМОЕ --}}
    <div class="card-body">

        {{-- ЕСЛИ ЖАНРОВ НЕТ --}}
        @if($genres->isEmpty())
            <div class="text-center py-5">
                {{-- Большая иконка тегов --}}
                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                <h5>Жанры не найдены</h5>
                <p class="text-muted">Добавьте первый жанр для ваших игр</p>
                {{-- Кнопка добавления первого жанра --}}
                <a href="{{ route('admin.genres.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Добавить жанр
                </a>
            </div>

        {{-- ЕСЛИ ЖАНРЫ ЕСТЬ --}}
        @else
            {{-- Контейнер с горизонтальным скроллом для мобильных --}}
            <div class="table-responsive">
                <table class="table table-hover">

                    {{-- ЗАГОЛОВКИ ТАБЛИЦЫ --}}
                    <thead class="table-light">
                        <tr>
                            <th width="50">Иконка</th>      {{-- Иконка жанра --}}
                            <th>Название</th>                {{-- Название и URL --}}
                            <th>Описание</th>                {{-- Описание жанра --}}
                            <th>Порядок</th>                 {{-- Порядок сортировки --}}
                            <th>Статус</th>                  {{-- Активен/неактивен --}}
                            <th width="150">Действия</th>    {{-- Кнопки --}}
                        </tr>
                    </thead>

                    {{-- ТЕЛО ТАБЛИЦЫ (перебираем жанры) --}}
                    <tbody>
                        @foreach($genres as $genre)
                            <tr>
                                {{-- ИКОНКА (увеличенный размер) --}}
                                <td class="text-center" style="font-size: 24px;">
                                    {!! $genre->icon !!}  {{-- Выводим как HTML, а не текст --}}
                                </td>

                                {{-- НАЗВАНИЕ И SLUG --}}
                                <td>
                                    <strong>{{ $genre->name }}</strong>  {{-- Название жирным --}}
                                    <br>
                                    <small class="text-muted">/{{ $genre->slug }}</small>  {{-- URL под названием --}}
                                </td>

                                {{-- ОПИСАНИЕ (обрезанное до 50 символов) --}}
                                <td>
                                    @if($genre->description)
                                        <small>{{ Str::limit($genre->description, 50) }}</small>
                                    @else
                                        <span class="text-muted">—</span>  {{-- Прочерк, если нет описания --}}
                                    @endif
                                </td>

                                {{-- ПОРЯДОК СОРТИРОВКИ --}}
                                <td>
                                    <span class="badge bg-light text-dark">{{ $genre->sort_order }}</span>
                                </td>

                                {{-- СТАТУС (цветной бейдж) --}}
                                <td>
                                    @if($genre->is_active)
                                        <span class="badge bg-success">Активен</span>   {{-- Зеленый --}}
                                    @else
                                        <span class="badge bg-danger">Неактивен</span>  {{-- Красный --}}
                                    @endif
                                </td>

                                {{-- КНОПКИ ДЕЙСТВИЙ --}}
                                <td>
                                    {{-- Группа кнопок --}}
                                    <div class="btn-group btn-group-sm" role="group">

                                        {{-- Кнопка "Редактировать" --}}
                                        <a href="{{ route('admin.genres.edit', $genre) }}"
                                           class="btn btn-outline-primary"
                                           title="Редактировать">
                                            <i class="fas fa-edit"></i>  {{-- Иконка карандаша --}}
                                        </a>

                                        {{-- Кнопка "Удалить" (отправляет DELETE запрос) --}}
                                        <form action="{{ route('admin.genres.destroy', $genre) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Удалить жанр «{{ $genre->name }}»?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Удалить">
                                                <i class="fas fa-trash"></i>  {{-- Иконка мусорки --}}
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
            {{ $genres->links() }}
        @endif
    </div>
</div>
@endsection
