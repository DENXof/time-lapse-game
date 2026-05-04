@extends('layouts.app')

@section('title', 'Мои оценки - TimeLapse Games')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Мои оценки</h1>
        <a href="{{ route('profile.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Назад в профиль
        </a>
    </div>

    @if($ratings->count() > 0)
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($ratings as $rating)
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <a href="{{ route('games.show', $rating->game->slug) }}"
                                       class="text-decoration-none">
                                        <strong>{{ $rating->game->title }}</strong>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $rating->value)
                                                <i class="fas fa-star"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <div class="col-md-3 text-md-end">
                                    <small class="text-muted">
                                        {{ $rating->created_at->format('d.m.Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-4">
            {{ $ratings->links() }}
        </div>
    @else
        <div class="alert alert-info text-center">
            <i class="fas fa-star fa-3x mb-3"></i>
            <p>Вы ещё не оценили ни одной игры</p>
            <a href="{{ route('games.index') }}" class="btn btn-primary">
                Перейти к играм
            </a>
        </div>
    @endif
</div>
@endsection
