@extends('layouts.app')

@section('title', $game->title . ' - TimeLapse Games')

@section('content')
<div class="container py-4">

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('games.index') }}">Игры</a></li>
            <li class="breadcrumb-item active">{{ $game->title }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="row g-0">
                    <div class="col-md-4">
                        @if($game->cover_image)
                            <img src="{{ Storage::url($game->cover_image) }}" class="img-fluid rounded-start" alt="{{ $game->title }}" style="height: 100%; object-fit: cover;" loading="lazy">
                        @else
                            <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 300px;">
                                <i class="fas fa-gamepad fa-5x text-light"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h1 class="card-title">{{ $game->title }}</h1>

                            <!-- Предупреждение для игр 18+ -->
                            @if($game->age_rating == '18+')
                                <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Внимание!</strong> Эта игра содержит контент для взрослых (18+).
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

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
                                <span class="badge {{ $game->getAgeRatingBadgeColor() }}">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    {{ $game->age_rating }}
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

                            {{-- БЛОК ОЦЕНКИ (только для авторизованных) --}}
                            @auth
                                <div class="mt-3 mb-3 p-3 bg-light rounded">
                                    <p class="mb-2"><strong>Оценить игру:</strong></p>
                                    <form action="{{ route('ratings.store', $game) }}" method="POST" id="rating-form">
                                        @csrf
                                        <div class="rating-stars d-flex gap-2 mb-2">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <label class="cursor-pointer" style="cursor: pointer;">
                                                    <input type="radio" name="value" value="{{ $i }}"
                                                           style="display: none;"
                                                           onchange="document.getElementById('rating-form').submit()"
                                                           {{ optional($game->userRating(Auth::user()))->value == $i ? 'checked' : '' }}>
                                                    <i class="fas fa-star fs-3 {{ optional($game->userRating(Auth::user()))->value >= $i ? 'text-warning' : 'text-secondary' }}"></i>
                                                </label>
                                            @endfor
                                        </div>
                                    </form>
                                    <small class="text-muted">Нажмите на звезду, чтобы оценить</small>
                                </div>
                            @endauth

                            <div class="mt-4">
                                <h5>Разработчик</h5>
                                <p>{{ $game->developer }}</p>
                                @if($game->publisher)
                                    <h5>Издатель</h5>
                                    <p>{{ $game->publisher }}</p>
                                @endif
                                <h5>Платформа</h5>
                                <p>{{ $game->platform }}</p>
                            </div>
                        </div>
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

            @if(isset($trailer) && $trailer)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h3 class="mb-0">
                            <i class="fab fa-youtube text-danger me-2"></i>
                            Трейлер
                        </h3>
                    </div>
                    <div class="card-body p-0 ratio ratio-16x9">
                        <iframe src="{{ $trailer['embed_url'] }}" title="{{ $trailer['title'] }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
            @endif

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h3>Комментарии ({{ $game->comments()->count() }})</h3>
                </div>
                <div class="card-body">
                    @auth
                        <form action="{{ route('comments.store', $game) }}" method="POST" class="mb-4">
                            @csrf
                            <textarea name="content" class="form-control" rows="3" placeholder="Ваш комментарий..." required></textarea>
                            <button type="submit" class="btn btn-primary mt-2">Отправить</button>
                        </form>
                    @else
                        <div class="alert alert-info text-center">
                            <a href="{{ route('login') }}">Войдите</a> или <a href="{{ route('register') }}">зарегистрируйтесь</a>
                        </div>
                    @endauth

                    @foreach($game->comments()->whereNull('parent_id')->latest()->get() as $comment)
                        <div class="border-bottom pb-2 mb-2">
                            <strong>{{ $comment->user->name }}</strong>
                            <small class="text-muted ms-2">{{ $comment->created_at->diffForHumans() }}</small>
                            <p class="mt-1 mb-1">{{ $comment->content }}</p>
                        </div>
                    @endforeach
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

            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h4>Информация</h4>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li><strong>Год выхода:</strong> {{ $game->release_year }}</li>
                        <li><strong>Жанр:</strong> {{ $game->genre->name ?? 'Не указан' }}</li>
                        <li><strong>Просмотров:</strong> {{ $game->views_count ?? 0 }}</li>
                        <li><strong>Платформа:</strong> {{ $game->platform }}</li>
                        <li><strong>Возрастной рейтинг:</strong>
                            <span class="badge {{ $game->getAgeRatingBadgeColor() }}">{{ $game->age_rating }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- ССЫЛКА НА STEAM --}}
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-light">
                    <h4 class="mb-0">
                        <i class="fab fa-steam text-info me-2"></i>
                        Игра в Steam
                    </h4>
                </div>
                <div class="card-body text-center">
                    <a href="{{ $game->getSteamUrl() }}"
                       class="btn btn-success btn-lg px-4"
                       target="_blank"
                       rel="noopener noreferrer">
                        <i class="fab fa-steam me-2 fa-lg"></i>
                        @if($game->steam_app_id)
                            Открыть страницу в Steam
                        @else
                            Найти в Steam
                        @endif
                    </a>
                    <p class="text-muted mt-3 mb-0 small">
                        <i class="fas fa-external-link-alt me-1"></i>
                        Откроется в новой вкладке
                    </p>
                    @if(!$game->steam_app_id && auth()->check() && auth()->user()->isAdmin())
                        <div class="mt-3">
                            <a href="{{ route('admin.games.edit', $game->id) }}" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-edit me-1"></i>Указать Steam App ID
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('games.index') }}" class="btn btn-secondary">← Вернуться к списку игр</a>
    </div>
</div>
@endsection
