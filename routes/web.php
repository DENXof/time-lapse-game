<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;

// ============================================
// ПУБЛИЧНЫЕ МАРШРУТЫ (доступны всем)
// ============================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/timeline', [HomeController::class, 'timeline'])->name('timeline');
Route::get('games', [GameController::class, 'index'])->name('games.index');
Route::get('games/{slug}', [GameController::class, 'show'])->name('games.show');

// ========= ДОБАВЛЕННЫЕ МАРШРУТЫ ДЛЯ ЭТАПА 4 =========
Route::get('/top', [GameController::class, 'top'])->name('games.top');
Route::get('/new-releases', [GameController::class, 'newReleases'])->name('games.new');
Route::get('/random-game', [GameController::class, 'randomGame'])->name('games.random');
Route::get('/calendar', [GameController::class, 'calendar'])->name('games.calendar');
// ====================================================

// ========= ДОБАВЛЕННЫЙ МАРШРУТ ДЛЯ ДОСТИЖЕНИЙ =========
Route::get('/achievements', [AchievementController::class, 'index'])->name('achievements.index');
// =======================================================

// ============================================
// АУТЕНТИФИКАЦИЯ ПОЛЬЗОВАТЕЛЕЙ (гости)
// ============================================
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// ============================================
// ЗАЩИЩЁННЫЕ МАРШРУТЫ (только для авторизованных пользователей)
// ============================================
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ПРОФИЛЬ ПОЛЬЗОВАТЕЛЯ
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('update');
        Route::get('/change-password', [\App\Http\Controllers\ProfileController::class, 'changePasswordForm'])->name('change-password');
        Route::put('/update-password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('update-password');
        Route::get('/ratings', [\App\Http\Controllers\ProfileController::class, 'ratings'])->name('ratings');
    });

    // ИЗБРАННОЕ
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{game}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    // ОЦЕНКИ
    Route::post('/ratings/{game}', [RatingController::class, 'store'])->name('ratings.store');

    // КОММЕНТАРИИ
    Route::prefix('comments')->name('comments.')->group(function () {
        Route::post('/game/{game}', [CommentController::class, 'store'])->name('store');
        Route::put('/{comment}', [CommentController::class, 'update'])->name('update');
        Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('destroy');
        Route::post('/{comment}/like', [CommentController::class, 'like'])->name('like');
        Route::post('/{comment}/reply', [CommentController::class, 'reply'])->name('reply');
    });
});

// ============================================
// АДМИНИСТРАТИВНАЯ ПАНЕЛЬ
// ============================================
Route::prefix('admin')->name('admin.')->group(function () {
    // Аутентификация админов
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Защищённые маршруты админки
    Route::middleware(['admin'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('genres', GenreController::class);

        Route::prefix('games')->name('games.')->group(function () {
            Route::get('/', [GameController::class, 'adminIndex'])->name('index');
            Route::get('create', [GameController::class, 'create'])->name('create');
            Route::post('/', [GameController::class, 'store'])->name('store');
            Route::get('{game}/edit', [GameController::class, 'edit'])->name('edit');
            Route::put('{game}', [GameController::class, 'update'])->name('update');
            Route::delete('{game}', [GameController::class, 'destroy'])->name('destroy');
        });

        Route::get('profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password.update');
    });
});
