<?php

namespace App\Http\Controllers\Admin\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\Infracoes;
use Illuminate\Http\Request;
use App\Models\Solicitacoes;
use App\Models\ConfirmarDeposito;
use App\Models\SolicitacoesCashOut;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransacoesController extends Controller
{
    public function index(Request $request)
    {
        $limit = 10;

        // Página atual
        $page = $request->input('page', 1);
        $offset = ($page - 1) * $limit;

        $now = Carbon::now();

        $todayStart = $now->startOfDay();
        $todayEnd = $now->endOfDay();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfWeek = $now->copy()->startOfWeek();

        // Valores de depósitos
        $depositsPaidOutToday = Solicitacoes::where('status', 'PAID_OUT')
            ->whereBetween('date', [$todayStart, $todayEnd])
            ->sum('amount');

        $depositsPaidOutMonth = Solicitacoes::where('status', 'PAID_OUT')
            ->where('created_at', '>=', $startOfMonth)
            ->sum('amount');

        $depositsPaidOutTotal = Solicitacoes::where('status', 'PAID_OUT')->sum('amount');

        $pixGeneratedTotal = Solicitacoes::whereIn('status', ['PAID_OUT', 'WAITING_FOR_APPROVAL'])
            ->sum('amount');

        $totalRecords = DB::table('solicitacoes')
            ->where('status', "PAID_OUT")->count();
        $totalPages = ceil($totalRecords / $limit);

        // Consultar os registros com paginação
        $deposits = DB::table('solicitacoes')
            //->where('status', "PAID_OUT")
            ->orderByDesc('date')
            ->get();


        $transacoes_aprovadas = Solicitacoes::where('status', 'PAID_OUT')->count() + SolicitacoesCashOut::where('status', 'COMPLETED')->count();
        $lucro_liquido_hoje = Solicitacoes::where('status', 'PAID_OUT')->whereDate('date', Carbon::today())->sum("taxa_cash_in") + SolicitacoesCashOut::where('status', 'COMPLETED')->whereDate('date', Carbon::today())->sum("taxa_cash_out");
        $lucro_liquido_mes = Solicitacoes::where('status', 'PAID_OUT')->whereMonth('date', Carbon::now()->month)->whereYear('date', Carbon::now()->year)->sum("taxa_cash_in") + SolicitacoesCashOut::where('status', 'COMPLETED')->whereMonth('date', Carbon::now()->month)->whereYear('date', Carbon::now()->year)->sum("taxa_cash_out");
        $lucro_liquido_total = Solicitacoes::where('status', 'PAID_OUT')->sum("taxa_cash_in") + SolicitacoesCashOut::where('status', 'COMPLETED')->sum("taxa_cash_out");

        $transacoes_aprovadas = Solicitacoes::where('status', 'PAID_OUT')->count();
        $valor_aprovado_hoje = Solicitacoes::where('status', 'PAID_OUT')->whereDate('date', Carbon::today())->sum('amount') + SolicitacoesCashOut::where('status', 'COMPLETED')->whereDate('date', Carbon::today())->sum('amount');
        $valor_aprovado_mes = Solicitacoes::where('status', 'PAID_OUT')->whereMonth('date', Carbon::now()->month)->whereYear('date', Carbon::now()->year)->sum('amount') + SolicitacoesCashOut::where('status', 'COMPLETED')->whereMonth('date', Carbon::now()->month)->whereYear('date', Carbon::now()->year)->sum('amount');
        $valor_aprovado_total = Solicitacoes::where('status', 'PAID_OUT')->sum('amount') + SolicitacoesCashOut::where('status', 'COMPLETED')->sum('amount');


        return view("admin.financeiro.transacoes", compact(
            "transacoes_aprovadas",
            "lucro_liquido_hoje",
            "lucro_liquido_mes",
            "lucro_liquido_total",
            "transacoes_aprovadas",
            "valor_aprovado_hoje",
            "valor_aprovado_mes",
            "valor_aprovado_total",

            "depositsPaidOutToday",
            "depositsPaidOutMonth",
            "depositsPaidOutTotal",
            "pixGeneratedTotal",
            'deposits',
            'totalPages',
            'page'
        ));
    }

    public function infracoes(Request $request)
    {
        $infracoes = Infracoes::get();

        return view('admin.infracoes', compact('infracoes'));
    }

    public function marcarMed(Request $request)
    {
        $data = $request->all();
        $id = $data['solicitacao_id'];
        $solicitacao = Solicitacoes::where('id', $id)->first();
        if ($solicitacao) {
            return back()->with('warning', 'Transação não encontrada.');
        }

        $solicitacao->update(['status' => 'MED']);

        $infracao = Infracoes::where('idTransaction', $solicitacao->idTransaction)->first();
        if (!$infracao) {
            Infracoes::create([
                'amount' => $solicitacao->amount,
                'idTransaction' => $solicitacao->idTransaction,
                'user_id' => $solicitacao->user->id,
                'transaction_id' => $solicitacao->id
            ]);
        } else {
            $infracao->update([
                'status' => "OPEN"
            ]);
        }
        return back()->with('success', 'Transação marcada como MED.');
    }

    public function removerMed(Request $request)
    {
        $data = $request->all();
        $id = $data['solicitacao_id'];
        $solicitacao = Solicitacoes::where('id', $id)->first();
        if ($solicitacao) {
            return back()->with('warning', 'Transação não encontrada.');
        }

        $solicitacao->update(['status' => 'PAID_OUT']);
        $infracao = Infracoes::where('idTransaction', $solicitacao->idTransaction)->first();
        if ($infracao) {
            $infracao->update(['status' => 'RESOLVED']);
        }

        return back()->with('success', 'Transação marcada como PAGO.');
    }
}
