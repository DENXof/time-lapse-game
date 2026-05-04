<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_id',
        'parent_id',
        'content',
        'likes_count',
        'is_approved'
    ];

    protected $with = ['user']; // Автоматически подгружаем автора

    // Связи
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->latest();
    }

    public function likes()
    {
        return $this->hasMany(CommentLike::class);
    }

    // Проверка, лайкнул ли пользователь
    public function isLikedByUser($userId = null)
    {
        if (!$userId && auth()->check()) {
            $userId = auth()->id();
        }
        return $this->likes()->where('user_id', $userId)->exists();
    }

    // Увеличить счётчик лайков
    public function incrementLikesCount()
    {
        $this->increment('likes_count');
    }

    // Уменьшить счётчик лайков
    public function decrementLikesCount()
    {
        $this->decrement('likes_count');
    }
}
