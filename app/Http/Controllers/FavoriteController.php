<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggle(Game $game)
    {
        $user = Auth::user();

        if ($user->favorites()->where('game_id', $game->id)->exists()) {
            $user->favorites()->detach($game->id);
            $favorited = false;
        } else {
            $user->favorites()->attach($game->id);
            $favorited = true;
        }

        if (request()->wantsJson()) {
            return response()->json(['favorited' => $favorited]);
        }

        return back();
    }
}
