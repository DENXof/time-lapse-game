@extends('layouts.admin')

@section('title', 'Управление пользователями - Админ-панель')
@section('page-title', 'Пользователи')
@section('page-subtitle', 'Управление пользователями сайта')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-users me-2"></i>Список пользователей
        </h5>
        <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm"
                   placeholder="Поиск..." value="{{ request('search') }}" style="width: 200px;">
            <select name="role" class="form-select form-select-sm" style="width: 120px;">
                <option value="">Все роли</option>
                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Пользователь</option>
                <option value="moderator" {{ request('role') == 'moderator' ? 'selected' : '' }}>Модератор</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Админ</option>
            </select>
            <select name="status" class="form-select form-select-sm" style="width: 120px;">
                <option value="">Все статусы</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Активен</option>
                <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Забанен</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-search"></i>
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-undo"></i>
            </a>
        </form>
    </div>
    <div class="card-body p-0">
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Аватар</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Роль</th>
                            <th>Статус</th>
                            <th>Зарегистрирован</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    @if($user->avatar)
                                        <img src="{{ Storage::url($user->avatar) }}"
                                             class="rounded-circle"
                                             style="width: 35px; height: 35px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 35px; height: 35px;">
                                            <i class="fas fa-user fa-sm text-white"></i>
                                        </div>
                                    @endif
                                 </td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role == 'admin')
                                        <span class="badge bg-danger">Админ</span>
                                    @elseif($user->role == 'moderator')
                                        <span class="badge bg-warning">Модератор</span>
                                    @else
                                        <span class="badge bg-secondary">Пользователь</span>
                                    @endif
                                 </td>
                                <td>
                                    @if($user->status == 'active')
                                        <span class="badge bg-success">Активен</span>
                                    @else
                                        <span class="badge bg-danger">Забанен</span>
                                    @endif
                                 </td>
                                <td>{{ $user->created_at->format('d.m.Y') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.users.show', $user) }}"
                                           class="btn btn-outline-info" title="Просмотр">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                           class="btn btn-outline-primary" title="Редактировать">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
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
            <p class="text-muted text-center py-4 mb-0">Пользователи не найдены</p>
        @endif
    </div>
</div>
@endsection
