{{-- resources/views/admin/genres/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Редактирование жанра')
@section('page-title', 'Редактирование жанра')
@section('page-subtitle', 'Изменение данных жанра')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>Редактирование жанра
                </h5>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.genres.update', $genre) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Название жанра *</label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name', $genre->name) }}"
                               required
                               placeholder="Например: Экшен, RPG, Стратегия">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Описание</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description"
                                  name="description"
                                  rows="3"
                                  placeholder="Краткое описание жанра...">{{ old('description', $genre->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="color" class="form-label">Цвет жанра *</label>
                                <input type="color"
                                       class="form-control form-control-color @error('color') is-invalid @enderror"
                                       id="color"
                                       name="color"
                                       value="{{ old('color', $genre->color) }}"
                                       required>
                                @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="icon" class="form-label">Иконка *</label>
                                <input type="text"
                                       class="form-control @error('icon') is-invalid @enderror"
                                       id="icon"
                                       name="icon"
                                       value="{{ old('icon', $genre->icon) }}"
                                       required>
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="mt-2">
                                    <small>Популярные иконки:</small>
                                    <div class="d-flex gap-2 mt-1">
                                        <button type="button" class="btn btn-sm btn-outline-secondary icon-btn" data-icon="🎮">🎮</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary icon-btn" data-icon="⚔️">⚔️</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary icon-btn" data-icon="👻">👻</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary icon-btn" data-icon="🚗">🚗</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary icon-btn" data-icon="⚽">⚽</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary icon-btn" data-icon="🧙">🧙</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sort_order" class="form-label">Порядок сортировки</label>
                                <input type="number"
                                       class="form-control @error('sort_order') is-invalid @enderror"
                                       id="sort_order"
                                       name="sort_order"
                                       value="{{ old('sort_order', $genre->sort_order) }}"
                                       min="0">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="is_active"
                                           name="is_active"
                                           value="1"
                                           {{ old('is_active', $genre->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Активный жанр
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i>Обновить жанр
                        </button>
                        <a href="{{ route('admin.genres.index') }}" class="btn btn-secondary ms-2">
                            <i class="fas fa-times me-1"></i>Отмена
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Выбор иконки
        $('.icon-btn').click(function() {
            const icon = $(this).data('icon');
            $('#icon').val(icon);
        });
    });
</script>
@endpush
