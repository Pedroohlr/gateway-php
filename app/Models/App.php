<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    protected $table = "app";

    protected $fillable = [
        'gateway_name',
        'gateway_logo',
        'gateway_favicon',
        'gateway_banner_home',
        'gateway_color',
        'bg_theme',
        'numero_users',
        'faturamento_total',
        'total_transacoes',
        'visitantes',
        'manutencao',
        'baseline',
        'taxa_cash_in_padrao',
        'taxa_cash_out_padrao',
        'taxa_fixa_padrao',
        'taxa_fixa_padrao_cash_out',
        'sms_url_cadastro_pendente',
        'sms_url_cadastro_ativo',
        'sms_url_notificacao_user',
        'sms_url_redefinir_senha',
        'sms_url_autenticar_admin',
        'taxa_pix_valor_real_cash_in_padrao',
        'limite_saque_mensal',
        'limite_saque_automatico',
        'deposito_minimo',
        'saque_minimo',
        'contato',
        'cnpj',
        'hour_limit_withdraw',
        'carteira_lucro'
    ];
}
