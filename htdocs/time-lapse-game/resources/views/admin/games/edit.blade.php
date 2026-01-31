@extends('layouts.admin')

@section('title', 'Редактирование игры')
@section('page-title', 'Редактирование игры')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card border-0 shadow">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>Редактировать игру
                </h5>
            </div>

            <div class="card-body">
                    <form action="{{ route('admin.games.update', $game->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Название игры *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title', $game->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Описание *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="5" required>{{ old('description', $game->description) }}</textarea>
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
                                                {{ old('genre_id', $game->genre_id) == $genre->id ? 'selected' : '' }}>
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
                                       value="{{ old('release_year', $game->release_year) }}"
                                       min="1900" max="{{ date('Y') + 5 }}" required>
                                @error('release_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="developer" class="form-label">Разработчик *</label>
                                <input type="text" class="form-control @error('developer') is-invalid @enderror"
                                       id="developer" name="developer"
                                       value="{{ old('developer', $game->developer) }}" required>
                                @error('developer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="publisher" class="form-label">Издатель</label>
                                <input type="text" class="form-control @error('publisher') is-invalid @enderror"
                                       id="publisher" name="publisher"
                                       value="{{ old('publisher', $game->publisher) }}">
                                @error('publisher')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="platform" class="form-label">Платформа *</label>
                            <input type="text" class="form-control @error('platform') is-invalid @enderror"
                                   id="platform" name="platform"
                                   value="{{ old('platform', $game->platform) }}" required>
                            @error('platform')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="cover_image" class="form-label">Обложка игры</label>

                            @if($game->cover_image)
                                <div class="mb-2">
                                    <p>Текущая обложка:</p>
                                    <img src="{{ Storage::url($game->cover_image) }}"
                                         alt="{{ $game->title }}"
                                         class="img-thumbnail" style="max-height: 200px;">
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox"
                                           id="remove_cover" name="remove_cover" value="1">
                                    <label class="form-check-label" for="remove_cover">
                                        Удалить текущую обложку
                                    </label>
                                </div>
                            @endif

                            <input type="file" class="form-control @error('cover_image') is-invalid @enderror"
                                   id="cover_image" name="cover_image" accept="image/*">
                            <small class="text-muted">Оставьте пустым, чтобы сохранить текущую обложку</small>
                            @error('cover_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('games.index') }}" class="btn btn-secondary me-md-2">Отмена</a>
                            <button type="submit" class="btn btn-warning">Обновить игру</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
