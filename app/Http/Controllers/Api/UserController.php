<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Rating;
use App\Http\Resources\UserResource;
use App\Http\Resources\GameResource;
use App\Http\Resources\GameCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        return new UserResource($request->user());
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'bio' => 'nullable|string|max:500',
            'telegram' => 'nullable|string|max:255',
            'discord' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->update($request->only(['name', 'bio', 'telegram', 'discord']));

        return response()->json([
            'success' => true,
            'user' => new UserResource($user),
        ]);
    }

    public function updateAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $path;
        $user->save();

        return response()->json([
            'success' => true,
            'avatar' => asset('storage/' . $path),
        ]);
    }

    public function favorites(Request $request)
    {
        $games = $request->user()->favorites()->paginate($request->get('per_page', 20));

        return new GameCollection($games);
    }

    public function toggleFavorite($id, Request $request)
    {
        $game = Game::findOrFail($id);
        $user = $request->user();

        if ($user->favorites()->where('game_id', $game->id)->exists()) {
            $user->favorites()->detach($game->id);
            $favorited = false;
        } else {
            $user->favorites()->attach($game->id);
            $favorited = true;
        }

        return response()->json([
            'success' => true,
            'favorited' => $favorited,
        ]);
    }

    public function ratings(Request $request)
    {
        $ratings = $request->user()->ratings()->with('game')->paginate($request->get('per_page', 20));

        return response()->json([
            'data' => $ratings->map(function($rating) {
                return [
                    'id' => $rating->id,
                    'value' => $rating->value,
                    'created_at' => $rating->created_at?->toISOString(),
                    'game' => new GameResource($rating->game),
                ];
            }),
            'meta' => [
                'current_page' => $ratings->currentPage(),
                'total' => $ratings->total(),
                'per_page' => $ratings->perPage(),
            ],
        ]);
    }

    public function rate($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $game = Game::findOrFail($id);
        $user = $request->user();

        $rating = Rating::updateOrCreate(
            ['user_id' => $user->id, 'game_id' => $game->id],
            ['value' => $request->rating, 'user_ip' => $request->ip()]
        );

        $game->updateRating();

        return response()->json([
            'success' => true,
            'rating' => [
                'value' => $rating->value,
                'game_avg_rating' => $game->rating_avg,
                'game_rating_count' => $game->rating_count,
            ],
        ]);
    }
}
