<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Rating;
use App\Services\AchievementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request, Game $game)
    {
        $request->validate([
            'value' => 'required|integer|min:1|max:5',
        ]);

        $user = Auth::user();

        Rating::updateOrCreate(
            ['user_id' => $user->id, 'game_id' => $game->id],
            [
                'value' => $request->value,
                'user_ip' => $request->ip(),
            ]
        );

        // Пересчитать рейтинг игры
        $game->updateRating();

        // ========= ПРОВЕРКА ДОСТИЖЕНИЙ =========
        $achievementService = new AchievementService();
        $newAchievements = $achievementService->checkAndAward($user, 'rating');

        if (!empty($newAchievements)) {
            session()->flash('new_achievements', $newAchievements);
        }
        // =======================================

        return back()->with('success', 'Спасибо за оценку!');
    }
}
