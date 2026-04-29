@extends('layouts.app')

@section('title', 'Избранное - TimeLapse Games')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">
        <i class="fas fa-heart text-danger me-2"></i>
        Мои избранные игры
    </h1>

    @if($favorites->count() > 0)
        <div class="row">
            @foreach($favorites as $game)
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        @if($game->cover_image)
                            <img src="{{ Storage::url($game->cover_image) }}"
                                 class="card-img-top"
                                 alt="{{ $game->title }}"
                                 style="height: 150px; object-fit: cover;">
                        @else
                            <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 150px;">
                                <i class="fas fa-gamepad fa-3x text-light"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $game->title }}</h5>
                            <p class="card-text text-muted small">
                                {{ $game->release_year }} • {{ $game->genre->name ?? 'Без жанра' }}
                            </p>
                        </div>
                        <div class="card-footer bg-white">
                            <a href="{{ route('games.show', $game->slug) }}" class="btn btn-primary btn-sm w-100">
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $favorites->links() }}
        </div>
    @else
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-heart-broken fa-4x mb-3 text-muted"></i>
            <p class="mb-3">У вас пока нет избранных игр.</p>
            <a href="{{ route('games.index') }}" class="btn btn-primary">
                <i class="fas fa-search me-2"></i>Перейти к играм
            </a>
        </div>
    @endif
</div>
@endsection
