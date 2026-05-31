<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RankingController extends Controller
{
    public function index(Request $request)
    {
        $users = User::withCount(['achievements as total_points' => function ($query) {
                $query->select(DB::raw('sum(points)'));
            }])
            ->orderBy('total_points', 'desc')
            ->paginate(20);

        $users->getCollection()->transform(function ($user, $key) use ($users) {
            $user->rank = $users->firstItem() + $key;
            return $user;
        });

        return view('ranking.index', compact('users'));
    }
}
