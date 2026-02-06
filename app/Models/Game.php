<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'release_year',
        'developer',
        'publisher',
        'description',
        'short_description',
        'cover_image',
        'platform',
        'genre_id'
    ];

    protected $appends = ['era_style', 'decade'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($game) {
            if (empty($game->slug)) {
                $game->slug = Str::slug($game->title);
            }
        });
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function eras()
    {
        return $this->belongsToMany(Era::class, 'game_era')
            ->withPivot('significance')
            ->withTimestamps();
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)
            ->where('is_approved', true)
            ->orderBy('created_at', 'desc');
    }

    public function getEraStyleAttribute()
    {
        if ($this->release_year < 1985)
            return 'pixel';
        if ($this->release_year < 1995)
            return '16bit';
        if ($this->release_year < 2005)
            return '3d-early';
        return 'modern';
    }

    public function getDecadeAttribute()
    {
        return floor($this->release_year / 10) * 10;
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function updateRating()
    {
        $this->rating_avg = $this->ratings()->avg('value') ?? 0;
        $this->rating_count = $this->ratings()->count();
        $this->save();
    }
}
