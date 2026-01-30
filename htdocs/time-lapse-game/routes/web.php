<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\Admin\ProfileController; // ← ДОБАВИТЬ ЭТУ СТРОЧКУ

// ============================================
// ПУБЛИЧНАЯ ЧАСТЬ (доступна всем)
// ============================================

// Главная страница
Route::get('/', [HomeController::class, 'index'])->name('home');

// Таймлайн
Route::get('/timeline', [HomeController::class, 'timeline'])->name('timeline');

// Просмотр списка игр (доступно всем)
Route::get('games', [GameController::class, 'index'])->name('games.index');

// Просмотр одной игры по slug (доступно всем) - используем явное указание {slug} для ясности
Route::get('games/{slug}', [GameController::class, 'show'])->name('games.show');

// Простая тестовая страница
Route::get('/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'TimeLapse Games API работает!',
        'data' => [
            'models' => ['Game', 'Genre', 'Era', 'Rating', 'Comment'],
            'tables' => 6,
            'version' => '1.0.0'
        ]
    ]);
});

// ============================================
// АДМИН-ПАНЕЛЬ
// ============================================

Route::prefix('admin')->name('admin.')->group(function () {
    // Аутентификация (доступна всем)
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Защищенные маршруты (только для админов)
    Route::middleware(['admin'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Управление жанрами (только в админке)
        Route::resource('genres', GenreController::class);

        // Управление играми (только в админке)
        Route::prefix('games')->name('games.')->group(function () {
            Route::get('/', [GameController::class, 'adminIndex'])->name('index');
            Route::get('create', [GameController::class, 'create'])->name('create');
            Route::post('/', [GameController::class, 'store'])->name('store');
            // Используем {id} для админских маршрутов и {game} для implicit binding
            Route::get('{game}/edit', [GameController::class, 'edit'])->name('edit');
            Route::put('{game}', [GameController::class, 'update'])->name('update');
            Route::delete('{game}', [GameController::class, 'destroy'])->name('destroy');
        });

        // =============== ДОБАВЛЯЕМ ЭТОТ БЛОК ===============
        // Профиль администратора
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
        // =============== КОНЕЦ БЛОКА ===============
    });
});
