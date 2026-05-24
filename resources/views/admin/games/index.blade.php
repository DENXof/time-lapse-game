{{--ГЛАВНАЯ СТРАНИЦА УПРАВЛЕНИЯ ИГРАМИ В АДМИНКЕ--}}

@extends('layouts.admin')

@section('title', 'Управление играми')
@section('page-title', 'Управление играми')

@section('content')

<div class="card border-0 shadow">

    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">
                <i class="fas fa-gamepad me-2"></i>
                Управление играми
            </h5>
            <small class="text-muted">
                Найдено: {{ $games->total() }} игр
                @if(request('search'))
                    по запросу "{{ request('search') }}"
                @endif
            </small>
        </div>

        <div>
            {{-- КНОПКА ПОИСКА STEAM ID ДЛЯ ВСЕХ ИГР --}}
            <a href="{{ route('admin.games.find-missing-steam') }}"
               class="btn btn-warning btn-sm me-2"
               onclick="return confirm('Найти Steam ID для всех игр без него? Это может занять некоторое время.')">
                <i class="fab fa-steam me-1"></i>Найти Steam ID для всех игр
            </a>
            <a href="{{ route('admin.games.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i>Добавить игру
            </a>
        </div>
    </div>

    <div class="card-body border-bottom">
        <form action="{{ route('admin.games.index') }}" method="GET" class="row g-3">
            <div class="col-md-10">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text"
                           class="form-control"
                           name="search"
                           placeholder="Поиск по названию игры..."
                           value="{{ request('search') }}"
                           autocomplete="off">
                    @if(request('search'))
                        <a href="{{ route('admin.games.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Сбросить
                        </a>
                    @endif
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Найти
                </button>
            </div>
        </form>
    </div>

    <div class="card-body">

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
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="50">ID</th>
                            <th>Название</th>
                            <th>Жанр</th>
                            <th>Год</th>
                            <th>Просмотры</th>
                            <th>Steam ID</th>
                            <th width="150">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($games as $game)
                            <tr>
                                <td>{{ $game->id }}</td>
                                <td>
                                    <strong>{{ $game->title }}</strong>
                                    <br>
                                    <small class="text-muted">/{{ $game->slug }}</small>
                                </td>
                                <td>
                                    @if($game->genre)
                                        <span class="badge" style="background: {{ $game->genre->color }}; color: white;">
                                            {{ $game->genre->icon }} {{ $game->genre->name }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Без жанра</span>
                                    @endif
                                </td>
                                <td>{{ $game->release_year }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $game->views_count }}</span>
                                </td>
                                <td>
                                    @if($game->steam_app_id)
                                        <span class="badge bg-success">{{ $game->steam_app_id }}</span>
                                    @else
                                        <span class="badge bg-secondary">Не найден</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('games.show', $game->slug) }}"
                                           class="btn btn-outline-info"
                                           title="Просмотреть на сайте"
                                           target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.games.edit', $game->id) }}"
                                           class="btn btn-outline-primary"
                                           title="Редактировать">
                                            <i class="fas fa-edit"></i>
                                        </a>
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
                                </tr>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $games->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    const searchForm = document.querySelector('form');

    if (searchInput && !searchInput.value) {
        searchInput.focus();
    }

    searchInput?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            this.form.submit();
        }
    });

    document.querySelector('.btn-outline-secondary')?.addEventListener('click', function(e) {
        searchInput.value = '';
        e.preventDefault();
        searchForm.submit();
    });
});
</script>

<style>
.input-group-text {
    background-color: #f8f9fa;
    border-right: none;
}

.form-control:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
}

.form-control:focus + .input-group-text {
    border-color: #3498db;
    border-left: none;
}
</style>
@endsection
