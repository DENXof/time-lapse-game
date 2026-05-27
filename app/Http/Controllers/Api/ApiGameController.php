<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Genre;
use App\Traits\SeoTrait;
use App\Services\YouTubeService;
use App\Services\SteamService;
use App\Http\Resources\GameResource;
use App\Http\Resources\GameCollection;
use App\Http\Resources\GenreResource;
use Illuminate\Http\Request;

class ApiGameController extends Controller
{
    /**
     * Список игр с фильтрацией и пагинацией
     */
    public function index(Request $request)
    {
        $query = Game::with('genre');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('genre_id')) {
            $query->where('genre_id', $request->genre_id);
        }

        if ($request->filled('decade')) {
            $startYear = $request->decade;
            $endYear = $startYear + 9;
            $query->whereBetween('release_year', [$startYear, $endYear]);
        }

        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'newest':
                $query->orderBy('release_year', 'desc');
                break;
            case 'oldest':
                $query->orderBy('release_year', 'asc');
                break;
            case 'rating':
                $query->orderBy('rating_avg', 'desc');
                break;
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            default:
                $query->orderBy('release_year', 'desc');
        }

        $games = $query->paginate($request->get('per_page', 20));

        return new GameCollection($games);
    }

    /**
     * Детальная информация об игре по ID
     */
    public function show($id)
    {
        $game = Game::with([
            'genre',
            'comments' => function ($q) {
                $q->whereNull('parent_id')->with('user', 'replies.user');
            }
        ])->findOrFail($id);

        return new GameResource($game);
    }

    /**
     * Детальная информация об игре по slug
     */
    public function showBySlug($slug)
    {
        $game = Game::with([
            'genre',
            'comments' => function ($q) {
                $q->whereNull('parent_id')->with('user', 'replies.user');
            }
        ])->where('slug', $slug)->firstOrFail();

        return new GameResource($game);
    }

    /**
     * Топ-100 игр по рейтингу
     */
    public function top(Request $request)
    {
        $games = Game::with('genre')
            ->where('rating_count', '>', 0)
            ->orderBy('rating_avg', 'desc')
            ->orderBy('rating_count', 'desc')
            ->paginate($request->get('per_page', 20));

        return new GameCollection($games);
    }

    /**
     * Новинки (игры последних 2 лет)
     */
    public function newReleases(Request $request)
    {
        $currentYear = date('Y');
        $twoYearsAgo = $currentYear - 2;

        $games = Game::with('genre')
            ->where('release_year', '>=', $twoYearsAgo)
            ->orderBy('release_year', 'desc')
            ->paginate($request->get('per_page', 20));

        return new GameCollection($games);
    }

    /**
     * Случайная игра
     */
    public function randomGame()
    {
        $game = Game::inRandomOrder()->first();

        if (!$game) {
            return response()->json(['error' => 'No games found'], 404);
        }

        return new GameResource($game);
    }

    /**
     * Список всех жанров
     */
    public function genres()
    {
        $genres = Genre::withCount('games')->get();
        return GenreResource::collection($genres);
    }

    /**
     * Игры по жанру
     */
    public function gamesByGenre($id, Request $request)
    {
        $genre = Genre::findOrFail($id);
        $games = $genre->games()->paginate($request->get('per_page', 20));

        return new GameCollection($games);
    }
}
