@extends('layouts.admin')

@section('title', 'Настройки - Админ-панель')
@section('page-title', 'Настройки сайта')
@section('page-subtitle', 'Управление параметрами сайта')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Основные настройки</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Здесь будут настройки сайта: название, описание, соцсети и т.д.
                </div>
                <p class="text-muted text-center py-4 mb-0">Раздел в разработке</p>
            </div>
        </div>
    </div>
</div>
@endsection
