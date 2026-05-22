<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pwa extends Model
{
    protected $table = 'pwa';

    protected $fillable = [
        'name',
        'short_name',
        'start_url',
        'display',
        'background_color',
        'theme_color',
        'orientation',
        'icon_192',
        'icon_512',
    ];
}
