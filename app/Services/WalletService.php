<?php

namespace App\Services;

use App\Models\App;
use App\Models\Solicitacoes;
use App\Models\SolicitacoesCashOut;
use App\Models\User;


class WalletService
{
    public function __construct()
    {
    }

    public function createSaldoIn(Solicitacoes $solicitacao): bool
    {
        $setting = App::first();

        if (!$setting || !$setting->carteira_lucro) {
            return true;
        }

        $user = User::where('email', $setting->carteira_lucro)->first();

        if (!$user) {
            return true;
        }

        $lucro = floatval($solicitacao->taxa_cash_in)
            - floatval($solicitacao->taxa_pix_cash_in_adquirente);

        if ($lucro <= 0) {
            return true;
        }

        $payload = $solicitacao->toArray();

        unset(
            $payload['id'],
            $payload['created_at'],
            $payload['updated_at']
        );

        $payload['amount'] = $lucro;
        $payload['taxa_cash_in'] = 0;
        $payload['taxa_pix_cash_in_adquirente'] = 0;
        $payload['deposito_liquido'] = $lucro;
        $payload['executor_ordem'] = 'carteira';
        $payload['callback'] = null;
        $payload['idTransaction'] = 'w_' . $solicitacao->idTransaction;
        $payload['externalreference'] = 'w_' . $solicitacao->externalreference;
        $payload['status'] = 'PAID_OUT';
        $payload['user_id'] = $user->user_id;

        Solicitacoes::create($payload);

        return true;
    }

    
    public function createSaldoOut(SolicitacoesCashOut $solicitacao): bool
    {
        $setting = App::first();

        if (!$setting || !$setting->carteira_lucro) {
            return true;
        }

        $user = User::where('email', $setting->carteira_lucro)->first();

        if (!$user) {
            return true;
        }

        $lucro = floatval($solicitacao->taxa_cash_out)
            - floatval($solicitacao->taxa_pix_cash_out_adquirente);

        if ($lucro <= 0) {
            return true;
        }

        $payload = $solicitacao->toArray();

        unset(
            $payload['id'],
            $payload['created_at'],
            $payload['updated_at']
        );

        $payload['amount'] = $lucro;
        $payload['taxa_cash_in'] = 0;
        $payload['taxa_pix_cash_in_adquirente'] = 0;
        $payload['deposito_liquido'] = $lucro;
        $payload['executor_ordem'] = 'carteira';
        $payload['callback'] = null;
        $payload['idTransaction'] = 'w_' . $solicitacao->idTransaction;
        $payload['externalreference'] = 'w_' . $solicitacao->externalreference;
        $payload['status'] = 'COMPLETED';
        $payload['user_id'] = $user->user_id;

        SolicitacoesCashOut::create($payload);

        return true;
    }
}