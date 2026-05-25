<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'likes_count' => $this->likes_count,
            'created_at' => $this->created_at?->diffForHumans(),
            'created_at_raw' => $this->created_at?->toISOString(),
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'avatar' => $this->user->avatar ? asset('storage/' . $this->user->avatar) : null,
            ],
            'replies' => CommentResource::collection($this->whenLoaded('replies')),
        ];
    }
}
