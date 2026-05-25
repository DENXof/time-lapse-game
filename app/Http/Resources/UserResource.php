<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar ? asset('storage/' . $this->avatar) : null,
            'bio' => $this->bio,
            'telegram' => $this->telegram,
            'discord' => $this->discord,
            'role' => $this->role,
            'status' => $this->status,
            'created_at' => $this->created_at?->toISOString(),
            'stats' => [
                'favorites_count' => $this->favorites()->count(),
                'ratings_count' => $this->ratings()->count(),
                'comments_count' => $this->comments()->count(),
                'achievements_count' => $this->achievements()->count(),
                'total_points' => $this->total_points ?? 0,
            ],
        ];
    }
}
