<?php
// app/Http/Controllers/Admin/GenreController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GenreController extends Controller
{
    // Список всех жанров
    public function index()
    {
        $genres = Genre::orderBy('sort_order')->orderBy('name')->paginate(10);
        return view('admin.genres.index', compact('genres'));
    }

    // Форма создания жанра
    public function create()
    {
        return view('admin.genres.create');
    }

    // Сохранение нового жанра
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:genres',
            'description' => 'nullable|string|max:500',
            'icon' => 'required|string',
        ]);

        $genre = Genre::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'icon' => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.genres.index')
            ->with('success', 'Жанр успешно создан!');
    }

    // Форма редактирования жанра
    public function edit(Genre $genre)
    {
        return view('admin.genres.edit', compact('genre'));
    }

    // Обновление жанра
    public function update(Request $request, Genre $genre)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:genres,name,' . $genre->id,
            'description' => 'nullable|string|max:500',
            'icon' => 'required|string',
        ]);

        $genre->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'icon' => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.genres.index')
            ->with('success', 'Жанр успешно обновлен!');
    }

    // Удаление жанра
    public function destroy(Genre $genre)
    {
        $genre->delete();
        return redirect()->route('admin.genres.index')
            ->with('success', 'Жанр успешно удален!');
    }
}
