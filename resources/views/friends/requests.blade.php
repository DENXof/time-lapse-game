@extends('layouts.app')

@section('title', 'Заявки в друзья - TimeLapse Games')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>
            <i class="fas fa-user-plus text-warning me-2"></i>
            Заявки в друзья
        </h1>
        <a href="{{ route('friends.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Мои друзья
        </a>
    </div>

    @if($requests->count() > 0)
        <div class="row">
            @foreach($requests as $friendship)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            @if($friendship->user->avatar)
                                <img src="{{ Storage::url($friendship->user->avatar) }}"
                                     class="rounded-circle mb-3"
                                     style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-user fa-2x text-white"></i>
                                </div>
                            @endif

                            <h5>{{ $friendship->user->name }}</h5>
                            <p class="text-muted small">{{ $friendship->created_at->diffForHumans() }}</p>

                            <div class="d-flex gap-2 justify-content-center">
                                <form action="{{ route('friends.accept', $friendship) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check me-2"></i>Принять
                                    </button>
                                </form>
                                <form action="{{ route('friends.reject', $friendship) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-times me-2"></i>Отклонить
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $requests->links() }}
        </div>
    @else
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-inbox fa-4x mb-3"></i>
            <p>Нет новых заявок в друзья</p>
        </div>
    @endif
</div>
@endsection
