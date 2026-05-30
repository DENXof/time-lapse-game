<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\Game;
use App\Services\AchievementService;
use App\Services\ActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Получить комментарии для игры (AJAX)
    public function index(Game $game)
    {
        $comments = $game->comments()
            ->whereNull('parent_id')
            ->with(['user', 'replies.user', 'replies.likes'])
            ->latest()
            ->paginate(10);

        return response()->json($comments);
    }

    // Добавить комментарий
    public function store(Request $request, Game $game)
    {
        $request->validate([
            'content' => 'required|string|min:2|max:1000',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'game_id' => $game->id,
            'parent_id' => $request->parent_id,
            'content' => $request->content,
        ]);

        ActivityService::log('comment', $comment);

        $achievementService = new AchievementService();
        $newAchievements = $achievementService->checkAndAward(Auth::user(), 'comment');

        if (!empty($newAchievements)) {
            session()->flash('new_achievements', $newAchievements);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'comment' => $comment->load('user', 'replies')
            ]);
        }

        return back()->with('success', 'Комментарий добавлен!');
    }

    // Обновить комментарий
    public function update(Request $request, Comment $comment)
    {
        if (Auth::id() !== $comment->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|string|min:2|max:1000'
        ]);

        $comment->update(['content' => $request->content]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'comment' => $comment]);
        }

        return back()->with('success', 'Комментарий обновлён!');
    }

    // Удалить комментарий
    public function destroy(Comment $comment)
    {
        if (Auth::id() !== $comment->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $comment->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Комментарий удалён!');
    }

    // Лайкнуть комментарий
    public function like(Comment $comment)
    {
        $user = Auth::user();

        $existingLike = CommentLike::where('user_id', $user->id)
            ->where('comment_id', $comment->id)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $comment->decrementLikesCount();
            $liked = false;
        } else {
            CommentLike::create([
                'user_id' => $user->id,
                'comment_id' => $comment->id
            ]);
            $comment->incrementLikesCount();
            $liked = true;
        }

        $achievementService = new AchievementService();
        $newAchievements = $achievementService->checkAndAward($comment->user, 'likes');

        if (!empty($newAchievements)) {
            session()->flash('new_achievements', $newAchievements);
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likes_count' => $comment->fresh()->likes_count
            ]);
        }

        return back();
    }

    // Ответить на комментарий
    public function reply(Request $request, Comment $comment)
    {
        $request->validate([
            'content' => 'required|string|min:2|max:1000'
        ]);

        $reply = Comment::create([
            'user_id' => Auth::id(),
            'game_id' => $comment->game_id,
            'parent_id' => $comment->id,
            'content' => $request->content,
        ]);

        ActivityService::log('comment', $reply);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'reply' => $reply->load('user')
            ]);
        }

        return back()->with('success', 'Ответ добавлен!');
    }
}
