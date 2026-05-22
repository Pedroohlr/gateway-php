<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebAuthnCredential extends Model
{
    protected $fillable = ['user_id', 'credential_id', 'public_key', 'sign_count', 'transports', 'name'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
