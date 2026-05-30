@extends('layouts.admin')

@section('title', 'Логи администраторов')
@section('page-title', 'Логи действий')
@section('page-subtitle', 'История действий всех администраторов')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Фильтры</h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Действие</label>
                <select name="action" class="form-select">
                    <option value="">Все действия</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ $action }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Администратор</label>
                <select name="admin_id" class="form-select">
                    <option value="">Все администраторы</option>
                    @foreach($admins as $admin)
                        <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">С даты</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">По дату</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Применить</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm mt-4">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Журнал действий</h5>
    </div>
    <div class="card-body p-0">
        @if($logs->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Администратор</th>
                            <th>Действие</th>
                            <th>Объект</th>
                            <th>IP адрес</th>
                            <th>Время</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td>
                                    <strong>{{ $log->admin->name }}</strong>
                                    <br>
                                    <small class="text-muted">ID: {{ $log->admin->id }}</small>
                                </td>
                                <td>{{ $log->action }}</td>
                                <td>{{ $log->target_type }} #{{ $log->target_id }}</td>
                                <td>{{ $log->ip_address }}</td>
                                <td>{{ $log->created_at->format('d.m.Y H:i:s') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $logs->links() }}
            </div>
        @else
            <p class="text-muted text-center py-4 mb-0">Нет записей</p>
        @endif
    </div>
</div>
@endsection
