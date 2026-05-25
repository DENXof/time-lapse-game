<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AchievementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'icon' => $this->icon,
            'points' => $this->points,
            'type' => $this->type,
            'required_count' => $this->required_count,
            'earned_at' => $this->whenPivotLoaded('user_achievements', fn() => $this->pivot->earned_at?->toISOString()),
        ];
    }
}
