@extends('layouts.admin')

@section('title', 'Управление играми')
@section('page-title', 'Управление играми')

@section('content')
<div class="card border-0 shadow">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-gamepad me-2"></i>Все игры
        </h5>
        <a href="{{ route('admin.games.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Добавить игру
        </a>
    </div>

    <div class="card-body">
        @if($games->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-gamepad fa-3x text-muted mb-3"></i>
                <h5>Игры не найдены</h5>
                <p class="text-muted">Добавьте первую игру в каталог</p>
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
                            <th>Дата</th>
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
                                    <small>{{ $game->created_at->format('d.m.Y') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('games.show', $game->slug) }}"
                                           class="btn btn-outline-info" title="Просмотреть на сайте" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.games.edit', $game->id) }}"
                                           class="btn btn-outline-primary" title="Редактировать">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.games.destroy', $game->id) }}"
                                              method="POST" class="d-inline"
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

            {{ $games->links() }}
        @endif
    </div>
</div>
@endsection
