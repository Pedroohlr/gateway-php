<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\{App, Adquirente, AdApiTheKey, Cashtime};
use App\Models\Solicitacoes;
use App\Models\SolicitacoesCashOut;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        
        if(auth()->user()->permission == 1){
            return redirect()->route('admin.dashboard');
        }
        
        Helper::calcularSaldoLiquidoUsuarios();

        /*  $cashin = Solicitacoes::where('user_id', 'Parecido77')->where('status', 'PAID_OUT')->sum('deposito_liquido');
        $cashout = SolicitacoesCashOut::where('user_id', 'Parecido77')->where('status', 'COMPLETED')->sum('amount');
        dd($cashin, $cashout, (floatval($cashin) - floatval($cashout)));
         */
        $app = App::first();
        $adquirente = Adquirente::where('status', 1)->first()->adquirente;
        $total_saldo = Solicitacoes::where('status', 'PAID_OUT')->sum('amount');

        $cadastros_hoje = User::whereDate('data_cadastro', Carbon::today())->count();
        $cadastros_total = User::count();
        $cadastros_analise = User::where('status', 5)->count();
        $cadastros_bloqueados = User::where('banido', 1)->count();
        
        $prefix = env('APP_NAME');
        $prefixFiltro = env('APP_NAME');
        
        $total_saldo_users =  User::where('id', '>', 0)->sum('saldo');
        
        $lucro_total = Solicitacoes::where('status', 'PAID_OUT')->where('externalreference', 'NOT LIKE', "%$prefix%")->sum("taxa_cash_in") + SolicitacoesCashOut::where('status', 'COMPLETED')->where('externalreference', 'NOT LIKE', "%$prefix%")->sum("taxa_cash_out");
        
        $hoje = Carbon::today();
        $mes = Carbon::now()->month;
        $ano = Carbon::now()->year;
        
        /**
         * 1️⃣ LUCRO LÍQUIDO HOJE
         */
        
        // CASH-IN (hoje)
        $cashInHoje = Solicitacoes::where('status', 'PAID_OUT')
            ->where('externalreference', 'NOT LIKE', $prefixFiltro)
            ->whereDate('date', $hoje)
            ->selectRaw('SUM(taxa_cash_in - taxa_pix_cash_in_adquirente) AS total')
            ->value('total');
        
        // CASH-OUT (hoje)
        $cashOutHoje = SolicitacoesCashOut::where('status', 'COMPLETED')
            ->where('externalreference', 'NOT LIKE', $prefixFiltro)
            ->whereDate('date', $hoje)
            ->selectRaw('SUM(taxa_cash_out - taxa_pix_cash_out_adquirente) AS total')
            ->value('total');
        
        $lucro_liquido_hoje = ($cashInHoje ?? 0) + ($cashOutHoje ?? 0);
        
        
        
        /**
         * 2️⃣ LUCRO LÍQUIDO DO MÊS
         */
        
        // CASH-IN (mês)
        $cashInMes = Solicitacoes::where('status', 'PAID_OUT')
            ->where('externalreference', 'NOT LIKE', $prefixFiltro)
            ->whereMonth('date', $mes)
            ->whereYear('date', $ano)
            ->selectRaw('SUM(taxa_cash_in - taxa_pix_cash_in_adquirente) AS total')
            ->value('total');
        
        // CASH-OUT (mês)
        $cashOutMes = SolicitacoesCashOut::where('status', 'COMPLETED')
            ->where('externalreference', 'NOT LIKE', $prefixFiltro)
            ->whereMonth('date', $mes)
            ->whereYear('date', $ano)
            ->selectRaw('SUM(taxa_cash_out - taxa_pix_cash_out_adquirente) AS total')
            ->value('total');
        
        $lucro_liquido_mes = ($cashInMes ?? 0) + ($cashOutMes ?? 0);
        
        /**
         * 3️⃣ LUCRO LÍQUIDO TOTAL
         */
        
        // CASH-IN (total)
        $cashInTotal = Solicitacoes::where('status', 'PAID_OUT')
            ->where('externalreference', 'NOT LIKE', $prefixFiltro)
            ->selectRaw('SUM(taxa_cash_in - taxa_pix_cash_in_adquirente) AS total')
            ->value('total');
        
        // CASH-OUT (total)
        $cashOutTotal = SolicitacoesCashOut::where('status', 'COMPLETED')
            ->where('externalreference', 'NOT LIKE', $prefixFiltro)
            ->selectRaw('SUM(taxa_cash_out - taxa_pix_cash_out_adquirente) AS total')
            ->value('total');
        
        $lucro_liquido_total = ($cashInTotal ?? 0) + ($cashOutTotal ?? 0);
        
        $transacoes_aprovadas = Solicitacoes::where('status', 'PAID_OUT')->count() + SolicitacoesCashOut::where('status', 'COMPLETED')->count();

        $valor_aprovado_hoje = Solicitacoes::where('status', 'PAID_OUT')->whereDate('date', Carbon::today())->sum('amount') + SolicitacoesCashOut::where('status', 'COMPLETED')->whereDate('date', Carbon::today())->sum('amount');
        $valor_aprovado_mes = Solicitacoes::where('status', 'PAID_OUT')->whereMonth('date', Carbon::now()->month)->whereYear('date', Carbon::now()->year)->sum('amount') + SolicitacoesCashOut::where('status', 'COMPLETED')->whereMonth('date', Carbon::now()->month)->whereYear('date', Carbon::now()->year)->sum('amount');
        $valor_aprovado_total = Solicitacoes::where('status', 'PAID_OUT')->sum('amount') + SolicitacoesCashOut::where('status', 'COMPLETED')->sum('amount');


        $lucroDepositos = Solicitacoes::where('status', 'PAID_OUT')->where('externalreference', 'NOT LIKE', "%$prefix%")->sum('deposito_liquido');
        $lucroSaques = SolicitacoesCashOut::where('status', 'COMPLETED')->where('externalreference', 'NOT LIKE', "%$prefix%")->sum('amount');

        $lucro_liquido = $lucroDepositos - $lucroSaques; 
        //$lucro_total - ($lucro_total * $app->taxa_cash_in_padrao / 100);

       
        
        $retiradas_hoje = SolicitacoesCashOut::where('status', 'COMPLETED')->whereDate('date', Carbon::today())->sum('amount');
        $retiradas_mes = SolicitacoesCashOut::where('status', 'COMPLETED')->whereMonth('date', Carbon::now()->month)->whereYear('date', Carbon::now()->year)->sum('amount');
        $retiradas_total = SolicitacoesCashOut::where('status', 'COMPLETED')->sum('amount');

        $retiradas_pendentes = SolicitacoesCashOut::where('status', 'PENDING')->sum('amount');
        
        return view("admin.dashboard", compact(
            'total_saldo',
            'lucro_total',
            'lucro_liquido',
            'lucro_liquido_hoje',
            'lucro_liquido_mes',
            'lucro_liquido_total',
            'transacoes_aprovadas',
            'valor_aprovado_hoje',
            'valor_aprovado_mes',
            'valor_aprovado_total',
            "cadastros_hoje",
            "cadastros_total",
            "cadastros_analise",
            "cadastros_bloqueados",
            "retiradas_hoje",
            "retiradas_mes",
            "retiradas_total",
            "retiradas_pendentes",
            "total_saldo",
            "total_saldo_users",
        ));
    }
}
