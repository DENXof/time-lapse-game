@extends('layouts.admin')

@section('title', 'Просмотр пользователя - Админ-панель')
@section('page-title', 'Просмотр пользователя')
@section('page-subtitle', 'Информация о пользователе')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}"
                         class="rounded-circle mb-3"
                         style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                         style="width: 150px; height: 150px;">
                        <i class="fas fa-user fa-5x text-white"></i>
                    </div>
                @endif
                <h4>{{ $user->name }}</h4>
                <p class="text-muted">{{ $user->email }}</p>
                <hr>
                <div class="text-start">
                    <p><strong>Роль:</strong> {{ ucfirst($user->role) }}</p>
                    <p><strong>Статус:</strong>
                        @if($user->status == 'active')
                            <span class="badge bg-success">Активен</span>
                        @else
                            <span class="badge bg-danger">Забанен</span>
                        @endif
                    </p>
                    <p><strong>Зарегистрирован:</strong> {{ $user->created_at->format('d.m.Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-chart-simple me-2"></i>Статистика</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-3 text-center">
                            <i class="fas fa-star fa-2x text-warning mb-2"></i>
                            <h3>{{ $user->ratings()->count() }}</h3>
                            <p class="text-muted mb-0">Оценок</p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-3 text-center">
                            <i class="fas fa-heart fa-2x text-danger mb-2"></i>
                            <h3>{{ $user->favorites()->count() }}</h3>
                            <p class="text-muted mb-0">В избранном</p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-3 text-center">
                            <i class="fas fa-comments fa-2x text-info mb-2"></i>
                            <h3>{{ $user->comments()->count() }}</h3>
                            <p class="text-muted mb-0">Комментариев</p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-3 text-center">
                            <i class="fas fa-trophy fa-2x text-warning mb-2"></i>
                            <h3>{{ $user->achievements()->count() }}</h3>
                            <p class="text-muted mb-0">Достижений</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Назад
            </a>
        </div>
    </div>
</div>
@endsection
