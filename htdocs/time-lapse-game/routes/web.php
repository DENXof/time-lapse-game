<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;

// Главная страница
Route::get('/', [HomeController::class, 'index'])->name('home');

// Таймлайн
Route::get('/timeline', [HomeController::class, 'timeline'])->name('timeline');

// Игры (CRUD) - все методы кроме show
Route::resource('games', GameController::class)->except(['show']);

// Отдельный маршрут для show с использованием slug
Route::get('games/{game:slug}', [GameController::class, 'show'])->name('games.show');

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

        // Здесь позже добавим управление играми, жанрами и т.д.
    });
});
