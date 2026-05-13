<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AdminLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,moderator,admin',
            'status' => 'required|in:active,banned',
        ]);

        $user->update($request->only(['name', 'email', 'role', 'status']));

        AdminLog::create([
            'admin_id' => Auth::id(),
            'action' => 'update_user',
            'target_type' => 'user',
            'target_id' => $user->id,
            'details' => ['changes' => $request->all()],
            'ip_address' => $request->ip()
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь обновлён');
    }

    public function ban(Request $request, User $user)
    {
        $request->validate([
            'ban_reason' => 'required|string|max:500',
            'banned_until' => 'nullable|date'
        ]);

        $user->update([
            'status' => 'banned',
            'ban_reason' => $request->ban_reason,
            'banned_until' => $request->banned_until
        ]);

        AdminLog::create([
            'admin_id' => Auth::id(),
            'action' => 'ban_user',
            'target_type' => 'user',
            'target_id' => $user->id,
            'details' => ['reason' => $request->ban_reason, 'until' => $request->banned_until],
            'ip_address' => $request->ip()
        ]);

        return back()->with('success', 'Пользователь забанен');
    }

    public function unban(User $user)
    {
        $user->update([
            'status' => 'active',
            'ban_reason' => null,
            'banned_until' => null
        ]);

        AdminLog::create([
            'admin_id' => Auth::id(),
            'action' => 'unban_user',
            'target_type' => 'user',
            'target_id' => $user->id,
            'details' => [],
            'ip_address' => request()->ip()
        ]);

        return back()->with('success', 'Бан снят');
    }

    public function destroy(User $user)
    {
        $user->delete();

        AdminLog::create([
            'admin_id' => Auth::id(),
            'action' => 'delete_user',
            'target_type' => 'user',
            'target_id' => $user->id,
            'details' => [],
            'ip_address' => request()->ip()
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь удалён');
    }
}
