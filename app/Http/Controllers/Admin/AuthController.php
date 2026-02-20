<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Подключаем класс Request - через него получаем данные из форм
// (email, пароль, галочки)
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    // Форма входа
    public function showLoginForm()
    // Метод, который покажет страницу с формой входа
    {
        // Если уже авторизован как админ - входит в дашборд
// Если не авторизован - форма входа
        if (Auth::guard('admin')->check()) {
            // Проверяем: наличие залогигненого админа
// guard('admin') проверка таблицы админов
// check() - возвращает true, если админ уже вошёл
            return redirect()->route('admin.dashboard');
            // Если админ уже залогинен - отправляем его сразу в админ-панель
// redirect()->route() - перенаправление по имени маршрута
        }
        return view('admin.auth.login');
        // Если админ не залогинен - показываем форму входа
    }
    // Обработка входа
    public function login(Request $request)
    // Метод, который обрабатывает данные из формы входа
// $request - данные которые прислал пользователь (email, пароль)
    {
        // При успехе - регенерирует сессию и входит в дашборд(панель управления)
// При ошибке - возвращает обратно с сообщением об ошибке
        $credentials = $request->validate([
            // $request->validate() - проверяет, что данные правильные
// Результат сохраняем в переменную $credentials
            'email' => 'required|email',
            // Поле 'email': обязательно (required) и должно быть формата email
            'password' => 'required',
            // Поле 'password': обязательно (required)
        ]);
        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            // attempt() проверяет email и пароль
// guard('admin') - проверяем только в таблице админов
// $credentials - массив с email и паролем
// $request->boolean('remember') - true, если админ поставил галочку "Запомнить меня"
// Регенерируем сессию для защиты от фиксации сессии (session fixation)
            $request->session()->regenerate();
            //session()->regenerate() - создаёт новую сессию
// Это защита: старую сессию нельзя украсть и использовать
// Перенаправляем в дашборд с сообщением об успехе
            return redirect()->route('admin.dashboard')
                // Перенаправляем в админ-панель
                ->with('success', 'Добро пожаловать в админ-панель!');
            // with() - добавляем сообщение, которое покажется на следующей странице
// В шаблоне его можно показать через session('success')
        }

        return back()->withErrors([
            // Если сюда попали - значит вход не удался
// back() - вернуться на предыдущую страницу (обратно к форме)
// withErrors() - добавить ошибки, которые покажутся в форме
            'email' => 'Неверные учетные данные.', // Сообщение об ошибке
        ])->onlyInput('email'); //сохранит введённый email, чтобы не вводить заново
    }
    // Выход
    public function logout(Request $request)
    // Метод для выхода из системы
    {
        // Выход через guard 'admin' - очищает данные аутентификации из сессии
        Auth::guard('admin')->logout();
        // logout() - удаляем информацию о том, что админ был залогинен
        $request->session()->invalidate();
        // invalidate() - полностью очищаем сессию (удаляем все данные)
// Чтобы ничего лишнего не осталось
        $request->session()->regenerateToken();
        // regenerateToken() - создаём новый CSRF-токен
// CSRF-токен защищает формы от подделки запросов
        /**
         * Перенаправление пользователя на страницу входа в админ-панель
         * после успешного выхода из системы.
         */
        return redirect()->route('admin.login')
            // Перенаправляем на страницу входа
            ->with('success', 'Вы успешно вышли из системы.');
        // with() - добавляем сообщение, что выход прошёл успешно
    }
}
