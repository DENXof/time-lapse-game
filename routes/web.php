<?php
//ФАЙЛ МАРШРУТОВ (web.php)
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;           // Главная страница
use App\Http\Controllers\GameController;           // Игры
use App\Http\Controllers\Admin\AuthController;     // Вход для админов
use App\Http\Controllers\Admin\DashboardController; // Админка (главная)
use App\Http\Controllers\Admin\GenreController;    // Управление жанрами
use App\Http\Controllers\Admin\ProfileController;  // Профиль админа

// ============================================
// 1. ПУБЛИЧНАЯ ЧАСТЬ
// ============================================

// Главная страница сайта
Route::get('/', [HomeController::class, 'index'])->name('home');

// Страница с историей игр
Route::get('/timeline', [HomeController::class, 'timeline'])->name('timeline');

// Список всех игр
Route::get('games', [GameController::class, 'index'])->name('games.index');

// Страница одной игры по slug
// {slug} - это переменная
Route::get('games/{slug}', [GameController::class, 'show'])->name('games.show');

// ============================================
// 2. АДМИН-ПАНЕЛЬ (все адреса начинаются с /admin)
// ============================================

// Группируем все админские маршруты:
// prefix('admin') - все адреса будут начинаться с /admin
// name('admin.') - все имена маршрутов будут начинаться с admin.
Route::prefix('admin')->name('admin.')->group(function () {

    // --------------------------------------------------------
    // 2.1 ВХОД В АДМИНКУ (доступен всем, даже не админам)
    // --------------------------------------------------------

    // Страница входа (admin/login)
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');

    // Отправка формы входа (POST запрос)
    Route::post('login', [AuthController::class, 'login'])->name('login.post');

    // Выход из админки (admin/logout)
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // --------------------------------------------------------
    // 2.2 ЗАЩИЩЕННЫЕ МАРШРУТЫ (только для авторизованных админов)
    // --------------------------------------------------------
    Route::middleware(['admin'])->group(function () {

        // Главная страница админки (admin/dashboard)
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // --------------------------------------------------------
        // 2.3 УПРАВЛЕНИЕ ЖАНРАМИ (CRUD)
        // --------------------------------------------------------
        // Route::resource создает сразу 7 маршрутов для жанров:
        // GET    /admin/genres              - список жанров
        // GET    /admin/genres/create       - форма создания
        // POST   /admin/genres              - сохранить новый
        // GET    /admin/genres/{genre}      - показать один жанр
        // GET    /admin/genres/{genre}/edit - форма редактирования
        // PUT    /admin/genres/{genre}      - обновить жанр
        // DELETE /admin/genres/{genre}      - удалить жанр
        Route::resource('genres', GenreController::class);

        // --------------------------------------------------------
        // 2.4 УПРАВЛЕНИЕ ИГРАМИ (вручную)
        // --------------------------------------------------------
        Route::prefix('games')->name('games.')->group(function () {

            // Список игр в админке (admin/games) с поиском
            Route::get('/', [GameController::class, 'adminIndex'])->name('index');

            // Форма создания новой игры (admin/games/create)
            Route::get('create', [GameController::class, 'create'])->name('create');

            // Сохранение новой игры (POST на admin/games)
            Route::post('/', [GameController::class, 'store'])->name('store');

            // Форма редактирования игры (admin/games/5/edit)
            // {game} - ID игры, например 5
            Route::get('{game}/edit', [GameController::class, 'edit'])->name('edit');

            // Обновление игры (PUT на admin/games/5)
            Route::put('{game}', [GameController::class, 'update'])->name('update');

            // Удаление игры (DELETE на admin/games/5)
            Route::delete('{game}', [GameController::class, 'destroy'])->name('destroy');
        });

        // --------------------------------------------------------
        // 2.5 ПРОФИЛЬ АДМИНИСТРАТОРА
        // --------------------------------------------------------

        // Страница редактирования профиля (admin/profile)
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');

        // Обновление данных профиля (PUT на admin/profile)
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

        // Смена пароля (PUT на admin/profile/password)
        Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    });
});
