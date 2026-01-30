<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin; // Измените на Admin
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Получить текущего администратора
     */
    private function getCurrentAdmin()
    {
        // Способ 1: Через сессию (если у вас своя админская аутентификация)
        $adminId = session('admin_id');
        if ($adminId) {
            return Admin::find($adminId);
        }

        // Способ 2: Берем первого админа из таблицы admins
        return Admin::first();
    }

    /**
     * Показать форму редактирования профиля
     */
    public function edit()
    {
        $admin = $this->getCurrentAdmin(); // Изменил на $admin

        if (!$admin) {
            return redirect()->route('admin.login')
                ->with('error', 'Администратор не найден');
        }

        // Передаем как 'user' для совместимости с шаблоном
        return view('admin.profile.edit', ['user' => $admin]);
    }

    /**
     * Обновить основную информацию профиля
     */
    public function update(Request $request)
    {
        $admin = $this->getCurrentAdmin(); // Изменил на $admin

        if (!$admin) {
            abort(403, 'Доступ запрещен');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // Используем таблицу 'admins', а не 'users'
            'email' => 'required|string|email|max:255|unique:admins,email,' . $admin->id,
        ]);

        $admin->update($validated);

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Профиль успешно обновлен!');
    }

    /**
     * Обновить пароль
     */
    public function updatePassword(Request $request)
    {
        $admin = $this->getCurrentAdmin(); // Изменил на $admin

        if (!$admin) {
            abort(403, 'Доступ запрещен');
        }

        $validator = Validator::make($request->all(), [
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) use ($admin) {
                    if (!Hash::check($value, $admin->password)) {
                        $fail('Текущий пароль введен неверно.');
                    }
                }
            ],
            'password' => 'required|string|min:8|confirmed|different:current_password',
        ], [
            'password.different' => 'Новый пароль должен отличаться от текущего.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.profile.edit')
                ->withErrors($validator, 'password');
        }

        $admin->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Пароль успешно изменен!');
    }
}
