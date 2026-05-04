<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ============================================
    // ДОБАВЛЕННЫЕ МЕТОДЫ ДЛЯ ИЗБРАННОГО И ОЦЕНОК
    // ============================================

    /**
     * Связь "многие ко многим" с таблицей избранного
     * Позволяет получить все игры, которые пользователь добавил в избранное
     */
    public function favorites()
    {
        return $this->belongsToMany(Game::class, 'favorites');
    }

    /**
     * Связь "один ко многим" с таблицей оценок
     * Позволяет получить все оценки, которые поставил пользователь
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Проверяет, добавил ли пользователь игру в избранное
     *
     * @param int $gameId ID игры
     * @return bool true если игра в избранном, false если нет
     */
    public function hasFavorited($gameId)
    {
        return $this->favorites()->where('game_id', $gameId)->exists();
    }

    // ============================================
    // ДОБАВЛЕННЫЕ МЕТОДЫ ДЛЯ КОММЕНТАРИЕВ
    // ============================================

    /**
     * Связь "один ко многим" с таблицей комментариев
     * Позволяет получить все комментарии пользователя
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Связь "один ко многим" с таблицей лайков комментариев
     * Позволяет получить все лайки пользователя
     */
    public function commentLikes()
    {
        return $this->hasMany(CommentLike::class);
    }

    /**
     * Проверка, является ли пользователь администратором
     * (если у вас есть поле role, иначе замените на свою логику)
     */
    public function isAdmin()
    {
        // Если есть поле role в таблице users
        // return $this->role === 'admin';

        // Или проверка по email (временное решение)
        return $this->email === 'admin@example.com';
    }
}
