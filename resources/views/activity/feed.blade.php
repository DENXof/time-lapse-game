@extends('layouts.app')

@section('title', 'Лента активности - TimeLapse Games')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>
            <i class="fas fa-rss text-primary me-2"></i>
            Лента активности друзей
        </h1>
        <a href="{{ route('activity.my') }}" class="btn btn-outline-secondary">
            <i class="fas fa-user me-2"></i>Моя активность
        </a>
    </div>

    @if($activities->count() > 0)
        <div class="timeline">
            @foreach($activities as $activity)
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                @if($activity->user->avatar)
                                    <img src="{{ Storage::url($activity->user->avatar) }}"
                                         class="rounded-circle"
                                         style="width: 48px; height: 48px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 48px; height: 48px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="mb-1">
                                    {!! \App\Services\ActivityService::getMessage($activity) !!}
                                </div>
                                <small class="text-muted">
                                    <i class="far fa-clock me-1"></i>
                                    {{ $activity->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-newspaper fa-4x mb-3"></i>
            <p>Пока нет активности от друзей</p>
            <a href="{{ route('friends.index') }}" class="btn btn-primary">
                Добавьте друзей
            </a>
        </div>
    @endif
</div>
@endsection
