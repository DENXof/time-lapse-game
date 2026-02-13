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
            'featuredGames' => Game::with('genre')->latest()->take(3)->get(),
            'popularGenres' => Genre::withCount('games')->orderBy('games_count', 'desc')->take(3)->get(),
            'eras' => Era::all(),
            'stats' => [
                'total_games' => Game::count(),
                'total_genres' => Genre::count(),
                'total_eras' => Era::count(),
            ],
            'message' => Game::count() . ' игр доступно для просмотра!'
        ]);
    }

    public function timeline()
    {
        // Получаем эпохи из базы данных с привязанными играми
        $eras = Era::with('games')->orderBy('start_year', 'asc')->get();

        // Преобразуем данные в формат, ожидаемый представлением (если нужно)
        // Но лучше обновить представление для работы с объектами Eloquent
        return view('timeline', compact('eras'));
    }
}
