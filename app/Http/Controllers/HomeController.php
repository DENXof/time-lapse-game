<?php
namespace App\Http\Controllers;

use App\Traits\SeoTrait;
use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Genre;
use App\Models\Era;

class HomeController extends Controller
{
    use SeoTrait;

    public function index()
    {
        // SEO мета-теги для главной страницы
        $this->setMeta(
            title: 'TimeLapse Games - История компьютерных игр',
            description: 'Крупнейшая база данных компьютерных игр: описание, рейтинги, обзоры, новости. Погрузитесь в историю игровой индустрии!',
            keywords: 'игры, компьютерные игры, история игр, видеоигры, игровая индустрия, ретро игры'
        );

        return view('home.index', [
            'featuredGames' => Game::with('genre')->orderBy('release_year', 'desc')->take(3)->get(),
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
        // SEO мета-теги для страницы таймлайна
        $this->setMeta(
            title: 'Таймлайн истории игр - TimeLapse Games',
            description: 'Путешествие по истории компьютерных игр: от первых Pong до современных AAA-проектов. Хронология развития игровой индустрии.',
            keywords: 'таймлайн игр, история игр, хронология игр, развитие игр'
        );

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
