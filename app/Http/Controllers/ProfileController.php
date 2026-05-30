<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Главная страница профиля
    public function index(Request $request)
    {
        $user = Auth::user();

        // Статистика
        $favoritesCount = $user->favorites()->count();
        $ratingsCount = $user->ratings()->count();
        $commentsCount = $user->comments()->count();

        // Определяем активную вкладку
        $tab = $request->get('tab', 'favorites');

        // Избранные игры (с пагинацией)
        $favorites = $user->favorites()->paginate(12, ['*'], 'favorites_page');

        // Оценки (с пагинацией)
        $ratings = $user->ratings()
            ->with('game')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'ratings_page');

        // Комментарии (с пагинацией)
        $comments = $user->comments()
            ->with('game')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'comments_page');

        // Последние оценки (для блока "Последние оценки")
        $recentRatings = $user->ratings()
            ->with('game')
            ->latest()
            ->take(5)
            ->get();

        // Избранные игры (последние)
        $recentFavorites = $user->favorites()
            ->latest()
            ->take(5)
            ->get();

        return view('profile.index', compact(
            'user',
            'favoritesCount',
            'ratingsCount',
            'commentsCount',
            'favorites',
            'ratings',
            'comments',
            'tab',
            'recentRatings',
            'recentFavorites'
        ));
    }

    // Форма редактирования профиля
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // Обновление профиля
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:500',
            'telegram' => 'nullable|string|max:255',
            'vk' => 'nullable|string|max:255',
            'github' => 'nullable|string|max:255',
            'steam' => 'nullable|string|max:255',
            'twitch' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Обновляем основные поля
        $user->name = $request->name;
        $user->email = $request->email;
        $user->bio = $request->bio;
        $user->telegram = $request->telegram;
        $user->vk = $request->vk;
        $user->github = $request->github;
        $user->steam = $request->steam;
        $user->twitch = $request->twitch;
        $user->youtube = $request->youtube;

        // Обновляем аватар
        if ($request->hasFile('avatar')) {
            // Удаляем старый аватар
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->route('profile.index')
            ->with('success', 'Профиль успешно обновлён!');
    }

    // Форма смены пароля
    public function changePasswordForm()
    {
        return view('profile.change-password');
    }

    // Обновление пароля
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        // Проверяем текущий пароль
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Неверный текущий пароль']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile.index')
            ->with('success', 'Пароль успешно изменён!');
    }

    // Мои оценки (отдельная страница, если нужна)
    public function ratings()
    {
        $user = Auth::user();
        $ratings = $user->ratings()
            ->with('game')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('profile.ratings', compact('ratings'));
    }
}
