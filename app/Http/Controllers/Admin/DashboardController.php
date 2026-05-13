<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Game;
use App\Models\Genre;
use App\Models\Comment;
use App\Models\Rating;
use App\Models\AdminLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Главная страница админки (Дашборд)
     */
    public function index()
    {
        // ========= ОСНОВНАЯ СТАТИСТИКА =========
        $stats = [
            'total_games' => Game::count(),
            'total_genres' => Genre::count(),
            'total_views' => Game::sum('views_count') ?? 0,
            'total_users' => User::count(),
            'total_comments' => Comment::count(),
            'total_ratings' => Rating::count(),
        ];

        // ========= НОВЫЕ ЗА СЕГОДНЯ =========
        $stats['new_users_today'] = User::whereDate('created_at', Carbon::today())->count();
        $stats['new_games_today'] = Game::whereDate('created_at', Carbon::today())->count();
        $stats['new_comments_today'] = Comment::whereDate('created_at', Carbon::today())->count();

        // ========= ПОСЛЕДНИЕ ИГРЫ =========
        $recentGames = Game::with('genre')
            ->latest()
            ->take(5)
            ->get();

        // ========= ПОПУЛЯРНЫЕ ИГРЫ (по просмотрам) =========
        $popularGames = Game::with('genre')
            ->orderBy('views_count', 'desc')
            ->limit(5)
            ->get();

        // ========= ТОП РЕЙТИНГ =========
        $topRatedGames = Game::where('rating_count', '>', 0)
            ->orderBy('rating_avg', 'desc')
            ->limit(5)
            ->get();

        // ========= АКТИВНОСТЬ ЗА 7 ДНЕЙ (регистрации) =========
        $registrations = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // ========= АКТИВНОСТЬ ЗА 7 ДНЕЙ (комментарии) =========
        $commentsActivity = Comment::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // ========= ПОСЛЕДНИЕ ДЕЙСТВИЯ АДМИНОВ =========
        $recentLogs = AdminLog::with('admin')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Подготавливаем данные для графиков
        $chartLabels = [];
        $chartRegistrations = [];
        $chartComments = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = Carbon::now()->subDays($i)->format('d.m');

            $reg = $registrations->firstWhere('date', $date);
            $chartRegistrations[] = $reg ? $reg->count : 0;

            $com = $commentsActivity->firstWhere('date', $date);
            $chartComments[] = $com ? $com->count : 0;
        }

        return view('admin.dashboard', compact(
            'stats', 'recentGames', 'popularGames', 'topRatedGames',
            'recentLogs', 'chartLabels', 'chartRegistrations', 'chartComments'
        ));
    }
}
