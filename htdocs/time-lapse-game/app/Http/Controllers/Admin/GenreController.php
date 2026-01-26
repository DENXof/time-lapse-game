<?php
// app/Http\Controllers\Admin\GenreController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GenreController extends Controller
{
    // Список всех жанров
    public function index()
    {
        $genres = Genre::sorted()->paginate(15);
        return view('admin.genres.index', compact('genres'));
    }

    // Форма создания нового жанра
    public function create()
    {
        $colors = [
            '#3498db' => 'Синий',
            '#2ecc71' => 'Зеленый',
            '#e74c3c' => 'Красный',
            '#f39c12' => 'Оранжевый',
            '#9b59b6' => 'Фиолетовый',
            '#1abc9c' => 'Бирюзовый',
            '#34495e' => 'Темно-синий',
            '#e67e22' => 'Морковный',
            '#6c757d' => 'Серый',
            '#17a2b8' => 'Голубой',
        ];

        $icons = [
            '🎮' => 'Геймпад',
            '⚔️' => 'Мечи',
            '🛡️' => 'Щит',
            '🧙' => 'Волшебник',
            '🐉' => 'Дракон',
            '👻' => 'Призрак',
            '🚗' => 'Машина',
            '⚽' => 'Мяч',
            '🧩' => 'Пазл',
            '🎭' => 'Маска',
            '🔫' => 'Пистолет',
            '🏹' => 'Лук',
            '🔮' => 'Кристальный шар',
            '💎' => 'Алмаз',
            '👑' => 'Корона',
            '🚀' => 'Ракета',
            '🦸' => 'Супергерой',
            '🧟' => 'Зомби',
            '🤖' => 'Робот',
            '👾' => 'Инопланетянин',
        ];

        return view('admin.genres.create', compact('colors', 'icons'));
    }

    // Сохранение нового жанра
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:genres,name',
            'description' => 'nullable|string|max:500',
            'color' => 'required|string|size:7|starts_with:#',
            'icon' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        Genre::create($validated);

        return redirect()->route('admin.genres.index')
            ->with('success', 'Жанр успешно создан!');
    }

    // Форма редактирования жанра
    public function edit(Genre $genre)
    {
        $colors = [
            '#3498db' => 'Синий',
            '#2ecc71' => 'Зеленый',
            '#e74c3c' => 'Красный',
            '#f39c12' => 'Оранжевый',
            '#9b59b6' => 'Фиолетовый',
            '#1abc9c' => 'Бирюзовый',
            '#34495e' => 'Темно-синий',
            '#e67e22' => 'Морковный',
            '#6c757d' => 'Серый',
            '#17a2b8' => 'Голубой',
        ];

        $icons = [
            '🎮' => 'Геймпад',
            '⚔️' => 'Мечи',
            '🛡️' => 'Щит',
            '🧙' => 'Волшебник',
            '🐉' => 'Дракон',
            '👻' => 'Призрак',
            '🚗' => 'Машина',
            '⚽' => 'Мяч',
            '🧩' => 'Пазл',
            '🎭' => 'Маска',
            '🔫' => 'Пистолет',
            '🏹' => 'Лук',
            '🔮' => 'Кристальный шар',
            '💎' => 'Алмаз',
            '👑' => 'Корона',
            '🚀' => 'Ракета',
            '🦸' => 'Супергерой',
            '🧟' => 'Зомби',
            '🤖' => 'Робот',
            '👾' => 'Инопланетянин',
        ];

        return view('admin.genres.edit', compact('genre', 'colors', 'icons'));
    }

    // Обновление жанра
    public function update(Request $request, Genre $genre)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('genres')->ignore($genre->id)
            ],
            'description' => 'nullable|string|max:500',
            'color' => 'required|string|size:7|starts_with:#',
            'icon' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $genre->update($validated);

        return redirect()->route('admin.genres.index')
            ->with('success', 'Жанр успешно обновлен!');
    }

    // Удаление жанра
    public function destroy(Genre $genre)
    {
        // Проверяем, есть ли связанные игры
        if ($genre->games()->count() > 0) {
            return redirect()->route('admin.genres.index')
                ->with('error', 'Нельзя удалить жанр, к которому привязаны игры!');
        }

        $genre->delete();

        return redirect()->route('admin.genres.index')
            ->with('success', 'Жанр успешно удален!');
    }

    // Быстрое переключение активности
    public function toggleActive(Genre $genre)
    {
        $genre->update(['is_active' => !$genre->is_active]);

        $status = $genre->is_active ? 'активирован' : 'деактивирован';
        return redirect()->route('admin.genres.index')
            ->with('success', "Жанр {$genre->name} успешно {$status}!");
    }
}
