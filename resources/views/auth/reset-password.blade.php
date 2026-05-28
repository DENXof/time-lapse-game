@extends('layouts.app')

@section('title', 'Сброс пароля - TimeLapse Games')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-lock me-2"></i>
                        Сброс пароля
                    </h3>
                    <p class="mb-0 mt-2 opacity-75">Придумайте новый пароль</p>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            @foreach($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label for="email" class="form-label">Email адрес</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email"
                                       class="form-control"
                                       id="email" name="email" value="{{ old('email') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Новый пароль</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-key"></i>
                                </span>
                                <input type="password"
                                       class="form-control"
                                       id="password" name="password" required>
                            </div>
                            <small class="text-muted">Минимум 6 символов</small>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Подтверждение пароля</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-check"></i>
                                </span>
                                <input type="password"
                                       class="form-control"
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2">
                            <i class="fas fa-save me-2"></i>Сохранить новый пароль
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
