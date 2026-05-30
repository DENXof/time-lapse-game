@extends('layouts.admin')

@section('title', 'Просмотр администратора')
@section('page-title', 'Информация об администраторе')
@section('page-subtitle', 'Детальная информация и действия')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body text-center">
                <div class="mb-3">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 100px; height: 100px;">
                        <i class="fas fa-user-shield fa-4x text-white"></i>
                    </div>
                </div>
                <h4>{{ $admin->name }}</h4>
                <p class="text-muted">{{ $admin->email }}</p>
                <hr>
                <div class="text-start">
                    <p><strong>Роль:</strong>
                        @if($admin->role === 'super_admin')
                            <span class="badge bg-danger">Супер-админ</span>
                        @elseif($admin->role === 'admin')
                            <span class="badge bg-primary">Администратор</span>
                        @else
                            <span class="badge bg-secondary">Модератор</span>
                        @endif
                    </p>
                    <p><strong>Статус:</strong>
                        @if($admin->is_active)
                            <span class="badge bg-success">Активен</span>
                        @else
                            <span class="badge bg-danger">Заблокирован</span>
                        @endif
                    </p>
                    <p><strong>Зарегистрирован:</strong> {{ $admin->created_at->format('d.m.Y H:i') }}</p>
                    <p><strong>Последний вход:</strong> {{ $admin->last_login_at ? $admin->last_login_at->format('d.m.Y H:i') : 'Никогда' }}</p>
                    <p><strong>IP последнего входа:</strong> {{ $admin->last_login_ip ?? 'Неизвестен' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-key me-2 text-warning"></i>Сброс пароля</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.admins.reset-password', $admin) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <input type="password" name="password" class="form-control" placeholder="Новый пароль" required>
                        </div>
                        <div class="col-md-8 mt-2">
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Подтверждение пароля" required>
                        </div>
                        <div class="col-md-4 mt-2">
                            <button type="submit" class="btn btn-warning w-100">Сбросить пароль</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-history me-2 text-secondary"></i>История действий</h5>
            </div>
            <div class="card-body p-0">
                @if($logs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Действие</th>
                                    <th>Объект</th>
                                    <th>IP адрес</th>
                                    <th>Время</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $log)
                                    <tr>
                                        <td>{{ $log->action }}</td>
                                        <td>{{ $log->target_type }} #{{ $log->target_id }}</td>
                                        <td>{{ $log->ip_address }}</td>
                                        <td>{{ $log->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        {{ $logs->links() }}
                    </div>
                @else
                    <p class="text-muted text-center py-4 mb-0">Нет записей о действиях</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
