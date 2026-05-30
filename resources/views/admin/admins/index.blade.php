@extends('layouts.admin')

@section('title', 'Управление администраторами')
@section('page-title', 'Администраторы')
@section('page-subtitle', 'Управление учетными записями администраторов')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-user-shield me-2 text-primary"></i>
            Список администраторов
        </h5>
        <a href="{{ route('admin.admins.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i>Добавить администратора
        </a>
    </div>
    <div class="card-body p-0">
        @if($admins->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Роль</th>
                            <th>Статус</th>
                            <th>Последний вход</th>
                            <th>Дата регистрации</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admins as $admin)
                            <tr>
                                <td>{{ $admin->id }}</td>
                                <td>{{ $admin->name }}</td>
                                <td>{{ $admin->email }}</td>
                                <td>
                                    @if($admin->role === 'super_admin')
                                        <span class="badge bg-danger">Супер-админ</span>
                                    @else
                                        <span class="badge bg-primary">Администратор</span>
                                    @endif
                                </td>
                                <td>
                                    @if($admin->is_active)
                                        <span class="badge bg-success">Активен</span>
                                    @else
                                        <span class="badge bg-danger">Заблокирован</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $admin->last_login_at ? $admin->last_login_at->diffForHumans() : 'Никогда' }}
                                </td>
                                <td>{{ $admin->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.admins.show', $admin) }}" class="btn btn-outline-info" title="Просмотр">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-outline-primary" title="Редактировать">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($admin->id !== Auth::guard('admin')->id())
                                            <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Удалить администратора {{ $admin->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Удалить">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $admins->links() }}
            </div>
        @else
            <p class="text-muted text-center py-4 mb-0">Администраторы не найдены</p>
        @endif
    </div>
</div>
@endsection
