<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Infracoes extends Model
{
    protected $table = 'infracoes';

    protected $fillable = [
        'amount',
        'status',
        'reason',
        'appealReason',
        'idTransaction',
        'createdBy',
        'createdAt',
        'resolvedAt',
        'resolvedBy',
        'transaction_id',
        'user_id'
    ];

    public function solicitacao()
    {
        return $this->belongsTo(Solicitacoes::class, 'transaction_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
