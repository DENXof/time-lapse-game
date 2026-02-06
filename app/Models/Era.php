<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Era extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'start_year',
        'end_year',
        'description',
        'characteristics',
        'color_primary',
        'color_secondary',
        'font_family',
        'background_image'
    ];

    public function games()
    {
        return $this->belongsToMany(Game::class, 'game_era')
            ->withPivot('significance')
            ->withTimestamps();
    }

    public function getDurationAttribute()
    {
        return $this->end_year - $this->start_year;
    }
}
