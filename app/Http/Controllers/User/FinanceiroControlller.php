<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Solicitacoes;
use App\Models\Retiradas;
use App\Models\App;
use App\Models\User;
use App\Models\SolicitacoesCashOut;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;

class FinanceiroControlller extends Controller
{
    public function index()
    {

        Helper::calculaSaldoLiquido(auth()->user()->user_id);
        $user_id2 = Auth::user()->user_id;
        $user_id = Auth::user()->user_id;

        // Consultar as últimas 4 solicitações do usuário
        $solicitacoes = Solicitacoes::where('user_id', $user_id2)
            ->orderBy('id', 'desc')
            ->limit(4)
            ->get(['id', 'externalreference', 'amount', 'deposito_liquido', 'client_name', 'client_document', 'client_email', 'date', 'status', 'paymentcode']);

        // Consultar o número de solicitações com status 'PAID_OUT' para o usuário
        $totalPaidOut = Solicitacoes::where('user_id', $user_id2)
            ->where('status', 'PAID_OUT')
            ->count();

        // Consultar o número total de solicitações para o usuário
        $totalRequests = Solicitacoes::where('user_id', $user_id2)
            ->count();

        // Consultar a soma dos valores de 'amount' para as solicitações com status 'PAID_OUT' e o user_id correspondente
        $sumAmountPaidOut = Solicitacoes::where('user_id', $user_id2)
            ->where('status', 'PAID_OUT')
            ->sum('amount') ?: 0;

        // Consultar a soma dos valores de 'deposito_liquido' para as solicitações com status 'PAID_OUT' e o user_id correspondente
        $sumDepositoLiquido = Solicitacoes::where('user_id', $user_id2)
            ->where('status', 'PAID_OUT')
            ->sum('deposito_liquido') ?: 0;

        // Consultar a data real mais recente
        $realDate = Solicitacoes::where('user_id', $user_id2)
            ->max('date');

        // Consultar a soma dos saques aprovados para o usuário
        $sumSaquesAprovados = SolicitacoesCashOut::where('user_id', $user_id2)
            ->where('status', 'COMPLETED')
            ->sum('amount') ?: 0;

        $retiradas = Retiradas::where('user_id', $user_id2)
            ->where('status', 0)
            ->count();

        $retiradasPendentes = $retiradas > 0;

        $totalDepositoLiquido = Solicitacoes::where('user_id', $user_id)
            ->where('status', 'PAID_OUT')
            ->sum('deposito_liquido');

        // Soma dos saques aprovados com status "COMPLETED"
        $totalSaquesAprovados = SolicitacoesCashOut::where('user_id', $user_id)
            ->where('status', 'COMPLETED')
            ->sum('cash_out_liquido');

        // Cálculo do saldo líquido
        $saldoliquido = (float) $totalDepositoLiquido - (float) $totalSaquesAprovados;

        $saldoBaixo = $saldoliquido < 5;
        $email = Auth::user()->email;


        $app = App::first();
        $taxa_cash_in = $app->taxa_cash_in ?? 5;
        $taxa_cash_out = $app->taxa_cash_out ?? 5;
        $taxa_fixa_padrao = $app->taxa_fixa_padrao;
        $taxa_fixa_padrao_cash_out = $app->taxa_fixa_padrao_cash_out.
        $user = auth()->user()->fresh();
        
        $saldo = $user->saldo;
        $saldo_bloqueado =$user->valor_saque_pendente;

        auth()->user()->fresh();
        auth()->user()->save();

        return view("profile.financeiro", compact(
            'saldo',
            'saldo_bloqueado',
            'taxa_cash_in',
            'taxa_cash_out',
            'email',
            'retiradasPendentes',
            "solicitacoes",
            'saldoBaixo',
            "totalPaidOut",
            "totalRequests",
            "sumAmountPaidOut",
            "sumDepositoLiquido",
            "realDate",
            "sumSaquesAprovados",
            "saldoliquido",
            "taxa_fixa_padrao",
            "taxa_fixa_padrao_cash_out"
        ));
    }
}
