<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Genre;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Главная страница админки
    public function index()
    {
        $stats = [
            'total_games' => Game::count(),
            'total_genres' => Genre::count(),
            'recent_games' => Game::with('genre')->latest()->take(5)->get(),
            'popular_genres' => Genre::withCount('games')->orderBy('games_count', 'desc')->take(5)->get(),
            'total_views' => Game::sum('views_count') ?? 0,
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
