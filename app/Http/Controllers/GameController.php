<?php
// КОНТРОЛЛЕР УПРАВЛЕНИЯ ИГРАМИ

// Указываем, что это контроллер (папка Controllers)
namespace App\Http\Controllers;

// Подключаем нужные модели и классы
use App\Models\Game;    // Модель игры (работа с таблицей games)
use App\Models\Genre;   // Модель жанра (работа с таблицей genres)
use Illuminate\Http\Request;    // Для получения данных из форм
use Illuminate\Support\Str; // Для работы со строками (создание slug)
use Illuminate\Support\Facades\Storage; // Для работы с файлами (загрузка обложек)

class GameController extends Controller
{
    // ============================================
    // ПУБЛИЧНЫЕ МЕТОДЫ (доступны всем посетителям сайта)
    // ============================================

    //ПОКАЗАТЬ ВСЕ ИГРЫ (ГЛАВНАЯ СТРАНИЦА С ИГРАМИ)
    public function index()
    {
        // Берем все игры из БД
        $games = Game::with('genre')    // Загружаем для каждой игры её жанр
            ->orderBy('release_year', 'desc')   // Сортируем от новых к старым (2025, 2024, 2023...)
            ->paginate(12); // Показываем по 12 игр на странице

        // Берем все жанры из БД (для фильтра или выпадающего списка)
        $genres = Genre::all();

        // Отправляем данные в шаблон games/index.blade.php
        return view('games.index', compact('games', 'genres'));
    }

    //(ДЕТАЛЬНАЯ СТРАНИЦА)
    public function show($slug)
    {
        // Ищем игру по её slug (название игры URL)
        $game = Game::where('slug', $slug)  // Где slug совпадает с тем, что в URL
            ->with('genre') // Загружаем жанр игры
            ->firstOrFail();    // Если не нашли - показываем ошибку 404

        // Увеличиваем счетчик просмотров на 1
        $game->increment('views_count');

        // Ищем похожие игры (того же жанра)
        $relatedGames = Game::where('genre_id', $game->genre_id)    // Тот же жанр
            ->where('id', '!=', $game->id)  // Не та же самая игра
            ->orderBy('release_year', 'desc')   // Сначала новые
            ->take(3)   // Берём только 3 игры
            ->get();    // Получаем результат

        // Отправляем данные в шаблон games/show.blade.php
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

        // СОЗДАЕМ SLUG ИЗ НАЗВАНИЯ (например "The Witcher 3" -> "the-witcher-3")
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
