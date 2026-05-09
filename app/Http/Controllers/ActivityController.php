<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    /**
     * Лента активности друзей
     */
    public function feed()
    {
        $user = Auth::user();
        $activities = $user->friendsActivities(50);

        return view('activity.feed', compact('activities'));
    }

    /**
     * Моя активность
     */
    public function myActivity()
    {
        $user = Auth::user();
        $activities = $user->activities()->paginate(30);

        return view('activity.my', compact('activities'));
    }
}
