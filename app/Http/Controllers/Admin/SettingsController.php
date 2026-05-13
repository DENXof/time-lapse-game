<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\AdminLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            if (str_starts_with($key, 'setting_')) {
                $settingKey = substr($key, 8);
                Setting::set($settingKey, $value);
            }
        }

        AdminLog::create([
            'admin_id' => Auth::id(),
            'action' => 'update_settings',
            'target_type' => 'system',
            'target_id' => 0,
            'details' => ['settings' => $request->all()],
            'ip_address' => $request->ip()
        ]);

        return back()->with('success', 'Настройки сохранены');
    }
}
