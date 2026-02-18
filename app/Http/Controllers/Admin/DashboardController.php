<?php
//Контроллер админ-панели(дашборд)
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Genre;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Главная страница админки
    public function index()
    {
        $stats = [
            'total_games' => Game::count(), //подсчитывает количество записей в таблице games
            'total_genres' => Genre::count(),   //подсчитывает количество записей в таблице genres
            'recent_games' => Game::with('genre')->latest()->take(5)->get(),               /**
                              * Последние 5 добавленных игр
                              * with('genre') - жадная загрузка связи с жанром (избегаем N+1 проблемы)
                              * latest() - сортировка по created_at DESC
                              * take(5) - ограничиваем 5 записями
                              */

            'total_views' => Game::sum('views_count') ?? 0,   /**
                    * Общее количество просмотров всех игр
                    * sum('views_count') - суммирует значения поля views_count
                    * ?? 0 - если null, то подставляет 0
                    */
        ];

        return view('admin.dashboard', compact('stats')); /**
            * Передаем статистику в представление
            * compact('stats') - создает массив ['stats' => $stats] */
    }
}
