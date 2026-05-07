<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function index()
    {
        $achievements = Achievement::all()->groupBy('type');

        if (auth()->check()) {
            $userAchievements = auth()->user()->achievements->pluck('id')->toArray();
        } else {
            $userAchievements = [];
        }

        return view('achievements.index', compact('achievements', 'userAchievements'));
    }
}
