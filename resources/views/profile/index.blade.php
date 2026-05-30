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

                    <!-- Соцсети (все рабочие ссылки) -->
                    <div class="mt-3">
                        <h6>Социальные сети и контакты:</h6>
                        <div class="d-flex flex-wrap justify-content-center gap-3 mt-3">
                            @if($user->telegram)
                                <a href="https://t.me/{{ ltrim($user->telegram, '@') }}"
                                   target="_blank" rel="noopener noreferrer"
                                   class="text-decoration-none" title="Telegram">
                                    <i class="fab fa-telegram fa-2x text-primary"></i>
                                </a>
                            @endif
                            @if($user->vk)
                                <a href="{{ preg_match('/^https?:\/\//', $user->vk) ? $user->vk : 'https://vk.com/' . ltrim($user->vk, '@') }}"
                                   target="_blank" rel="noopener noreferrer"
                                   class="text-decoration-none" title="ВКонтакте">
                                    <i class="fab fa-vk fa-2x text-primary"></i>
                                </a>
                            @endif
                            @if($user->github)
                                <a href="{{ preg_match('/^https?:\/\//', $user->github) ? $user->github : 'https://github.com/' . ltrim($user->github, '@') }}"
                                   target="_blank" rel="noopener noreferrer"
                                   class="text-decoration-none" title="GitHub">
                                    <i class="fab fa-github fa-2x text-dark"></i>
                                </a>
                            @endif
                            @if($user->steam)
                                <a href="{{ preg_match('/^https?:\/\//', $user->steam) ? $user->steam : 'https://steamcommunity.com/id/' . ltrim($user->steam, '/') }}"
                                   target="_blank" rel="noopener noreferrer"
                                   class="text-decoration-none" title="Steam">
                                    <i class="fab fa-steam fa-2x text-info"></i>
                                </a>
                            @endif
                            @if($user->twitch)
                                <a href="{{ preg_match('/^https?:\/\//', $user->twitch) ? $user->twitch : 'https://twitch.tv/' . ltrim($user->twitch, '@') }}"
                                   target="_blank" rel="noopener noreferrer"
                                   class="text-decoration-none" title="Twitch">
                                    <i class="fab fa-twitch fa-2x text-purple" style="color: #9146FF;"></i>
                                </a>
                            @endif
                            @if($user->youtube)
                                <a href="{{ preg_match('/^https?:\/\//', $user->youtube) ? $user->youtube : 'https://youtube.com/@' . ltrim($user->youtube, '@') }}"
                                   target="_blank" rel="noopener noreferrer"
                                   class="text-decoration-none" title="YouTube">
                                    <i class="fab fa-youtube fa-2x text-danger"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                    <hr>

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

            <!-- ВКЛАДКИ -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <ul class="nav nav-tabs card-header-tabs" id="profileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $tab == 'favorites' ? 'active' : '' }}"
                                    id="favorites-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#favorites"
                                    type="button" role="tab">
                                <i class="fas fa-heart text-danger"></i> Избранное
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $tab == 'ratings' ? 'active' : '' }}"
                                    id="ratings-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#ratings"
                                    type="button" role="tab">
                                <i class="fas fa-star text-warning"></i> Оценки
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $tab == 'comments' ? 'active' : '' }}"
                                    id="comments-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#comments"
                                    type="button" role="tab">
                                <i class="fas fa-comment text-info"></i> Комментарии
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Вкладка: ИЗБРАННОЕ -->
                        <div class="tab-pane fade {{ $tab == 'favorites' ? 'show active' : '' }}" id="favorites" role="tabpanel">
                            @if($favorites->count() > 0)
                                <div class="row">
                                    @foreach($favorites as $game)
                                        <div class="col-md-4 col-sm-6 mb-3">
                                            <a href="{{ route('games.show', $game->slug) }}" class="text-decoration-none">
                                                @if($game->cover_image)
                                                    <img src="{{ Storage::url($game->cover_image) }}"
                                                         class="img-fluid rounded mb-2"
                                                         style="height: 120px; width: 100%; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                                         style="height: 120px;">
                                                        <i class="fas fa-gamepad text-light fa-3x"></i>
                                                    </div>
                                                @endif
                                                <h6 class="text-dark text-center">{{ Str::limit($game->title, 25) }}</h6>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-3">
                                    {{ $favorites->appends(['tab' => 'favorites'])->links() }}
                                </div>
                            @else
                                <p class="text-muted text-center py-4">Нет избранных игр</p>
                            @endif
                        </div>

                        <!-- Вкладка: ОЦЕНКИ -->
                        <div class="tab-pane fade {{ $tab == 'ratings' ? 'show active' : '' }}" id="ratings" role="tabpanel">
                            @if($ratings->count() > 0)
                                <div class="list-group">
                                    @foreach($ratings as $rating)
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
                                                <small class="text-muted">{{ $rating->created_at->diffForHumans() }}</small>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                                <div class="mt-3">
                                    {{ $ratings->appends(['tab' => 'ratings'])->links() }}
                                </div>
                            @else
                                <p class="text-muted text-center py-4">Вы ещё не оценили ни одной игры</p>
                            @endif
                        </div>

                        <!-- Вкладка: КОММЕНТАРИИ -->
                        <div class="tab-pane fade {{ $tab == 'comments' ? 'show active' : '' }}" id="comments" role="tabpanel">
                            @if($comments->count() > 0)
                                <div class="list-group">
                                    @foreach($comments as $comment)
                                        <a href="{{ route('games.show', $comment->game->slug) }}"
                                           class="list-group-item list-group-item-action">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <strong>{{ $comment->game->title }}</strong>
                                                    <p class="mb-1 text-muted small">{{ Str::limit($comment->content, 100) }}</p>
                                                </div>
                                                <small class="text-muted ms-2">{{ $comment->created_at->diffForHumans() }}</small>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                                <div class="mt-3">
                                    {{ $comments->appends(['tab' => 'comments'])->links() }}
                                </div>
                            @else
                                <p class="text-muted text-center py-4">Вы ещё не оставили ни одного комментария</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- БЛОК ДОСТИЖЕНИЙ -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-trophy text-warning me-2"></i>Достижения ({{ $user->achievements->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($user->achievements->count() > 0)
                        <div class="row">
                            @foreach($user->achievements as $achievement)
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center p-2 border rounded">
                                        <div class="flex-shrink-0"><i class="{{ $achievement->icon }} fa-2x text-warning"></i></div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-0">{{ $achievement->name }}</h6>
                                            <small class="text-muted">{{ $achievement->description }}<br><i class="fas fa-star text-warning"></i> +{{ $achievement->points }} очков</small>
                                        </div>
                                        <div><small class="text-success">{{ \Carbon\Carbon::parse($achievement->pivot->earned_at)->format('d.m.Y') }}</small></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3 text-end border-top pt-3"><strong>Всего очков: {{ $user->total_points }}</strong></div>
                    @else
                        <p class="text-muted text-center mb-0">🎯 Пока нет достижений. Будьте активнее, чтобы их получать!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
