<?php

namespace App\Services;

use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class ActivityService
{
    /**
     * Логирование действия пользователя
     */
    public static function log($type, $target, $data = null)
    {
        if (Auth::check()) {
            Auth::user()->addActivity($type, $target, $data);
        }
    }

    /**
     * Получить форматированное сообщение активности
     */
    public static function getMessage(Activity $activity)
    {
        $user = $activity->user;
        $userName = $user->name;

        return match ($activity->type) {
            'rating' => "{$userName} оценил(а) игру <strong>{$activity->target->title}</strong> на {$activity->data['rating']} ⭐",
            'favorite' => "{$userName} добавил(а) игру <strong>{$activity->target->title}</strong> в избранное",
            'comment' => "{$userName} оставил(а) комментарий к игре <strong>{$activity->target->game->title}</strong>",
            'friend_request' => "{$userName} отправил(а) вам заявку в друзья",
            'friend_accepted' => "{$userName} принял(а) вашу заявку в друзья",
            default => "{$userName} был(а) активен",
        };
    }
}
