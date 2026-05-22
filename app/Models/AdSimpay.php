<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdSimpay extends Model
{
    public $table = "ad_simpay";
    
    protected $fillable = [
        'x_api_key',
        'url',
        'url_cash_in',
        'url_cash_out',
        'taxa_pix_cash_in',
        'taxa_pix_cash_out',
        ];
}
