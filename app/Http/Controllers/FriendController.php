<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friendship;
use App\Services\ActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FriendController extends Controller
{
    /**
     * Список друзей
     */
    public function index()
    {
        $user = Auth::user();

        // Получаем друзей через метод модели (исправленный)
        $friends = $user->friends()->paginate(20);

        return view('friends.index', compact('friends'));
    }

    /**
     * Полученные заявки
     */
    public function requests()
    {
        $user = Auth::user();
        $requests = $user->receivedFriendRequests()->with('user')->paginate(20);

        return view('friends.requests', compact('requests'));
    }

    /**
     * Отправить заявку в друзья
     */
    public function sendRequest(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Нельзя добавить себя в друзья');
        }

        // Проверка, есть ли уже заявка
        $existing = Friendship::where(function($q) use ($user) {
                    $q->where('user_id', Auth::id())
                      ->where('friend_id', $user->id);
                })->orWhere(function($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->where('friend_id', Auth::id());
                })->first();

        if ($existing) {
            if ($existing->status === 'pending') {
                return back()->with('error', 'Заявка уже отправлена');
            } elseif ($existing->status === 'accepted') {
                return back()->with('error', 'Вы уже друзья');
            } elseif ($existing->status === 'rejected') {
                $existing->delete();
            }
        }

        // Создаём заявку
        Friendship::create([
            'user_id' => Auth::id(),
            'friend_id' => $user->id,
            'status' => 'pending',
            'action_user_id' => Auth::id()
        ]);

        ActivityService::log('friend_request', $user);

        return back()->with('success', 'Заявка в друзья отправлена');
    }

    /**
     * Принять заявку
     */
    public function accept(Friendship $friendship)
    {
        // Проверка, что заявка адресована текущему пользователю
        if ($friendship->friend_id !== Auth::id()) {
            abort(403, 'Доступ запрещён');
        }

        // Проверка, что заявка ещё не обработана
        if ($friendship->status !== 'pending') {
            return back()->with('error', 'Эта заявка уже обработана');
        }

        $friendship->accept();

        ActivityService::log('friend_accepted', $friendship->user);

        return back()->with('success', 'Заявка принята');
    }

    /**
     * Отклонить заявку
     */
    public function reject(Friendship $friendship)
    {
        // Проверка, что заявка адресована текущему пользователю
        if ($friendship->friend_id !== Auth::id()) {
            abort(403, 'Доступ запрещён');
        }

        // Проверка, что заявка ещё не обработана
        if ($friendship->status !== 'pending') {
            return back()->with('error', 'Эта заявка уже обработана');
        }

        $friendship->update(['status' => 'rejected']);
        $friendship->delete();

        return back()->with('success', 'Заявка отклонена');
    }

    /**
     * Удалить из друзей
     */
    public function destroy(User $user)
    {
        // Проверка, что пользователь не удаляет самого себя
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Нельзя удалить самого себя');
        }

        $friendship = Friendship::where(function($q) use ($user) {
                    $q->where('user_id', Auth::id())
                      ->where('friend_id', $user->id);
                })->orWhere(function($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->where('friend_id', Auth::id());
                })->where('status', 'accepted')->first();

        if (!$friendship) {
            return back()->with('error', 'Пользователь не найден в списке друзей');
        }

        $friendship->delete();

        return back()->with('success', 'Пользователь удалён из друзей');
    }
}
