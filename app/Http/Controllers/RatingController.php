<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Rating;
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

        // ========= ИСПРАВЛЕНО: добавили user_ip =========
        Rating::updateOrCreate(
            ['user_id' => $user->id, 'game_id' => $game->id],
            [
                'value' => $request->value,
                'user_ip' => $request->ip(),  // Добавляем IP-адрес пользователя
            ]
        );
        // =============================================

        // Пересчитать рейтинг игры
        $game->updateRating();

        return back()->with('success', 'Спасибо за оценку!');
    }
}
