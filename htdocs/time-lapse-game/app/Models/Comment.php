<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'game_id',
        'author_name',
        'author_ip'
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
