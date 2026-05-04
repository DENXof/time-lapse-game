<?php
// КОНТРОЛЛЕР УПРАВЛЕНИЯ ИГРАМИ
namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
    // ============================================
    // ПУБЛИЧНЫЕ МЕТОДЫ (доступны всем посетителям сайта)
    // ============================================

    //ПОКАЗАТЬ ВСЕ ИГРЫ (С ФИЛЬТРАЦИЕЙ, ПОИСКОМ И СОРТИРОВКОЙ)
    public function index(Request $request)
    {
        $query = Game::with('genre');

        // ПОИСК ПО НАЗВАНИЮ
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', '%' . $search . '%');
        }

        // ФИЛЬТР ПО ЖАНРУ
        if ($request->filled('genre')) {
            $query->where('genre_id', $request->genre);
        }

        // ФИЛЬТР ПО ДЕСЯТИЛЕТИЮ
        if ($request->filled('decade')) {
            $startYear = $request->decade;
            $endYear = $startYear + 9;
            $query->whereBetween('release_year', [$startYear, $endYear]);
        }

        // ФИЛЬТР ПО ПЛАТФОРМЕ
        if ($request->filled('platform')) {
            $query->where('platform', 'like', '%' . $request->platform . '%');
        }

        // СОРТИРОВКА
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

        return view('games.show', compact('game', 'relatedGames'));
    }
    // ============================================
    // АДМИНСКИЕ МЕТОДЫ (только для админов)
    // ============================================
    //СПИСОК ИГР С ПОИСКОМ
    public function adminIndex(Request $request)
    {
        // Начинаем запрос к БД, сразу подгружаем жанры
        $query = Game::with('genre');
        // ЕСЛИ ЕСТЬ ПОИСК (пользователь ввел текст в поиск)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            // Ищем игры, где название похоже на то, что ввел пользователь
            // LIKE "%текст%" - ищем везде, где встречается этот текст
            $query->where('title', 'LIKE', "%{$search}%");
        }
        // ЕСЛИ ЕСТЬ ФИЛЬТР ПО ГОДУ
        if ($request->has('year') && $request->year != '') {
            // Показываем игры только этого года
            $query->where('release_year', $request->year);
        }
        // Получаем результат
        $games = $query->orderBy('created_at', 'desc')  // Сортируем по дате добавления
            ->paginate(15); // По 15 игр на странице
        // Сохраняем параметры поиска в пагинации (чтобы при переходе на стр.2 поиск не сбрасывался)
        if ($request->has('search') || $request->has('year')) {
            $games->appends($request->all());
        }
        // Отправляем в шаблон admin/games/index.blade.php
        return view('admin.games.index', compact('games'));
    }
    //ФОРМА ДОБАВЛЕНИЯ НОВОЙ ИГРЫ
    public function create()
    {
        // Получаем все жанры для выпадающего списка
        $genres = Genre::all();
        // Показываем форму создания игры
        return view('admin.games.create', compact('genres'));
    }
    //СОХРАНИТЬ НОВУЮ ИГРУ
    public function store(Request $request)
    {
        // ПРОВЕРЯЕМ, ЧТО ПОЛЯ ЗАПОЛНЕНЫ ПРАВИЛЬНО
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:games',  // Название: обязательно, строка, макс 255 символов, уникальное
            'description' => 'required|string', // Описание: обязательно
            'genre_id' => 'required|exists:genres,id',  // Жанр: обязательно, должен быть в таблице genres
            'release_year' => 'required|integer|min:1900|max:' . (date('Y') + 5), // Год: от 1900 до текущий год+5
            'developer' => 'required|string|max:255',   // Разработчик: обязательно
            'publisher' => 'nullable|string|max:255',   // Издатель: необязательно
            'platform' => 'required|string|max:255',    // Платформа: обязательно
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',  // Обложка: картинка до 2MB
        ]);
        // СОЗДАЕМ SLUG ИЗ НАЗВАНИЯ
        $validated['slug'] = Str::slug($validated['title']);
        // ЕСЛИ ЗАГРУЗИЛИ КАРТИНКУ
        if ($request->hasFile('cover_image')) {
            // Сохраняем картинку в папку public/covers
            $path = $request->file('cover_image')->store('covers', 'public');
            // Добавляем путь к картинке в данные для сохранения
            $validated['cover_image'] = $path;
        }
        // СОЗДАЕМ НОВУЮ ЗАПИСЬ В ТАБЛИЦЕ games
        Game::create($validated);
        // Перенаправляем обратно в админку с сообщением об успехе
        return redirect()->route('admin.games.index')
            ->with('success', 'Игра успешно добавлена!');
    }
    //ФОРМА РЕДАКТИРОВАНИЯ ИГРЫ
    public function edit($id)
    {
        // Ищем игру по ID, если нет - 404
        $game = Game::findOrFail($id);
        // Получаем все жанры для выпадающего списка
        $genres = Genre::all();
        // Показываем форму редактирования
        return view('admin.games.edit', compact('game', 'genres'));
    }
    //ОБНОВИТЬ ИГРУ
    public function update(Request $request, $id)
    {
        // Ищем игру по ID
        $game = Game::findOrFail($id);
        // ПРОВЕРЯЕМ ДАННЫЕ (уникальность названия - исключая эту игру)
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
        // ЕСЛИ НАЗВАНИЕ ИЗМЕНИЛОСЬ - ОБНОВЛЯЕМ SLUG
        if ($game->title !== $validated['title']) {
            $validated['slug'] = Str::slug($validated['title']);
        }
        // ЕСЛИ ЗАГРУЗИЛИ НОВУЮ ОБЛОЖКУ
        if ($request->hasFile('cover_image')) {
            // Удаляем старую обложку, если она была
            if ($game->cover_image) {
                Storage::disk('public')->delete($game->cover_image);
            }
            // Сохраняем новую
            $path = $request->file('cover_image')->store('covers', 'public');
            $validated['cover_image'] = $path;
        }
        // ОБНОВЛЯЕМ ДАННЫЕ В БАЗЕ
        $game->update($validated);
        // Перенаправляем в админку с сообщением
        return redirect()->route('admin.games.index')
            ->with('success', 'Игра успешно обновлена!');
    }
    //УДАЛИТЬ ИГРУ
    public function destroy($id)
    {
        // Ищем игру по ID
        $game = Game::findOrFail($id);
        // ЕСЛИ У ИГРЫ БЫЛА ОБЛОЖКА - УДАЛЯЕМ ФАЙЛ
        if ($game->cover_image) {
            Storage::disk('public')->delete($game->cover_image);
        }
        // УДАЛЯЕМ ЗАПИСЬ ИЗ БАЗЫ
        $game->delete();
        // Перенаправляем в админку с сообщением
        return redirect()->route('admin.games.index')
            ->with('success', 'Игра успешно удалена!');
    }
}
