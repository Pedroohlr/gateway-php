<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Smtp extends Model
{
    protected $table = 'smtp';

    protected $fillable = [
        'host',
        'port',
        'user',
        'pass',
        'color',
        'image',
        'auth_title',
        'auth_message',
    ];
}
