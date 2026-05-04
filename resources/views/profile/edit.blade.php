@extends('layouts.app')

@section('title', 'Редактирование профиля - TimeLapse Games')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Редактирование профиля</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Имя</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">О себе</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror"
                                      id="bio" name="bio" rows="4">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="telegram" class="form-label">Telegram</label>
                            <input type="text" class="form-control @error('telegram') is-invalid @enderror"
                                   id="telegram" name="telegram" value="{{ old('telegram', $user->telegram) }}">
                            @error('telegram')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="discord" class="form-label">Discord</label>
                            <input type="text" class="form-control @error('discord') is-invalid @enderror"
                                   id="discord" name="discord" value="{{ old('discord', $user->discord) }}">
                            @error('discord')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="avatar" class="form-label">Аватар</label>
                            @if($user->avatar)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($user->avatar) }}"
                                         style="width: 100px; height: 100px; object-fit: cover;" class="rounded-circle">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('avatar') is-invalid @enderror"
                                   id="avatar" name="avatar" accept="image/*">
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('profile.index') }}" class="btn btn-secondary">Отмена</a>
                            <button type="submit" class="btn btn-primary">Сохранить</button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <a href="{{ route('profile.change-password') }}" class="btn btn-outline-warning">
                            <i class="fas fa-key me-2"></i>Сменить пароль
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
