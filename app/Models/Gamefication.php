<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gamefication extends Model
{
    protected $fillable = [
        'name',
        'desc',
        'min',
        'max',
        'image',
    ];
}
