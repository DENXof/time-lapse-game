@extends('layouts.app')

@section('title', 'Мой профиль - TimeLapse Games')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Левая колонка: аватар и информация -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    <!-- Аватар -->
                    @if($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}"
                             class="rounded-circle img-fluid mb-3"
                             style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                             style="width: 150px; height: 150px;">
                            <i class="fas fa-user fa-5x text-white"></i>
                        </div>
                    @endif

                    <h3>{{ $user->name }}</h3>
                    <p class="text-muted">{{ $user->email }}</p>

                    @if($user->bio)
                        <p class="mt-3">{{ $user->bio }}</p>
                    @endif

                    <hr>

                    <!-- Соцсети -->
                    @if($user->telegram || $user->discord)
                        <div class="mt-3">
                            <h6>Контакты:</h6>
                            @if($user->telegram)
                                <p><i class="fab fa-telegram text-primary"></i> {{ $user->telegram }}</p>
                            @endif
                            @if($user->discord)
                                <p><i class="fab fa-discord text-primary"></i> {{ $user->discord }}</p>
                            @endif
                        </div>
                        <hr>
                    @endif

                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-edit me-2"></i>Редактировать профиль
                    </a>
                </div>
            </div>
        </div>

        <!-- Правая колонка: статистика и активность -->
        <div class="col-md-8">
            <!-- Статистика -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <i class="fas fa-heart text-danger fa-2x mb-2"></i>
                            <h3>{{ $favoritesCount }}</h3>
                            <p class="text-muted mb-0">В избранном</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <i class="fas fa-star text-warning fa-2x mb-2"></i>
                            <h3>{{ $ratingsCount }}</h3>
                            <p class="text-muted mb-0">Оценок</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <i class="fas fa-comment text-info fa-2x mb-2"></i>
                            <h3>{{ $commentsCount }}</h3>
                            <p class="text-muted mb-0">Комментариев</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Последние оценки -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Последние оценки</h5>
                </div>
                <div class="card-body">
                    @if($recentRatings->count() > 0)
                        <div class="list-group">
                            @foreach($recentRatings as $rating)
                                <a href="{{ route('games.show', $rating->game->slug) }}"
                                   class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $rating->game->title }}</strong>
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
                                        <small class="text-muted">
                                            {{ $rating->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        <div class="mt-3 text-center">
                            <a href="{{ route('profile.ratings') }}" class="btn btn-sm btn-outline-primary">
                                Все оценки <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">Вы ещё не оценили ни одной игры</p>
                    @endif
                </div>
            </div>

            <!-- Избранное (последние) -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Избранные игры</h5>
                </div>
                <div class="card-body">
                    @if($recentFavorites->count() > 0)
                        <div class="row">
                            @foreach($recentFavorites as $game)
                                <div class="col-6 col-md-4 mb-3">
                                    <a href="{{ route('games.show', $game->slug) }}" class="text-decoration-none">
                                        @if($game->cover_image)
                                            <img src="{{ Storage::url($game->cover_image) }}"
                                                 class="img-fluid rounded mb-2"
                                                 style="height: 100px; width: 100%; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                                 style="height: 100px;">
                                                <i class="fas fa-gamepad text-light fa-2x"></i>
                                            </div>
                                        @endif
                                        <h6 class="text-dark text-center mb-0">{{ Str::limit($game->title, 20) }}</h6>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">Нет избранных игр</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
