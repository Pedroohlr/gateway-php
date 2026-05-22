<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adquirente extends Model
{
    protected $fillable = [
        "adquirente",
        "status",
        "url",
        "referencia",
    ];
}