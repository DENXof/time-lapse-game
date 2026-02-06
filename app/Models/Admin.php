<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
// или use Illuminate\Database\Eloquent\Model;
// с use Illuminate\Auth\Authenticatable;

class Admin extends Authenticatable
{
    // Если вы используете трейт отдельно:
    // use \Illuminate\Auth\Authenticatable;

    protected $fillable = [
        'name', 'email', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
