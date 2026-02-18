<?php
//Контроллер авунтификации админа
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Показать форму входа
    public function showLoginForm()
    {
        /**Если уже авторизован как админ - входит в дашборд(панель управления)
        Если не авторизован - форма входа */
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    // Обработка входа
    public function login(Request $request)
    {
        /*При успехе - регенерирует сессию и входит в дашборд(панель управления).
         При ошибке - возвращает обратно с сообщением об ошибке.*/
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            // Регенерируем сессию для защиты от фиксации сессии (session fixation)
            $request->session()->regenerate();
            // Перенаправляем в дашборд с сообщением об успехе
            return redirect()->route('admin.dashboard')
                ->with('success', 'Добро пожаловать в админ-панель!');
        }

        return back()->withErrors([
            'email' => 'Неверные учетные данные.', // Сообщение об ошибке
        ])->onlyInput('email'); // Сохраняем email для повторного заполнения формы
    }

    // Выход
    public function logout(Request $request)
    {
        // Выход через guard 'admin' - очищает данные аутентификации из сессии
        Auth::guard('admin')->logout();
        $request->session()->invalidate();  // Очищаем все данные сессии
        $request->session()->regenerateToken(); // Создаем новый CSRF-токен


        /**
         * Перенаправление пользователя на страницу входа в админ-панель
         * после успешного выхода из системы.
         */
        return redirect()->route('admin.login')
            ->with('success', 'Вы успешно вышли из системы.');
    }
}
