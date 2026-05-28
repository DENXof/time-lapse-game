<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminCommentController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\SettingsController;

// ============================================
// ПУБЛИЧНЫЕ МАРШРУТЫ (доступны всем)
// ============================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/timeline', [HomeController::class, 'timeline'])->name('timeline');
Route::get('games', [GameController::class, 'index'])->name('games.index');
Route::get('games/{slug}', [GameController::class, 'show'])->name('games.show');

Route::get('/top', [GameController::class, 'top'])->name('games.top');
Route::get('/new-releases', [GameController::class, 'newReleases'])->name('games.new');
Route::get('/random-game', [GameController::class, 'randomGame'])->name('games.random');
Route::get('/calendar', [GameController::class, 'calendar'])->name('games.calendar');
Route::get('/achievements', [AchievementController::class, 'index'])->name('achievements.index');

// ============================================
// ВОССТАНОВЛЕНИЕ ПАРОЛЯ (доступно всем)
// ============================================
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

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

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('update');
        Route::get('/change-password', [\App\Http\Controllers\ProfileController::class, 'changePasswordForm'])->name('change-password');
        Route::put('/update-password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('update-password');
        Route::get('/ratings', [\App\Http\Controllers\ProfileController::class, 'ratings'])->name('ratings');
        Route::get('/{user}', [\App\Http\Controllers\ProfileController::class, 'show'])->name('show');
    });

    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{game}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::post('/ratings/{game}', [RatingController::class, 'store'])->name('ratings.store');

    Route::prefix('comments')->name('comments.')->group(function () {
        Route::post('/game/{game}', [CommentController::class, 'store'])->name('store');
        Route::put('/{comment}', [CommentController::class, 'update'])->name('update');
        Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('destroy');
        Route::post('/{comment}/like', [CommentController::class, 'like'])->name('like');
        Route::post('/{comment}/reply', [CommentController::class, 'reply'])->name('reply');
    });

    Route::prefix('friends')->name('friends.')->group(function () {
        Route::get('/', [FriendController::class, 'index'])->name('index');
        Route::get('/requests', [FriendController::class, 'requests'])->name('requests');
        Route::post('/send/{user}', [FriendController::class, 'sendRequest'])->name('send');
        Route::post('/accept/{friendship}', [FriendController::class, 'accept'])->name('accept');
        Route::post('/reject/{friendship}', [FriendController::class, 'reject'])->name('reject');
        Route::delete('/remove/{user}', [FriendController::class, 'destroy'])->name('remove');
    });

    Route::prefix('activity')->name('activity.')->group(function () {
        Route::get('/feed', [ActivityController::class, 'feed'])->name('feed');
        Route::get('/my', [ActivityController::class, 'myActivity'])->name('my');
    });
});

// ============================================
// АДМИНИСТРАТИВНАЯ ПАНЕЛЬ
// ============================================
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

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
            Route::get('update-prices', [GameController::class, 'updatePrices'])->name('update-prices');
            Route::post('{game}/find-steam', [GameController::class, 'findSteamId'])->name('find-steam');
            Route::get('find-missing-steam', [GameController::class, 'findMissingSteamIds'])->name('find-missing-steam');
        });

        Route::resource('users', UserController::class)->except(['create', 'store']);
        Route::post('users/{user}/ban', [UserController::class, 'ban'])->name('users.ban');
        Route::post('users/{user}/unban', [UserController::class, 'unban'])->name('users.unban');

        Route::resource('comments', AdminCommentController::class)->only(['index', 'show', 'destroy']);
        Route::post('comments/{comment}/approve', [AdminCommentController::class, 'approve'])->name('comments.approve');
        Route::post('comments/{comment}/hide', [AdminCommentController::class, 'hide'])->name('comments.hide');

        Route::get('logs', [LogController::class, 'index'])->name('logs.index');
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');

        Route::get('profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password.update');

        Route::get('clear-cache', function () {
            \Illuminate\Support\Facades\Cache::flush();
            return back()->with('success', 'Кеш успешно очищен!');
        })->name('admin.clear-cache');

        Route::get('import-games', function () {
            return view('admin.import-games');
        })->name('import-games');

        Route::post('import-games/run', [GameController::class, 'runImport'])->name('import-games.run');
    });
});
