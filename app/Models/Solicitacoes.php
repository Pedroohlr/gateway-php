<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitacoes extends Model
{
    protected $table = "solicitacoes";

    protected $fillable = [
        "user_id",
        "externalreference",
        "amount",
        "client_name",
        "client_document",
        "client_email",
        "date",
        "status",
        "idTransaction",
        "deposito_liquido",
        "qrcode_pix",
        "paymentcode",
        "paymentCodeBase64",
        "adquirente_ref",
        "taxa_cash_in",
        "taxa_pix_cash_in_adquirente",
        "taxa_pix_cash_in_valor_fixo",
        "client_telefone",
        "executor_ordem",
        "descricao_transacao",
        "callback",
        "split_email",
        "split_percentage",
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function infracao()
    {
        return $this->hasOne(Infracoes::class, 'transaction_id', 'id');
    }
}
