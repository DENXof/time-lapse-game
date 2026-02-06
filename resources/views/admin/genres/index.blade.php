{{-- resources/views/admin/genres/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Управление жанрами')
@section('page-title', 'Управление жанрами')
@section('page-subtitle', 'Список всех игровых жанров')

@section('content')
<div class="card border-0 shadow">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-tags me-2"></i>Все жанры
        </h5>
        <a href="{{ route('admin.genres.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Добавить жанр
        </a>
    </div>

    <div class="card-body">
        @if($genres->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                <h5>Жанры не найдены</h5>
                <p class="text-muted">Добавьте первый жанр для ваших игр</p>
                <a href="{{ route('admin.genres.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Добавить жанр
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="50">Иконка</th>
                            <th>Название</th>
                            <th>Описание</th>
                            <th>Порядок</th>
                            <th>Статус</th>
                            <th width="150">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($genres as $genre)
                            <tr>
                                <td class="text-center" style="font-size: 24px;">
                                    {!! $genre->icon !!}
                                </td>
                                <td>
                                    <strong>{{ $genre->name }}</strong>
                                    <br>
                                    <small class="text-muted">/{{ $genre->slug }}</small>
                                </td>
                                <td>
                                    @if($genre->description)
                                        <small>{{ Str::limit($genre->description, 50) }}</small>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge bg-light text-dark">{{ $genre->sort_order }}</span>
                                </td>
                                <td>
                                    @if($genre->is_active)
                                        <span class="badge bg-success">Активен</span>
                                    @else
                                        <span class="badge bg-danger">Неактивен</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.genres.edit', $genre) }}"
                                           class="btn btn-outline-primary" title="Редактировать">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.genres.destroy', $genre) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Удалить жанр «{{ $genre->name }}»?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Удалить">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $genres->links() }}
        @endif
    </div>
</div>
@endsection
