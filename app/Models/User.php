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
}
