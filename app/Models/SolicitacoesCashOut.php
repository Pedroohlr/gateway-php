<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitacoesCashOut extends Model
{
    protected $table = "solicitacoes_cash_out";

    protected $fillable = [
        "user_id",
        "externalreference",
        "amount",
        "beneficiaryname",
        "beneficiarydocument",
        "pix",
        "pixkey",
        "date",
        "status",
        "type",
        "idTransaction",
        "taxa_cash_out",
        "taxa_pix_cash_out_adquirente",
        "cash_out_liquido",
        "end_to_end",
        "descricao_transacao",
        "callback",
        "adquirente_ref"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
