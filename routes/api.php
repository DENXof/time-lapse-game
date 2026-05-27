<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiGameController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CommentController;

// ============================================
// ПУБЛИЧНЫЕ API МАРШРУТЫ (без авторизации)
// ============================================
Route::get('/games', [ApiGameController::class, 'index']);
Route::get('/games/top', [ApiGameController::class, 'top']);
Route::get('/games/new', [ApiGameController::class, 'newReleases']);
Route::get('/games/random', [ApiGameController::class, 'randomGame']);
Route::get('/games/{id}', [ApiGameController::class, 'show']);
Route::get('/games/slug/{slug}', [ApiGameController::class, 'showBySlug']);
Route::get('/genres', [ApiGameController::class, 'genres']);
Route::get('/genres/{id}/games', [ApiGameController::class, 'gamesByGenre']);

// ============================================
// АУТЕНТИФИКАЦИЯ (API)
// ============================================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// ============================================
// ЗАЩИЩЁННЫЕ API МАРШРУТЫ (требуют токен)
// ============================================
Route::middleware('auth:sanctum')->group(function () {
    // Профиль пользователя
    Route::get('/user', [UserController::class, 'profile']);
    Route::put('/user', [UserController::class, 'updateProfile']);
    Route::post('/user/avatar', [UserController::class, 'updateAvatar']);

    // Избранное
    Route::get('/user/favorites', [UserController::class, 'favorites']);
    Route::post('/games/{id}/favorite', [UserController::class, 'toggleFavorite']);

    // Оценки
    Route::get('/user/ratings', [UserController::class, 'ratings']);
    Route::post('/games/{id}/rate', [UserController::class, 'rate']);

    // Комментарии
    Route::get('/games/{id}/comments', [CommentController::class, 'index']);
    Route::post('/games/{id}/comments', [CommentController::class, 'store']);
    Route::put('/comments/{id}', [CommentController::class, 'update']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
});
