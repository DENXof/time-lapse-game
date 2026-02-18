<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Genre;
use App\Models\Era;

class HomeController extends Controller
{
    public function index()
    {
        return view('home.index', [
            'featuredGames' => Game::with('genre')->latest()->take(3)->get(),   //рекомендуемые игры
            'popularGenres' => Genre::withCount('games')->orderBy('games_count', 'desc')->take(3)->get(),   //популярные жанры
            'eras' => Era::all(),   //все жанры
            'stats' => [    //статистика
                'total_games' => Game::count(),
                'total_genres' => Genre::count(),
                'total_eras' => Era::count(),
            ],
            'message' => Game::count() . ' игр доступно для просмотра!'
        ]);
    }

    public function timeline()
    {
        $eras = Era::orderBy('start_year', 'asc')->get();

        // Для каждой эпохи вручную получаем игры по годам
        foreach ($eras as $era) {
            $era->games = Game::whereBetween('release_year', [$era->start_year, $era->end_year])
                ->orderBy('release_year')
                ->get();
        }

        return view('timeline', compact('eras'));
    }
}
