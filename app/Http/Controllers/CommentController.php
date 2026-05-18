@extends('layouts.app')

@section('title', $game->title . ' - TimeLapse Games')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h1>{{ $game->title }}</h1>

                    <div class="mb-3">
                        <span class="badge bg-primary">{{ $game->genre->name ?? 'Без жанра' }}</span>
                        <span class="badge bg-secondary">{{ $game->release_year }} год</span>
                        <span class="badge bg-info">{{ $game->views_count ?? 0 }} просмотров</span>
                        <span class="badge bg-warning text-dark">
                            @if($game->rating_count > 0)
                                {{ number_format($game->rating_avg, 1) }} ({{ $game->rating_count }})
                            @else
                                Нет оценок
                            @endif
                        </span>
                    </div>

                    @auth
                        <form action="{{ route('favorites.toggle', $game) }}" method="POST" class="d-inline-block mb-3">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">
                                {{ Auth::user()->hasFavorited($game->id) ? '❤️ В избранном' : '🤍 В избранное' }}
                            </button>
                        </form>
                    @endauth

                    <div class="mt-4">
                        <h5>Разработчик</h5>
                        <p>{{ $game->developer }}</p>
                        <h5>Платформа</h5>
                        <p>{{ $game->platform }}</p>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h3>Описание</h3>
                </div>
                <div class="card-body">
                    <p>{{ $game->description }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h4>Похожие игры</h4>
                </div>
                <div class="card-body">
                    @foreach($relatedGames as $relatedGame)
                        <div class="mb-2">
                            <a href="{{ route('games.show', $relatedGame->slug) }}">{{ $relatedGame->title }}</a>
                            <small class="text-muted">({{ $relatedGame->release_year }})</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <a href="{{ route('games.index') }}" class="btn btn-secondary">← Вернуться к списку игр</a>
</div>
@endsection
