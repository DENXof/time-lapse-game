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
        'vk',
        'github',
        'steam',
        'twitch',
        'youtube',
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
     */
    public function favorites()
    {
        return $this->belongsToMany(Game::class, 'favorites');
    }

    /**
     * Связь "один ко многим" с таблицей оценок
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Проверяет, добавил ли пользователь игру в избранное
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
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Связь "один ко многим" с таблицей лайков комментариев
     */
    public function commentLikes()
    {
        return $this->hasMany(CommentLike::class);
    }

    /**
     * Проверка, является ли пользователь администратором
     */
    public function isAdmin()
    {
        return $this->email === 'admin@example.com';
    }

    // ============================================
    // ДОБАВЛЕННЫЕ МЕТОДЫ ДЛЯ ДОСТИЖЕНИЙ
    // ============================================

    /**
     * Связь "многие ко многим" с таблицей достижений
     */
    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
            ->withPivot('earned_at', 'is_new')
            ->withTimestamps();
    }

    /**
     * Получить общее количество очков пользователя
     */
    public function getTotalPointsAttribute()
    {
        return $this->achievements()->sum('points');
    }

    /**
     * Получить новые достижения (не просмотренные)
     */
    public function getNewAchievementsAttribute()
    {
        return $this->achievements()
            ->wherePivot('is_new', true)
            ->get();
    }

    /**
     * Отметить все достижения как просмотренные
     */
    public function markAchievementsAsSeen()
    {
        $this->achievements()
            ->wherePivot('is_new', true)
            ->each(function ($achievement) {
                $achievement->pivot->update(['is_new' => false]);
            });
    }

    // ============================================
    // ДОБАВЛЕННЫЕ МЕТОДЫ ДЛЯ ДРУЗЕЙ
    // ============================================

    /**
     * Отправленные заявки в друзья
     */
    public function sentFriendRequests()
    {
        return $this->hasMany(Friendship::class, 'user_id');
    }

    /**
     * Полученные заявки в друзья
     */
    public function receivedFriendRequests()
    {
        return $this->hasMany(Friendship::class, 'friend_id')
            ->where('status', 'pending');
    }

    /**
     * Друзья (принятые заявки) - исправлено для работы с пагинацией
     */
    public function friends()
    {
        // Получаем ID друзей из обоих направлений
        $friendIds = $this->sentFriendRequests()
            ->where('status', 'accepted')
            ->pluck('friend_id')
            ->merge(
                $this->receivedFriendRequests()
                    ->where('status', 'accepted')
                    ->pluck('user_id')
            );

        // Возвращаем query builder для пагинации
        return User::whereIn('id', $friendIds);
    }

    /**
     * Проверить, являются ли пользователи друзьями
     */
    public function isFriendWith($userId)
    {
        return Friendship::where(function ($query) use ($userId) {
            $query->where('user_id', $this->id)
                ->where('friend_id', $userId);
        })->orWhere(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->where('friend_id', $this->id);
        })->where('status', 'accepted')->exists();
    }

    /**
     * Получить ID всех друзей
     */
    public function getFriendsIds()
    {
        $ids1 = $this->sentFriendRequests()
            ->where('status', 'accepted')
            ->pluck('friend_id')
            ->toArray();

        $ids2 = $this->receivedFriendRequests()
            ->where('status', 'accepted')
            ->pluck('user_id')
            ->toArray();

        return array_merge($ids1, $ids2);
    }

    // ============================================
    // ДОБАВЛЕННЫЕ МЕТОДЫ ДЛЯ АКТИВНОСТИ
    // ============================================

    /**
     * Активность пользователя
     */
    public function activities()
    {
        return $this->hasMany(Activity::class)->orderBy('created_at', 'desc');
    }

    /**
     * Лента активности друзей
     */
    public function friendsActivities($limit = 50)
    {
        $friendsIds = $this->getFriendsIds();

        return Activity::whereIn('user_id', $friendsIds)
            ->with('user', 'target')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Создать запись активности
     */
    public function addActivity($type, $target, $data = null)
    {
        return Activity::create([
            'user_id' => $this->id,
            'type' => $type,
            'target_id' => $target->id,
            'target_type' => get_class($target),
            'data' => $data
        ]);
    }
}
