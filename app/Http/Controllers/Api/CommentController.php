<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Comment;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function index($id, Request $request)
    {
        $game = Game::findOrFail($id);

        $comments = $game->comments()
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->latest()
            ->paginate($request->get('per_page', 20));

        return CommentResource::collection($comments);
    }

    public function store($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|min:2|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $game = Game::findOrFail($id);

        $comment = Comment::create([
            'user_id' => $request->user()->id,
            'game_id' => $game->id,
            'parent_id' => $request->parent_id,
            'content' => $request->content,
        ]);

        return response()->json([
            'success' => true,
            'comment' => new CommentResource($comment->load('user')),
        ], 201);
    }

    public function update($id, Request $request)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|min:2|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $comment->update(['content' => $request->content]);

        return response()->json([
            'success' => true,
            'comment' => new CommentResource($comment->load('user')),
        ]);
    }

    public function destroy($id, Request $request)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['success' => true]);
    }
}
