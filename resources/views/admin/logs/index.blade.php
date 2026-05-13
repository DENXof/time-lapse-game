@extends('layouts.admin')

@section('title', 'Логи - Админ-панель')
@section('page-title', 'Логи действий')
@section('page-subtitle', 'История действий администраторов')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Журнал действий</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Администратор</th>
                        <th>Действие</th>
                        <th>Объект</th>
                        <th>IP адрес</th>
                        <th>Время</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Раздел в разработке
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
