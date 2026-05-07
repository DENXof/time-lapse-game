@extends('layouts.app')

@section('title', 'Все достижения - TimeLapse Games')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">
        <i class="fas fa-trophy text-warning me-2"></i>
        Все достижения
    </h1>

    <div class="alert alert-info mb-4">
        <i class="fas fa-info-circle me-2"></i>
        Получайте достижения за активность на сайте. Чем больше достижений — тем выше ваш статус!
    </div>

    @php
        $typeNames = [
            'rating' => 'Оценки',
            'favorite' => 'Избранное',
            'comment' => 'Комментарии',
            'likes' => 'Лайки',
        ];
    @endphp

    @foreach($achievements as $type => $achievementsList)
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fas {{ $type == 'rating' ? 'fa-star' : ($type == 'favorite' ? 'fa-heart' : ($type == 'comment' ? 'fa-comments' : 'fa-thumbs-up')) }} me-2"></i>
                    {{ $typeNames[$type] ?? ucfirst($type) }}
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($achievementsList as $achievement)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center p-3 border rounded {{ in_array($achievement->id, $userAchievements) ? 'bg-success bg-opacity-10' : '' }}">
                                <div class="flex-shrink-0">
                                    <i class="{{ $achievement->icon }} fa-3x {{ in_array($achievement->id, $userAchievements) ? 'text-warning' : 'text-secondary' }}"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-0">
                                        {{ $achievement->name }}
                                        @if(in_array($achievement->id, $userAchievements))
                                            <span class="badge bg-success ms-2">Получено</span>
                                        @endif
                                    </h5>
                                    <p class="text-muted mb-1">{{ $achievement->description }}</p>
                                    <small class="text-warning">
                                        <i class="fas fa-star"></i> +{{ $achievement->points }} очков
                                    </small>
                                    <br>
                                    <small class="text-muted">
                                        Требуется: {{ $achievement->required_count }}
                                        @if($type == 'rating') оценок
                                        @elseif($type == 'favorite') игр в избранном
                                        @elseif($type == 'comment') комментариев
                                        @else лайков
                                        @endif
                                    </small>
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
