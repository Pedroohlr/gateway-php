<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdApiTheKey extends Model
{
    protected $table = "ad_apithekey";
    
    protected $fillable = [
        'client_id',
        'client_secret',
        'url',
        'url_cash_in',
        'url_cash_out',
        'taxa_pix_cash_in',
        'taxa_pix_cash_out',
    ];
}