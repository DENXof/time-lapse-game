<?php
use Illuminate\Support\Facades\Route;
// Импорт фасада Route для определения маршрутов
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\Admin\ProfileController;
// ============================================
// ПУБЛИЧНЫЕ МАРШРУТЫ (доступны без аутентификации)
// ============================================
Route::get('/', [HomeController::class, 'index'])->name('home');
// Определяет GET-маршрут для корневого URL
// При обращении к '/' вызывается метод index контроллера HomeController
// Маршруту присваивается имя 'home' для генерации ссылок
Route::get('/timeline', [HomeController::class, 'timeline'])->name('timeline');
// Определяет GET-маршрут для '/timeline'
// Вызывает метод timeline контроллера HomeController
// Отображает хронологию игровых эпох
Route::get('games', [GameController::class, 'index'])->name('games.index');
// Определяет GET-маршрут для '/games'
// Вызывает метод index контроллера GameController
// Отображает список всех игр
Route::get('games/{slug}', [GameController::class, 'show'])->name('games.show');
// Определяет GET-маршрут с динамическим параметром {slug}
// Параметр передаётся в метод show контроллера GameController
// Отображает детальную страницу конкретной игры
// ============================================
// МАРШРУТЫ АДМИН-ПАНЕЛИ
// ============================================
Route::prefix('admin')->name('admin.')->group(function () {
    // Группирует маршруты с префиксом 'admin' в URL
    // Все имена маршрутов получают префикс 'admin.'
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    // GET-маршрут для отображения формы входа в админ-панель
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    // POST-маршрут для обработки данных формы входа
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    // POST-маршрут для выхода из системы
    Route::middleware(['admin'])->group(function () {
        // Группа маршрутов, защищённых middleware 'admin'
        // Доступ только для аутентифицированных администраторов
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        // GET-маршрут для отображения главной страницы админ-панели
        Route::resource('genres', GenreController::class);
        // Ресурсный маршрут для CRUD-операций с жанрами
        // Автоматически создаёт 7 маршрутов для всех операций
        Route::prefix('games')->name('games.')->group(function () {
            // Группа маршрутов для управления играми в админ-панели
            Route::get('/', [GameController::class, 'adminIndex'])->name('index');
            // GET-маршрут для отображения списка игр в админ-панели
            Route::get('create', [GameController::class, 'create'])->name('create');
            // GET-маршрут для отображения формы создания игры
            Route::post('/', [GameController::class, 'store'])->name('store');
            // POST-маршрут для сохранения новой игры
            Route::get('{game}/edit', [GameController::class, 'edit'])->name('edit');
            // GET-маршрут для отображения формы редактирования игры
            Route::put('{game}', [GameController::class, 'update'])->name('update');
            // PUT-маршрут для обновления данных игры
            Route::delete('{game}', [GameController::class, 'destroy'])->name('destroy');
            // DELETE-маршрут для удаления игры
        });
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        // GET-маршрут для отображения формы редактирования профиля
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
        // PUT-маршрут для обновления данных профиля
        Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
        // PUT-маршрут для обновления пароля
    });
});
