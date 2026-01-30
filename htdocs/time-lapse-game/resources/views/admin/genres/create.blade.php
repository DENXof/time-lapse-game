{{-- resources/views/admin/genres/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Создание жанра')
@section('page-title', 'Создание жанра')
@section('page-subtitle', 'Добавление нового игрового жанра')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-plus me-2"></i>Новый жанр
                </h5>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.genres.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Название жанра *</label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
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
                                  placeholder="Краткое описание жанра...">{{ old('description') }}</textarea>
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
                                       value="{{ old('color', '#6c757d') }}"
                                       required>
                                @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Выберите цвет для визуального выделения</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="icon" class="form-label">Иконка *</label>
                                <input type="text"
                                       class="form-control @error('icon') is-invalid @enderror"
                                       id="icon"
                                       name="icon"
                                       value="{{ old('icon', '🎮') }}"
                                       required
                                       placeholder="Вставьте эмодзи">
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Используйте эмодзи, например: 🎮, ⚔️, 👻, 🚗</small>
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
                                       value="{{ old('sort_order', 0) }}"
                                       min="0"
                                       placeholder="0">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Чем меньше число, тем выше в списке</small>
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
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Активный жанр
                                    </label>
                                </div>
                                <small class="text-muted">Неактивные жанры не отображаются на сайте</small>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i>Создать жанр
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
