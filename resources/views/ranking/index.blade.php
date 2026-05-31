@extends('layouts.app')

@section('title', 'Рейтинг пользователей - TimeLapse Games')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h3 class="mb-0">
                        <i class="fas fa-trophy text-warning me-2"></i>
                        Рейтинг пользователей
                    </h3>
                    <p class="text-muted mb-0 mt-1">
                        Топ пользователей по количеству очков достижений
                    </p>
                </div>
                <div class="card-body p-0">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="80">#</th>
                                        <th>Пользователь</th>
                                        <th width="150">Очки</th>
                                        <th width="150">Достижения</th>
                                        <th width="150">Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>
                                                @if($user->rank == 1)
                                                    <div class="text-center">
                                                        <i class="fas fa-crown fa-2x text-warning"></i>
                                                        <div class="fw-bold">1</div>
                                                    </div>
                                                @elseif($user->rank == 2)
                                                    <div class="text-center">
                                                        <i class="fas fa-medal fa-2x text-secondary"></i>
                                                        <div class="fw-bold">2</div>
                                                    </div>
                                                @elseif($user->rank == 3)
                                                    <div class="text-center">
                                                        <i class="fas fa-medal fa-2x" style="color: #cd7f32;"></i>
                                                        <div class="fw-bold">3</div>
                                                    </div>
                                                @else
                                                    <div class="text-center fw-bold fs-5">{{ $user->rank }}</div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($user->avatar)
                                                        <img src="{{ Storage::url($user->avatar) }}"
                                                             class="rounded-circle me-2"
                                                             style="width: 40px; height: 40px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                             style="width: 40px; height: 40px;">
                                                            <i class="fas fa-user fa-lg text-white"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $user->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">Зарегистрирован: {{ $user->created_at->format('d.m.Y') }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning text-dark fs-6">
                                                    <i class="fas fa-star me-1"></i>
                                                    {{ number_format($user->total_points) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <i class="fas fa-trophy me-1"></i>
                                                    {{ $user->achievements()->count() }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('profile.show', $user) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-user me-1"></i>Профиль
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-4x text-muted mb-3"></i>
                            <h5>Пока нет пользователей</h5>
                            <p class="text-muted">Зарегистрируйтесь, чтобы попасть в рейтинг!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
