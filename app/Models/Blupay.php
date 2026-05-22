<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blupay extends Model
{
    protected $table = "blupay";

    protected $fillable = [
        'url',
        'username',
        'password',
        'idempotency_key',
        'taxa_pix_cash_in',
        'taxa_pix_cash_out',
        'tx_billet_fixed',
        'tx_billet_percent',
        'tx_card_fixed',
        'tx_card_percent',
];
}
