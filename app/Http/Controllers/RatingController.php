<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request, Game $game)
    {
        $request->validate([
            'value' => 'required|integer|min:1|max:5',
        ]);

        $rating = $game->ratings()->updateOrCreate(
            ['user_id' => Auth::id()],
            ['value' => $request->value, 'user_ip' => $request->ip()]
        );

        $game->updateRating();

        return back()->with('success', 'Оценка сохранена');
    }
}
