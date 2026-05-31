@extends('layouts.app')

@section('title', 'Мои друзья - TimeLapse Games')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>
            <i class="fas fa-users text-primary me-2"></i>
            Мои друзья
        </h1>
        <div>
            <a href="{{ route('friends.requests') }}" class="btn btn-outline-warning me-2">
                <i class="fas fa-user-plus me-2"></i>Заявки
            </a>
            <a href="{{ route('activity.feed') }}" class="btn btn-outline-info">
                <i class="fas fa-rss me-2"></i>Лента
            </a>
        </div>
    </div>

    @if($friends->count() > 0)
        <div class="row">
            @foreach($friends as $friend)
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm text-center">
                        <div class="card-body">
                            @if($friend->avatar)
                                <img src="{{ Storage::url($friend->avatar) }}"
                                     class="rounded-circle mb-3"
                                     style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                     style="width: 100px; height: 100px;">
                                    <i class="fas fa-user fa-3x text-white"></i>
                                </div>
                            @endif

                            <h5 class="mb-1">{{ $friend->name }}</h5>
                            <p class="text-muted small">{{ $friend->email }}</p>

                            <!-- Статистика друга -->
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted">Очки</small>
                                    <div class="fw-bold text-warning">{{ $friend->total_points ?? 0 }}</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Достижений</small>
                                    <div class="fw-bold text-info">{{ $friend->achievements()->count() }}</div>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('profile.show', $friend) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-user me-2"></i>Профиль
                                </a>
                                <form action="{{ route('friends.remove', $friend) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100"
                                            onclick="return confirm('Удалить пользователя из друзей?')">
                                        <i class="fas fa-user-minus me-2"></i>Удалить
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $friends->links() }}
        </div>
    @else
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-user-friends fa-4x mb-3"></i>
            <p>У вас пока нет друзей.</p>
            <p>Найдите пользователей и добавьте их в друзья!</p>
            <a href="{{ route('ranking') }}" class="btn btn-primary">
                <i class="fas fa-chart-line me-2"></i>Найти пользователей
            </a>
        </div>
    @endif
</div>
@endsection
