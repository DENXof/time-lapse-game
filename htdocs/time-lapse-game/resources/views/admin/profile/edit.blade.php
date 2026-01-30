@extends('layouts.admin')

@section('title', 'Редактирование профиля')
@section('page-title', 'Редактирование профиля')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-user-edit me-2"></i>Основная информация
                </h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Имя</label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email адрес</label>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email', $user->email) }}"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Роль</label>
                        <input type="text"
                               class="form-control bg-light"
                               value="{{ $user->is_admin ? 'Администратор' : 'Пользователь' }}"
                               readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Дата регистрации</label>
                        <input type="text"
                               class="form-control bg-light"
                               value="{{ $user->created_at->format('d.m.Y H:i') }}"
                               readonly>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Сохранить изменения
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-key me-2"></i>Смена пароля
                </h5>
            </div>
            <div class="card-body">
                @if($errors->password->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->password->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success') && !session()->has('errors'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('admin.profile.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Текущий пароль</label>
                        <input type="password"
                               class="form-control @error('current_password', 'password') is-invalid @enderror"
                               id="current_password"
                               name="current_password"
                               required>
                        @error('current_password', 'password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Новый пароль</label>
                        <input type="password"
                               class="form-control @error('password', 'password') is-invalid @enderror"
                               id="password"
                               name="password"
                               required>
                        @error('password', 'password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Подтверждение пароля</label>
                        <input type="password"
                               class="form-control"
                               id="password_confirmation"
                               name="password_confirmation"
                               required>
                    </div>

                    <button type="submit" class="btn btn-warning w-100">
                        <i class="fas fa-key me-1"></i>Сменить пароль
                    </button>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow mt-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Информация
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-0">
                    <h6><i class="fas fa-lightbulb me-1"></i>Рекомендации по безопасности:</h6>
                    <ul class="mb-0 ps-3">
                        <li>Используйте сложный пароль</li>
                        <li>Не используйте пароль от других сервисов</li>
                        <li>Регулярно обновляйте пароль</li>
                        <li>Никому не сообщайте свои учетные данные</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
