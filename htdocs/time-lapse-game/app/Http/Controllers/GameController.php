<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::with('genre')->orderBy('release_year', 'desc')->paginate(12);
        $genres = Genre::all();

        return view('games.index', compact('games', 'genres'));
    }

    public function show($slug)
    {
        $game = Game::where('slug', $slug)
            ->with('genre')
            ->firstOrFail();

        // Увеличиваем счетчик просмотров
        $game->increment('views_count');

        // Похожие игры (того же жанра)
        $relatedGames = Game::where('genre_id', $game->genre_id)
            ->where('id', '!=', $game->id)
            ->orderBy('release_year', 'desc')
            ->take(3)
            ->get();

        return view('games.show', compact('game', 'relatedGames'));
    }

    public function create()
    {
        $genres = Genre::all();
        return view('games.create', compact('genres'));
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
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('covers', 'public');
            $validated['cover_image'] = $path;
        }

        Game::create($validated);

        return redirect()->route('games.index')
            ->with('success', 'Игра успешно добавлена!');
    }

    public function edit($id)
    {
        $game = Game::findOrFail($id);
        $genres = Genre::all();

        return view('games.edit', compact('game', 'genres'));
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

        $game->update($validated);

        return redirect()->route('games.index')
            ->with('success', 'Игра успешно обновлена!');
    }

    public function destroy($id)
    {
        $game = Game::findOrFail($id);

        if ($game->cover_image) {
            Storage::disk('public')->delete($game->cover_image);
        }

        $game->delete();

        return redirect()->route('games.index')
            ->with('success', 'Игра успешно удалена!');
    }
}
