<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = ['game_id', 'user_ip', 'value'];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
