<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fcm extends Model
{
    protected $table = 'fcm';

    protected $fillable = [
        'title',
        'body',
        'title_cashout',
        'body_cashout',

    ];
}
