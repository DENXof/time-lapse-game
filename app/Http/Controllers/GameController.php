<?php
// КОНТРОЛЛЕР УПРАВЛЕНИЯ ИГРАМИ
namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Genre;
use App\Traits\SeoTrait;
use App\Services\YouTubeService;
use App\Services\SteamService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GameController extends Controller
{
    use SeoTrait;

    // ============================================
    // ПУБЛИЧНЫЕ МЕТОДЫ
    // ============================================

    public function index(Request $request)
    {
        $query = Game::with('genre');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', '%' . $search . '%');
        }

        if ($request->filled('genre')) {
            $query->where('genre_id', $request->genre);
        }

        if ($request->filled('decade')) {
            $startYear = $request->decade;
            $endYear = $startYear + 9;
            $query->whereBetween('release_year', [$startYear, $endYear]);
        }

        if ($request->filled('platform')) {
            $query->where('platform', 'like', '%' . $request->platform . '%');
        }

        // ФИЛЬТР ПО ВОЗРАСТНОМУ РЕЙТИНГУ
        if ($request->filled('age_rating')) {
            $query->where('age_rating', $request->age_rating);
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
            case 'az':
                $query->orderBy('title', 'asc');
                break;
            case 'za':
                $query->orderBy('title', 'desc');
                break;
            default:
                $query->orderBy('release_year', 'desc');
        }

        $games = $query->paginate(12)->withQueryString();
        $genres = Genre::orderBy('name')->get();

        $this->setMeta(
            title: 'Все игры - TimeLapse Games',
            description: 'Полный каталог компьютерных игр. Поиск по жанрам, годам, платформам.',
            keywords: 'каталог игр, компьютерные игры, видеоигры, поиск игр'
        );

        return view('games.index', compact('games', 'genres'));
    }

    // ДЕТАЛЬНАЯ СТРАНИЦА
    public function show($slug)
    {
        $game = Game::where('slug', $slug)
            ->with('genre')
            ->firstOrFail();

        $game->increment('views_count');

        $relatedGames = Game::where('genre_id', $game->genre_id)
            ->where('id', '!=', $game->id)
            ->orderBy('release_year', 'desc')
            ->take(3)
            ->get();

        // YouTube трейлер
        try {
            $youtubeService = new YouTubeService();
            $trailer = $youtubeService->searchTrailer($game->title, $game->release_year);
        } catch (\Exception $e) {
            $trailer = null;
        }

        $this->setMeta(
            title: $game->title . ' - TimeLapse Games',
            description: Str::limit(strip_tags($game->description), 160),
            keywords: $game->title . ', ' . ($game->genre->name ?? 'игра') . ', ' . $game->release_year,
            image: $game->cover_image ? Storage::url($game->cover_image) : null
        );

        return view('games.show', compact('game', 'relatedGames', 'trailer'));
    }

    // ========= ДОБАВЛЕННЫЕ МЕТОДЫ =========

    public function top()
    {
        $games = Game::with('genre')
            ->where('rating_count', '>', 0)
            ->orderBy('rating_avg', 'desc')
            ->orderBy('rating_count', 'desc')
            ->paginate(20);

        $this->setMeta(
            title: 'Топ-100 игр - TimeLapse Games',
            description: 'Самые высокооценённые игры по версии пользователей.',
            keywords: 'топ игр, лучшие игры, рейтинг игр'
        );

        return view('games.top', compact('games'));
    }

    public function newReleases()
    {
        $currentYear = date('Y');
        $twoYearsAgo = $currentYear - 2;

        $games = Game::with('genre')
            ->where('release_year', '>=', $twoYearsAgo)
            ->orderBy('release_year', 'desc')
            ->paginate(20);

        $this->setMeta(
            title: 'Новинки - TimeLapse Games',
            description: 'Самые свежие релизы игр за последние 2 года.',
            keywords: 'новые игры, новинки игр, релизы игр'
        );

        return view('games.new', compact('games'));
    }

    public function randomGame()
    {
        $game = Game::inRandomOrder()->first();

        if (!$game) {
            return redirect()->route('games.index')->with('error', 'Игры не найдены');
        }

        return redirect()->route('games.show', $game->slug);
    }

    public function calendar()
    {
        $gamesByDecade = Game::with('genre')
            ->orderBy('release_year', 'desc')
            ->get()
            ->groupBy(function ($game) {
                return floor($game->release_year / 10) * 10;
            });

        $decades = $gamesByDecade->keys()->sortDesc();

        $this->setMeta(
            title: 'Календарь релизов игр - TimeLapse Games',
            description: 'Хронология выхода игр по годам и десятилетиям.',
            keywords: 'календарь релизов, хронология игр, история игр'
        );

        return view('games.calendar', compact('gamesByDecade', 'decades'));
    }

    // ============================================
    // АДМИНСКИЕ МЕТОДЫ
    // ============================================

    public function adminIndex(Request $request)
    {
        $query = Game::with('genre');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('title', 'LIKE', "%{$search}%");
        }

        if ($request->has('year') && $request->year != '') {
            $query->where('release_year', $request->year);
        }

        $games = $query->orderBy('created_at', 'desc')->paginate(15);

        if ($request->has('search') || $request->has('year')) {
            $games->appends($request->all());
        }

        return view('admin.games.index', compact('games'));
    }

    public function create()
    {
        $genres = Genre::all();
        return view('admin.games.create', compact('genres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:games',
            'description' => 'required|string',
            'genre_id' => 'required|exists:genres,id',
            'release_year' => 'required|integer|min:1900|max:' . (date('Y') + 5),
            'developer' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'platform' => 'required|string|max:255',
            'age_rating' => 'required|in:0+,6+,12+,16+,18+',
            'steam_app_id' => 'nullable|string|max:50',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('covers', 'public');
            $validated['cover_image'] = $path;
        }

        Game::create($validated);

        return redirect()->route('admin.games.index')
            ->with('success', 'Игра успешно добавлена!');
    }

    public function edit($id)
    {
        $game = Game::findOrFail($id);
        $genres = Genre::all();
        return view('admin.games.edit', compact('game', 'genres'));
    }

    public function update(Request $request, $id)
    {
        $game = Game::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:games,title,' . $game->id,
            'description' => 'required|string',
            'genre_id' => 'required|exists:genres,id',
            'release_year' => 'required|integer|min:1900|max:' . (date('Y') + 5),
            'developer' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'platform' => 'required|string|max:255',
            'age_rating' => 'required|in:0+,6+,12+,16+,18+',
            'steam_app_id' => 'nullable|string|max:50',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($game->title !== $validated['title']) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('cover_image')) {
            if ($game->cover_image) {
                Storage::disk('public')->delete($game->cover_image);
            }
            $path = $request->file('cover_image')->store('covers', 'public');
            $validated['cover_image'] = $path;
        }

        if ($request->has('remove_cover') && $request->remove_cover == 1) {
            if ($game->cover_image) {
                Storage::disk('public')->delete($game->cover_image);
            }
            $validated['cover_image'] = null;
        }

        $game->update($validated);

        return redirect()->route('admin.games.index')
            ->with('success', 'Игра успешно обновлена!');
    }

    public function destroy($id)
    {
        $game = Game::findOrFail($id);

        if ($game->cover_image) {
            Storage::disk('public')->delete($game->cover_image);
        }

        $game->delete();

        return redirect()->route('admin.games.index')
            ->with('success', 'Игра успешно удалена!');
    }

    // ========= МЕТОД: ПОИСК STEAM ID ДЛЯ ОДНОЙ ИГРЫ =========
    public function findSteamId(Game $game)
    {
        $steamService = new SteamService();

        $result = $steamService->searchAppId($game->title);

        if ($result) {
            $game->steam_app_id = $result['app_id'];
            $game->save();

            return redirect()->route('admin.games.edit', $game->id)
                ->with('success', "Steam ID найден: {$result['app_id']}");
        }

        return redirect()->route('admin.games.edit', $game->id)
            ->with('error', 'Игра не найдена в Steam. Укажите Steam App ID вручную.');
    }

    // ========= МЕТОД: ПОИСК STEAM ID ДЛЯ ВСЕХ ИГР =========
    public function findMissingSteamIds()
    {
        set_time_limit(0);

        $games = Game::whereNull('steam_app_id')
            ->orWhere('steam_app_id', '')
            ->get();

        if ($games->isEmpty()) {
            return redirect()->route('admin.games.index')
                ->with('info', 'Нет игр без Steam ID');
        }

        $updated = 0;
        $notFound = 0;
        $steamService = new SteamService();

        foreach ($games as $game) {
            try {
                $result = $steamService->searchAppId($game->title);

                if ($result) {
                    $game->steam_app_id = $result['app_id'];
                    $game->save();
                    $updated++;
                } else {
                    $notFound++;
                }
            } catch (\Exception $e) {
                $notFound++;
                Log::error('Steam search failed for ' . $game->title . ': ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.games.index')
            ->with('success', "Найдено Steam ID: {$updated}, не найдено: {$notFound}");
    }
}
