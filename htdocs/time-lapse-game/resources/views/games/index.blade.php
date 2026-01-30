@extends('layouts.app')

@section('title', 'Все игры')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Все игры</h1>

        {{-- Кнопка добавления только для админов --}}
        @auth
            @if(auth()->user()->is_admin)
                <a href="{{ route('admin.games.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Добавить игру
                </a>
            @endif
        @endauth
    </div>

    @if($games->count() > 0)
        <div class="row">
            @foreach($games as $game)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        @if($game->cover_image)
                            <img src="{{ Storage::url($game->cover_image) }}"
                                 class="card-img-top"
                                 alt="{{ $game->title }}"
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center"
                                 style="height: 200px;">
                                <i class="fas fa-gamepad fa-3x text-light"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $game->title }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($game->description, 100) }}</p>
                            <div class="mb-2">
                                <span class="badge bg-primary">{{ $game->genre->name ?? 'Без жанра' }}</span>
                                <span class="badge bg-secondary">{{ $game->release_year }}</span>
                            </div>
                        </div>
                        <div class="card-footer bg-white d-flex justify-content-between">
                            <a href="{{ route('games.show', $game->slug) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> Подробнее
                            </a>

                            {{-- Кнопки администрирования только для админов --}}
                            @auth
                                @if(auth()->user()->is_admin)
                                    <div>
                                        <a href="{{ route('admin.games.edit', $game->id) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.games.destroy', $game->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Удалить эту игру?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Пагинация -->
        @if($games->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $games->links() }}
            </div>
        @endif
    @else
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-gamepad fa-3x mb-3"></i>
            <h4>Игр пока нет</h4>

        </div>
    @endif
</div>
@endsection
