<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CommentController;

// ============================================
// ПУБЛИЧНЫЕ API МАРШРУТЫ (без авторизации)
// ============================================
Route::get('/games', [GameController::class, 'index']);
Route::get('/games/top', [GameController::class, 'top']);
Route::get('/games/new', [GameController::class, 'newReleases']);
Route::get('/games/random', [GameController::class, 'randomGame']);
Route::get('/games/{id}', [GameController::class, 'show']);
Route::get('/games/slug/{slug}', [GameController::class, 'showBySlug']);
Route::get('/genres', [GameController::class, 'genres']);
Route::get('/genres/{id}/games', [GameController::class, 'gamesByGenre']);

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
