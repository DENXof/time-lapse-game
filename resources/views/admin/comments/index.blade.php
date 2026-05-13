@extends('layouts.admin')

@section('title', 'Комментарии - Админ-панель')
@section('page-title', 'Комментарии')
@section('page-subtitle', 'Управление комментариями пользователей')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-comments me-2"></i>Список комментариев
        </h5>
        <form method="GET" action="{{ route('admin.comments.index') }}" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm"
                   placeholder="Поиск..." value="{{ request('search') }}" style="width: 200px;">
            <select name="status" class="form-select form-select-sm" style="width: 130px;">
                <option value="">Все статусы</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Одобренные</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>На модерации</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-search"></i>
            </button>
            <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-undo"></i>
            </a>
        </form>
    </div>
    <div class="card-body p-0">
        @if($comments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Автор</th>
                            <th>Игра</th>
                            <th>Комментарий</th>
                            <th>Лайки</th>
                            <th>Статус</th>
                            <th>Дата</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($comments as $comment)
                            <tr>
                                <td>{{ $comment->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($comment->user->avatar)
                                            <img src="{{ Storage::url($comment->user->avatar) }}"
                                                 class="rounded-circle me-2"
                                                 style="width: 30px; height: 30px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                 style="width: 30px; height: 30px;">
                                                <i class="fas fa-user fa-sm text-white"></i>
                                            </div>
                                        @endif
                                        <span>{{ $comment->user->name }}</span>
                                    </div>
                                  </td>
                                <td>
                                    <a href="{{ route('admin.games.edit', $comment->game_id) }}" target="_blank">
                                        {{ $comment->game->title }}
                                    </a>
                                  </td>
                                <td>
                                    <div style="max-width: 300px;">
                                        {{ Str::limit($comment->content, 80) }}
                                    </div>
                                  </td>
                                <td>{{ $comment->likes_count }}</td>
                                <td>
                                    @if($comment->is_approved)
                                        <span class="badge bg-success">Одобрен</span>
                                    @else
                                        <span class="badge bg-warning">На модерации</span>
                                    @endif
                                  </td>
                                <td>{{ $comment->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.comments.show', $comment) }}"
                                           class="btn btn-outline-info" title="Просмотр">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(!$comment->is_approved)
                                            <form action="{{ route('admin.comments.approve', $comment) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success" title="Одобрить">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.comments.hide', $comment) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-warning" title="Скрыть">
                                                    <i class="fas fa-eye-slash"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.comments.destroy', $comment) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Удалить комментарий?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Удалить">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                  </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $comments->links() }}
            </div>
        @else
            <p class="text-muted text-center py-4 mb-0">Комментарии не найдены</p>
        @endif
    </div>
</div>
@endsection
