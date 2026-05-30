<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminManagementController extends Controller
{
    // Простая проверка в конструкторе (без middleware)
    public function __construct()
    {
        // Проверяем права доступа при создании контроллера
        $this->checkAccess();
    }

    private function checkAccess()
    {
        $user = Auth::guard('admin')->user();
        if (!$user || !$user->isSuperAdmin()) {
            abort(403, 'Доступ запрещён. Только супер-администратор может управлять администраторами.');
        }
    }

    public function index()
    {
        $admins = Admin::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:super_admin,admin', // Убрали moderator
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true,
        ]);

        Auth::guard('admin')->user()->log('create_admin', 'admin', $admin->id, [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => $admin->role,
        ]);

        return redirect()->route('admin.admins.index')
            ->with('success', "Администратор {$admin->name} успешно создан!");
    }

    public function show(Admin $admin)
    {
        $logs = $admin->logs()->latest()->paginate(20);
        return view('admin.admins.show', compact('admin', 'logs'));
    }

    public function edit(Admin $admin)
    {
        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, Admin $admin)
    {
        if ($admin->id === Auth::guard('admin')->id()) {
            return back()->with('error', 'Вы не можете редактировать свой собственный аккаунт.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'role' => 'required|in:super_admin,admin', // Убрали moderator
            'is_active' => 'required|boolean',
        ]);

        $oldData = $admin->only(['name', 'email', 'role', 'is_active']);

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->is_active,
        ]);

        Auth::guard('admin')->user()->log('update_admin', 'admin', $admin->id, [
            'old' => $oldData,
            'new' => $admin->only(['name', 'email', 'role', 'is_active']),
        ]);

        return redirect()->route('admin.admins.index')
            ->with('success', "Данные администратора {$admin->name} обновлены!");
    }

    public function resetPassword(Request $request, Admin $admin)
    {
        if ($admin->id === Auth::guard('admin')->id()) {
            return back()->with('error', 'Используйте профиль для смены своего пароля.');
        }

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $admin->update([
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('admin')->user()->log('reset_admin_password', 'admin', $admin->id, [
            'admin_name' => $admin->name,
        ]);

        return back()->with('success', "Пароль для {$admin->name} успешно сброшен!");
    }

    public function destroy(Admin $admin)
    {
        if ($admin->id === Auth::guard('admin')->id()) {
            return back()->with('error', 'Вы не можете удалить свой собственный аккаунт.');
        }

        $adminName = $admin->name;

        Auth::guard('admin')->user()->log('delete_admin', 'admin', $admin->id, [
            'deleted_admin' => $adminName,
        ]);

        $admin->delete();

        return redirect()->route('admin.admins.index')
            ->with('success', "Администратор {$adminName} удалён!");
    }
}
