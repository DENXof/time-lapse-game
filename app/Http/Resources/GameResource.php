<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'release_year' => $this->release_year,
            'developer' => $this->developer,
            'publisher' => $this->publisher,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'platform' => $this->platform,
            'steam_url' => $this->getSteamUrl(),
            'cover_image' => $this->cover_image ? asset('storage/' . $this->cover_image) : null,
            'views_count' => $this->views_count,
            'rating' => [
                'avg' => (float) $this->rating_avg,
                'count' => $this->rating_count
            ],
            'genre' => $this->genre ? [
                'id' => $this->genre->id,
                'name' => $this->genre->name,
            ] : null,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
