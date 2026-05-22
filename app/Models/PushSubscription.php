<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushSubscription extends Model
{
    protected $fillable = [
        'endpoint',
        'public_key',
        'auth_token',
        'device_id',
        'device_name',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}