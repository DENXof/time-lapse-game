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

                        <!-- Telegram -->
                        <div class="mb-3">
                            <label for="telegram" class="form-label">Telegram</label>
                            <input type="text" class="form-control @error('telegram') is-invalid @enderror"
                                   id="telegram" name="telegram" value="{{ old('telegram', $user->telegram) }}"
                                   placeholder="@username или username">
                            <small class="text-muted">Укажите ваш Telegram username (без @ или с @)</small>
                            @error('telegram')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- VK -->
                        <div class="mb-3">
                            <label for="vk" class="form-label">ВКонтакте</label>
                            <input type="text" class="form-control @error('vk') is-invalid @enderror"
                                   id="vk" name="vk" value="{{ old('vk', $user->vk) }}"
                                   placeholder="https://vk.com/username или username">
                            <small class="text-muted">Ссылка на ваш профиль VK или ID</small>
                            @error('vk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- GitHub -->
                        <div class="mb-3">
                            <label for="github" class="form-label">GitHub</label>
                            <input type="text" class="form-control @error('github') is-invalid @enderror"
                                   id="github" name="github" value="{{ old('github', $user->github) }}"
                                   placeholder="https://github.com/username или username">
                            <small class="text-muted">Ссылка на ваш GitHub профиль</small>
                            @error('github')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Steam -->
                        <div class="mb-3">
                            <label for="steam" class="form-label">Steam</label>
                            <input type="text" class="form-control @error('steam') is-invalid @enderror"
                                   id="steam" name="steam" value="{{ old('steam', $user->steam) }}"
                                   placeholder="https://steamcommunity.com/id/username или ID">
                            <small class="text-muted">Ссылка на ваш Steam профиль</small>
                            @error('steam')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Twitch -->
                        <div class="mb-3">
                            <label for="twitch" class="form-label">Twitch</label>
                            <input type="text" class="form-control @error('twitch') is-invalid @enderror"
                                   id="twitch" name="twitch" value="{{ old('twitch', $user->twitch) }}"
                                   placeholder="https://twitch.tv/username или username">
                            <small class="text-muted">Ссылка на ваш Twitch канал</small>
                            @error('twitch')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- YouTube -->
                        <div class="mb-3">
                            <label for="youtube" class="form-label">YouTube</label>
                            <input type="text" class="form-control @error('youtube') is-invalid @enderror"
                                   id="youtube" name="youtube" value="{{ old('youtube', $user->youtube) }}"
                                   placeholder="https://youtube.com/c/username или @username">
                            <small class="text-muted">Ссылка на ваш YouTube канал</small>
                            @error('youtube')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Аватар -->
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
                            <small class="text-muted">Рекомендуемый размер: 500x500px, макс. 2MB</small>
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
