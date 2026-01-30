@extends('layouts.admin')

@section('title', 'Добавление игры')
@section('page-title', 'Добавление игры')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card border-0 shadow">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-plus me-2"></i>Добавить игру
                </h5>
            </div>

            <div class="card-body">
                    <form action="{{ route('admin.games.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Название игры *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Описание *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="genre_id" class="form-label">Жанр *</label>
                                <select class="form-select @error('genre_id') is-invalid @enderror"
                                        id="genre_id" name="genre_id" required>
                                    <option value="">Выберите жанр</option>
                                    @foreach($genres as $genre)
                                        <option value="{{ $genre->id }}"
                                                {{ old('genre_id') == $genre->id ? 'selected' : '' }}>
                                            {{ $genre->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('genre_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="release_year" class="form-label">Год выхода *</label>
                                <input type="number" class="form-control @error('release_year') is-invalid @enderror"
                                       id="release_year" name="release_year"
                                       value="{{ old('release_year', date('Y')) }}" min="1900" max="{{ date('Y') + 5 }}" required>
                                @error('release_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="developer" class="form-label">Разработчик *</label>
                                <input type="text" class="form-control @error('developer') is-invalid @enderror"
                                       id="developer" name="developer" value="{{ old('developer') }}" required>
                                @error('developer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="publisher" class="form-label">Издатель</label>
                                <input type="text" class="form-control @error('publisher') is-invalid @enderror"
                                       id="publisher" name="publisher" value="{{ old('publisher') }}">
                                @error('publisher')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="platform" class="form-label">Платформа *</label>
                            <input type="text" class="form-control @error('platform') is-invalid @enderror"
                                   id="platform" name="platform"
                                   value="{{ old('platform', 'PC') }}" required>
                            <small class="text-muted">Например: PC, PlayStation 5, Xbox Series X, Nintendo Switch</small>
                            @error('platform')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="cover_image" class="form-label">Обложка игры</label>
                            <input type="file" class="form-control @error('cover_image') is-invalid @enderror"
                                   id="cover_image" name="cover_image" accept="image/*">
                            <small class="text-muted">Рекомендуемый размер: 600x800px, макс. размер: 2MB</small>
                            @error('cover_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('games.index') }}" class="btn btn-secondary me-md-2">Отмена</a>
                            <button type="submit" class="btn btn-primary">Добавить игру</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
