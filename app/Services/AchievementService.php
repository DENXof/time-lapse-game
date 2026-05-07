<?php

namespace App\Services;

use App\Models\User;
use App\Models\Achievement;
use App\Models\UserAchievement;
use Illuminate\Support\Facades\DB;

class AchievementService
{
    /**
     * Проверить и выдать достижения пользователю
     */
    public function checkAndAward(User $user, string $type, int $currentCount = null)
    {
        // Если количество не передано - вычисляем
        if ($currentCount === null) {
            $currentCount = $this->getUserCountForType($user, $type);
        }

        // Находим все достижения этого типа, которые ещё не получены
        $achievements = Achievement::where('type', $type)
            ->where('required_count', '<=', $currentCount)
            ->get();

        $newAchievements = [];

        foreach ($achievements as $achievement) {
            // Проверяем, не получил ли пользователь уже это достижение
            $hasAchievement = UserAchievement::where('user_id', $user->id)
                ->where('achievement_id', $achievement->id)
                ->exists();

            if (!$hasAchievement) {
                // Выдаём достижение
                UserAchievement::create([
                    'user_id' => $user->id,
                    'achievement_id' => $achievement->id,
                    'earned_at' => now(),
                    'is_new' => true
                ]);

                $newAchievements[] = $achievement;
            }
        }

        return $newAchievements;
    }

    /**
     * Получить количество действий пользователя по типу
     */
    private function getUserCountForType(User $user, string $type)
    {
        return match ($type) {
            'rating' => $user->ratings()->count(),
            'favorite' => $user->favorites()->count(),
            'comment' => $user->comments()->count(),
            'likes' => $user->comments()->sum('likes_count'),
            default => 0,
        };
    }

    /**
     * Проверить все типы достижений
     */
    public function checkAll(User $user)
    {
        $types = ['rating', 'favorite', 'comment', 'likes'];
        $allNew = [];

        foreach ($types as $type) {
            $new = $this->checkAndAward($user, $type);
            $allNew = array_merge($allNew, $new);
        }

        return $allNew;
    }
}
