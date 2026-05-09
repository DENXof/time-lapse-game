<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Services\AchievementService;
use App\Services\ActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Auth::user()->favorites()->paginate(12);
        return view('favorites.index', compact('favorites'));
    }

    public function toggle(Game $game)
    {
        $user = Auth::user();
        $isAdding = false;

        if ($user->favorites()->where('game_id', $game->id)->exists()) {
            $user->favorites()->detach($game->id);
            $message = 'Игра удалена из избранного';
        } else {
            $user->favorites()->attach($game->id);
            $message = 'Игра добавлена в избранное';
            $isAdding = true;
        }

        // ========= ЛОГИРОВАНИЕ АКТИВНОСТИ =========
        if ($isAdding) {
            ActivityService::log('favorite', $game);
        }
        // ==========================================

        // ========= ПРОВЕРКА ДОСТИЖЕНИЙ =========
        $achievementService = new AchievementService();
        $newAchievements = $achievementService->checkAndAward($user, 'favorite');

        if (!empty($newAchievements)) {
            session()->flash('new_achievements', $newAchievements);
        }
        // =======================================

        return back()->with('success', $message);
    }
}
