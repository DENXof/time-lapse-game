код comments.blade.php:
<div class="comment-item mb-4" id="comment-{{ $comment->id }}">
    <div class="d-flex">
        <!-- Аватар -->
        <div class="flex-shrink-0">
            @if($comment->user->avatar)
                <img src="{{ Storage::url($comment->user->avatar) }}"
                     class="rounded-circle"
                     style="width: 48px; height: 48px; object-fit: cover;">
            @else
                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                     style="width: 48px; height: 48px;">
                    <i class="fas fa-user fa-2x text-white"></i>
                </div>
            @endif
        </div>

        <!-- Содержание -->
        <div class="flex-grow-1 ms-3">
            <div class="card bg-light">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <strong class="text-primary">{{ $comment->user->name }}</strong>
                            <small class="text-muted ms-2">
                                {{ $comment->created_at->diffForHumans() }}
                            </small>
                        </div>

                        @auth
                            <div class="btn-group btn-group-sm">
                                @if(Auth::id() === $comment->user_id)
                                    <button class="btn btn-outline-secondary edit-comment" data-id="{{ $comment->id }}" title="Редактировать">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger delete-comment" data-id="{{ $comment->id }}" title="Удалить">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                                <button class="btn btn-outline-primary reply-btn" data-id="{{ $comment->id }}" title="Ответить">
                                    <i class="fas fa-reply"></i>
                                </button>
                            </div>
                        @endauth
                    </div>

                    <!-- Содержимое комментария -->
                    <div class="comment-content">
                        <p class="mb-0">{{ nl2br(e($comment->content)) }}</p>
                    </div>

                    <!-- Кнопка лайка -->
                    <div class="mt-2">
                        <button class="btn btn-sm btn-link like-btn text-decoration-none p-0" data-id="{{ $comment->id }}">
                            <i class="fas fa-heart {{ $comment->isLikedByUser() ? 'text-danger' : 'text-secondary' }} me-1"></i>
                            <span class="likes-count">{{ $comment->likes_count }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Форма ответа (скрытая) -->
            <div class="reply-form mt-3 ms-5" id="reply-form-{{ $comment->id }}" style="display: none;">
                <form action="{{ route('comments.reply', $comment) }}" method="POST" class="reply-form-ajax">
                    @csrf
                    <div class="input-group">
                        <textarea name="content" class="form-control" rows="2"
                                  placeholder="Ответить {{ $comment->user->name }}..." required></textarea>
                        <button type="submit" class="btn btn-primary">Ответить</button>
                    </div>
                </form>
            </div>

            <!-- Форма редактирования (скрытая) -->
            @auth
                @if(Auth::id() === $comment->user_id)
                    <div class="edit-form mt-3 ms-5" id="edit-form-{{ $comment->id }}" style="display: none;">
                        <form action="{{ route('comments.update', $comment) }}" method="POST" class="edit-form-ajax">
                            @csrf
                            @method('PUT')
                            <textarea name="content" class="form-control" rows="3">{{ $comment->content }}</textarea>
                            <div class="mt-2">
                                <button type="submit" class="btn btn-sm btn-primary">Сохранить</button>
                                <button type="button" class="btn btn-sm btn-secondary cancel-edit">Отмена</button>
                            </div>
                        </form>
                    </div>
                @endif
            @endauth

            <!-- Ответы на комментарий -->
            @if($comment->replies && $comment->replies->count() > 0)
                <div class="replies-list ms-5 mt-3">
                    @foreach($comment->replies as $reply)
                        @include('comments.partials.comment', ['comment' => $reply])
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
