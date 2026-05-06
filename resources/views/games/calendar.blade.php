@extends('layouts.app')

@section('title', 'Календарь релизов - TimeLapse Games')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>
            <i class="fas fa-calendar-alt text-primary me-2"></i>
            Календарь релизов
        </h1>
        <a href="{{ route('games.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Все игры
        </a>
    </div>

    @foreach($decades as $decade)
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">
                    <i class="fas fa-clock me-2"></i>
                    {{ $decade }}-е годы
                    <span class="badge bg-light text-dark ms-2">
                        {{ $gamesByDecade[$decade]->count() }} игр
                    </span>
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($gamesByDecade[$decade]->groupBy('release_year') as $year => $yearGames)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">{{ $year }} год</h5>
                                </div>
                                <div class="card-body p-2">
                                    <div class="list-group list-group-flush">
                                        @foreach($yearGames->take(5) as $game)
                                            <a href="{{ route('games.show', $game->slug) }}"
                                               class="list-group-item list-group-item-action">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>{{ $game->title }}</span>
                                                    @if($game->rating_count > 0)
                                                        <small class="text-warning">
                                                            @if($game->rating_avg >= 4)
                                                                <i class="fas fa-star"></i>
                                                            @endif
                                                            {{ number_format($game->rating_avg, 1) }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </a>
                                        @endforeach

                                        @if($yearGames->count() > 5)
                                            <div class="text-center mt-2">
                                                <small class="text-muted">
                                                    и ещё {{ $yearGames->count() - 5 }} игр...
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
