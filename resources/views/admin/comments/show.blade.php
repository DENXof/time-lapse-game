@extends('layouts.admin')

@section('title', 'Просмотр комментария - Админ-панель')
@section('page-title', 'Просмотр комментария')
@section('page-subtitle', 'Детальная информация о комментарии')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-comment me-2"></i>Комментарий #{{ $comment->id }}
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6>Автор:</h6>
                    <div class="d-flex align-items-center">
                        @if($comment->user->avatar)
                            <img src="{{ Storage::url($comment->user->avatar) }}"
                                 class="rounded-circle me-2"
                                 style="width: 40px; height: 40px; object-fit: cover;">
                        @else
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2"
                                 style="width: 40px; height: 40px;">
                                <i class="fas fa-user fa-lg text-white"></i>
                            </div>
                        @endif
                        <div>
                            <strong>{{ $comment->user->name }}</strong><br>
                            <small class="text-muted">{{ $comment->user->email }}</small>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6>Игра:</h6>
                    <a href="{{ route('admin.games.edit', $comment->game_id) }}" target="_blank">
                        {{ $comment->game->title }}
                    </a>
                </div>

                <div class="mb-4">
                    <h6>Комментарий:</h6>
                    <div class="border rounded p-3 bg-light">
                        {{ nl2br(e($comment->content)) }}
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Статус:</h6>
                        @if($comment->is_approved)
                            <span class="badge bg-success">Одобрен</span>
                        @else
                            <span class="badge bg-warning">На модерации</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6>Лайки:</h6>
                        <span>{{ $comment->likes_count }}</span>
                    </div>
                </div>

                <div class="mb-4">
                    <h6>Дата создания:</h6>
                    <p>{{ $comment->created_at->format('d.m.Y H:i:s') }}</p>
                </div>

                @if($comment->updated_at != $comment->created_at)
                    <div class="mb-4">
                        <h6>Дата обновления:</h6>
                        <p>{{ $comment->updated_at->format('d.m.Y H:i:s') }}</p>
                    </div>
                @endif

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Назад
                    </a>
                    <div class="btn-group">
                        @if(!$comment->is_approved)
                            <form action="{{ route('admin.comments.approve', $comment) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-2"></i>Одобрить
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.comments.hide', $comment) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-eye-slash me-2"></i>Скрыть
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST"
                              onsubmit="return confirm('Удалить комментарий?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-2"></i>Удалить
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
