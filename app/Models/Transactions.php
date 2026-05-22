<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{

    protected $table = "users_key";

    protected $fillable = [
        "user_id",
        "price",
        "status",
        "reference",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }//
}