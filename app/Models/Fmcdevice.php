<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fmcdevice extends Model
{
    protected $fillable = ['token', 'device', 'user_id']; 

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
