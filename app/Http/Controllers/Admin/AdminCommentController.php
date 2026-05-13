<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\AdminLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminCommentController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with(['user', 'game']);

        if ($request->filled('search')) {
            $query->where('content', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('is_approved', $request->status === 'approved');
        }

        $comments = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.comments.index', compact('comments'));
    }

    public function show(Comment $comment)
    {
        return view('admin.comments.show', compact('comment'));
    }

    public function approve(Comment $comment)
    {
        $comment->update(['is_approved' => true]);

        AdminLog::create([
            'admin_id' => Auth::id(),  // ИСПРАВЛЕНО: Auth::guard('admin')->id() → Auth::id()
            'action' => 'approve_comment',
            'target_type' => 'comment',
            'target_id' => $comment->id,
            'details' => [],
            'ip_address' => request()->ip()
        ]);

        return back()->with('success', 'Комментарий опубликован');
    }

    public function hide(Comment $comment)
    {
        $comment->update(['is_approved' => false]);

        AdminLog::create([
            'admin_id' => Auth::id(),  // ИСПРАВЛЕНО
            'action' => 'hide_comment',
            'target_type' => 'comment',
            'target_id' => $comment->id,
            'details' => [],
            'ip_address' => request()->ip()
        ]);

        return back()->with('success', 'Комментарий скрыт');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        AdminLog::create([
            'admin_id' => Auth::id(),  // ИСПРАВЛЕНО
            'action' => 'delete_comment',
            'target_type' => 'comment',
            'target_id' => $comment->id,
            'details' => [],
            'ip_address' => request()->ip()
        ]);

        return redirect()->route('admin.comments.index')
            ->with('success', 'Комментарий удалён');
    }
}
