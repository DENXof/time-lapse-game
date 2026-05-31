@extends('layouts.app')

@section('title', 'Все игры - TimeLapse Games')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Боковая панель с фильтрами (скрывается на мобильных) -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm filters-sidebar">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-filter me-2"></i>Фильтры
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('games.index') }}">
                        <div class="mb-3">
                            <label class="form-label">Поиск</label>
                            <input type="text" name="search" class="form-control" placeholder="Название игры..." value="{{ request('search') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Жанр</label>
                            <select name="genre" class="form-select">
                                <option value="">Все жанры</option>
                                @foreach($genres as $genre)
                                    <option value="{{ $genre->id }}" {{ request('genre') == $genre->id ? 'selected' : '' }}>{{ $genre->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Десятилетие</label>
                            <select name="decade" class="form-select">
                                <option value="">Все годы</option>
                                <option value="1980" {{ request('decade') == '1980' ? 'selected' : '' }}>1980-1989</option>
                                <option value="1990" {{ request('decade') == '1990' ? 'selected' : '' }}>1990-1999</option>
                                <option value="2000" {{ request('decade') == '2000' ? 'selected' : '' }}>2000-2009</option>
                                <option value="2010" {{ request('decade') == '2010' ? 'selected' : '' }}>2010-2019</option>
                                <option value="2020" {{ request('decade') == '2020' ? 'selected' : '' }}>2020-2029</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Платформа</label>
                            <select name="platform" class="form-select">
                                <option value="">Все платформы</option>
                                <option value="PC" {{ request('platform') == 'PC' ? 'selected' : '' }}>PC</option>
                                <option value="PlayStation" {{ request('platform') == 'PlayStation' ? 'selected' : '' }}>PlayStation</option>
                                <option value="Xbox" {{ request('platform') == 'Xbox' ? 'selected' : '' }}>Xbox</option>
                                <option value="Nintendo" {{ request('platform') == 'Nintendo' ? 'selected' : '' }}>Nintendo</option>
                            </select>
                        </div>

                        <!-- Фильтр по возрастному рейтингу -->
                        <div class="mb-3">
                            <label class="form-label">Возрастной рейтинг</label>
                            <select name="age_rating" class="form-select">
                                <option value="">Все</option>
                                <option value="0+" {{ request('age_rating') == '0+' ? 'selected' : '' }}>0+ — Для всех</option>
                                <option value="6+" {{ request('age_rating') == '6+' ? 'selected' : '' }}>6+</option>
                                <option value="12+" {{ request('age_rating') == '12+' ? 'selected' : '' }}>12+</option>
                                <option value="16+" {{ request('age_rating') == '16+' ? 'selected' : '' }}>16+</option>
                                <option value="18+" {{ request('age_rating') == '18+' ? 'selected' : '' }}>18+</option>
                            </select>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label">Сортировка</label>
                            <select name="sort" class="form-select">
                                <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>📅 Сначала новые</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>📅 Сначала старые</option>
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>⭐ По рейтингу</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>👁️ По популярности</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Применить
                            </button>
                            <a href="{{ route('games.index') }}" class="btn btn-secondary">
                                <i class="fas fa-undo me-2"></i>Сбросить
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Список игр -->
        <div class="col-lg-9">
            @if($games->count() > 0)
                <div class="row g-3">
                    @foreach($games as $game)
                        <div class="col-sm-6 col-md-4 col-lg-4">
                            <div class="card h-100 shadow-sm">
                                @if($game->cover_image)
                                    <img src="{{ Storage::url($game->cover_image) }}"
                                         class="card-img-top"
                                         alt="{{ $game->title }}"
                                         style="height: 180px; object-fit: cover;"
                                         loading="lazy">
                                @else
                                    <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 180px;">
                                        <i class="fas fa-gamepad fa-3x text-light"></i>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ Str::limit($game->title, 30) }}</h5>
                                    <p class="card-text text-muted small">{{ Str::limit($game->description, 80) }}</p>
                                    <div class="mb-2">
                                        <span class="badge bg-primary">{{ $game->genre->name ?? 'Без жанра' }}</span>
                                        <span class="badge bg-secondary">{{ $game->release_year }}</span>
                                        <span class="badge {{ $game->getAgeRatingBadgeColor() }} ms-1">{{ $game->age_rating }}</span>
                                    </div>
                                    @if($game->rating_count > 0)
                                        <div class="text-warning small">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= round($game->rating_avg))
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                            <span class="text-muted">({{ $game->rating_count }})</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer bg-white">
                                    <a href="{{ route('games.show', $game->slug) }}" class="btn btn-sm btn-outline-primary w-100">
                                        Подробнее <i class="fas fa-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    {{ $games->links() }}
                </div>
            @else
                <div class="alert alert-warning text-center py-5">
                    <i class="fas fa-frown fa-3x mb-3"></i>
                    <h5>Игры не найдены</h5>
                    <p>Попробуйте изменить параметры поиска</p>
                    <a href="{{ route('games.index') }}" class="btn btn-primary">Сбросить фильтры</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
