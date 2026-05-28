@extends('layouts.app')

@section('title', 'Восстановление пароля - TimeLapse Games')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-warning text-dark text-center py-4">
                    <h3 class="mb-0">
                        <i class="fas fa-key me-2"></i>
                        Восстановление пароля
                    </h3>
                    <p class="mb-0 mt-2 opacity-75">Введите email, чтобы получить ссылку для сброса пароля</p>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            @foreach($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email адрес</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email"
                                       class="form-control"
                                       id="email" name="email" value="{{ old('email') }}" required autofocus>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning w-100 py-2">
                            <i class="fas fa-paper-plane me-2"></i>Отправить ссылку
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" class="text-muted small">
                            <i class="fas fa-arrow-left me-1"></i>Вернуться к входу
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
