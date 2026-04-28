<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    /**
     * Поля, доступные для массового заполнения
     */
    protected $fillable = [
        'game_id',   // ID игры (внешний ключ)
        'user_id',   // ID пользователя (внешний ключ, может быть null для гостей)
        'user_ip',   // IP-адрес пользователя (для гостей)
        'value',     // Оценка (1-5)
    ];

    /**
     * Преобразование типов
     */
    protected $casts = [
        'value' => 'integer',
    ];

    // ============================================
    // СВЯЗИ С ДРУГИМИ МОДЕЛЯМИ
    // ============================================

    /**
     * Связь с моделью Game (многие к одному)
     * Оценка принадлежит одной игре
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Связь с моделью User (многие к одному)
     * Оценка принадлежит одному пользователю (может быть null для гостей)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
