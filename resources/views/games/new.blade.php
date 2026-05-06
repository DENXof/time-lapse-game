@extends('layouts.app')

@section('title', 'Топ-100 игр - TimeLapse Games')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>
            <i class="fas fa-trophy text-warning me-2"></i>
            Топ-100 игр
        </h1>
        <a href="{{ route('games.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Все игры
        </a>
    </div>

    <div class="alert alert-info mb-4">
        <i class="fas fa-info-circle me-2"></i>
        Топ-100 составлен на основе пользовательских оценок. Чем выше рейтинг и больше оценок, тем выше позиция.
    </div>

    @if($games->count() > 0)
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($games as $index => $game)
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="text-center" style="width: 50px;">
                                        @if($index + 1 == 1)
                                            <i class="fas fa-crown fa-2x text-warning"></i>
                                        @elseif($index + 1 == 2)
                                            <i class="fas fa-medal fa-2x text-secondary"></i>
                                        @elseif($index + 1 == 3)
                                            <i class="fas fa-medal fa-2x text-bronze" style="color: #cd7f32;"></i>
                                        @else
                                            <span class="fs-4 fw-bold text-muted">{{ $index + 1 }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-3 col-lg-2">
                                    @if($game->cover_image)
                                        <img src="{{ Storage::url($game->cover_image) }}"
                                             class="img-fluid rounded"
                                             style="height: 60px; width: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                             style="width: 60px; height: 60px;">
                                            <i class="fas fa-gamepad text-light fa-2x"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-5 col-lg-6">
                                    <h5 class="mb-1">
                                        <a href="{{ route('games.show', $game->slug) }}" class="text-decoration-none">
                                            {{ $game->title }}
                                        </a>
                                    </h5>
                                    <p class="text-muted small mb-0">
                                        {{ $game->release_year }} • {{ $game->genre->name ?? 'Без жанра' }}
                                    </p>
                                </div>

                                <div class="col-md-3 col-lg-2">
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= round($game->rating_avg))
                                                <i class="fas fa-star"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <small class="text-muted">{{ number_format($game->rating_avg, 1) }} ({{ $game->rating_count }} оценок)</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-4">
            {{ $games->links() }}
        </div>
    @else
        <div class="alert alert-warning text-center py-5">
            <i class="fas fa-chart-line fa-3x mb-3"></i>
            <p>Пока недостаточно оценок для составления топа.</p>
            <a href="{{ route('games.index') }}" class="btn btn-primary">
                Перейти к играм
            </a>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .text-bronze {
        color: #cd7f32;
    }
</style>
@endpush
