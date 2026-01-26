@extends('layouts.app')

@section('title', 'Вход в админ-панель - TimeLapse Games')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-lock me-2"></i>Вход в админ-панель
                    </h3>
                    <p class="mb-0 mt-2 opacity-75">TimeLapse Games - Панель управления</p>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.login.post') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email адрес</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       required
                                       autofocus
                                       placeholder="admin@timelapse.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Пароль</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-key"></i>
                                </span>
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       required
                                       placeholder="••••••">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Запомнить меня</label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Войти
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-home me-2"></i>На главную
                            </a>
                        </div>
                    </form>
                </div>

                <div class="card-footer text-center text-muted py-3">
                    <small>
                        <i class="fas fa-info-circle me-1"></i>
                        Доступ только для администраторов системы
                    </small>
                </div>
            </div>

            <!-- Тестовые данные -->
            <div class="alert alert-info mt-4">
                <h6><i class="fas fa-key me-2"></i>Тестовые данные для входа:</h6>
                <p class="mb-1"><strong>Email:</strong> admin@timelapse.com</p>
                <p class="mb-0"><strong>Пароль:</strong> admin123</p>
            </div>
        </div>
    </div>
</div>
@endsection
