<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Infracoes;
use App\Models\Solicitacoes;
use App\Services\WitetecService;
use App\Traits\WitetecTrait;
use App\Traits\ZoompagTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SolicitacoesCashOut;
use Carbon\Carbon;

class RelatoriosControlller extends Controller
{
    public function pixentrada(Request $request)
    {
        $userId = Auth::user()->user_id;
        $dataHoje = Carbon::today()->toDateString();
        $mesAtual = Carbon::now()->format('Y-m');

        $valorAprovadoHoje = DB::table('solicitacoes')
            ->where('status', 'PAID_OUT')
            ->whereDate('date', $dataHoje)
            ->where('user_id', $userId)
            ->sum('amount');

        $valorAprovadoMes = DB::table('solicitacoes')
            ->where('status', 'PAID_OUT')
            ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$mesAtual])
            ->where('user_id', $userId)
            ->sum('amount');

        $valorAprovadoTotal = DB::table('solicitacoes')
            ->where('status', 'PAID_OUT')
            ->where('user_id', $userId)
            ->sum('amount');

        $valorDepositoAprovadoHoje = DB::table('solicitacoes')
            ->where('status', 'PAID_OUT')
            ->whereDate('date', $dataHoje)
            ->where('user_id', $userId)
            ->sum('deposito_liquido');

        $valorDepositoAprovadoMes = DB::table('solicitacoes')
            ->where('status', 'PAID_OUT')
            ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$mesAtual])
            ->where('user_id', $userId)
            ->sum('deposito_liquido');

        $valorDepositoAprovadoTotal = DB::table('solicitacoes')
            ->where('status', 'PAID_OUT')
            ->where('user_id', $userId)
            ->sum('deposito_liquido');

        $totalaprovadasHoje = DB::table('solicitacoes')
            ->where('status', 'PAID_OUT')
            ->whereDate('date', $dataHoje)
            ->where('user_id', $userId)
            ->count();

        $totalaprovadasMes = DB::table('solicitacoes')
            ->where('status', 'PAID_OUT')
            ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$mesAtual])
            ->where('user_id', $userId)
            ->count();

        $totalaprovadas = DB::table('solicitacoes')
            ->where('status', 'PAID_OUT')
            ->where('user_id', $userId)
            ->count();

        $totalsolicitacoes = DB::table('solicitacoes')
            ->where('user_id', $userId)
            ->count();

        $dataInicio = $request->input('data_inicio');
        $dataFim = $request->input('data_fim');

        // Configurações de paginação
        $limit = 1000000000; // Número de registros por página
        $page = $request->input('page', 1); // Página atual
        $offset = ($page - 1) * $limit;

        // Consulta para obter a soma filtrada com status COMPLETED
        $filteredQuery = DB::table('solicitacoes')
            ->where('user_id', $userId)
            ->where('status', 'PAID_OUT');

        if (!empty($dataInicio) && !empty($dataFim)) {
            $filteredQuery->whereBetween('date', [$dataInicio, $dataFim]);
        }


        $total_cash_in_liquido_filtrado = $filteredQuery->sum('deposito_liquido');
        $total_cash_in_bruto_filtrada = $filteredQuery->sum('amount');

        // Consulta para obter o número total de registros, ajustando para o filtro de datas
        $countQuery = DB::table('solicitacoes')
            ->where('user_id', $userId)
            ->where('status', 'PAID_OUT');

        if (!empty($dataInicio) && !empty($dataFim)) {
            $countQuery->whereBetween('date', [$dataInicio, $dataFim]);
        }

        $totalRecords = $countQuery->count();
        $totalPages = ceil($totalRecords / $limit);

        // Consulta para obter os registros com paginação e filtro de data
        $transactions = DB::table('solicitacoes')
            ->where('user_id', $userId)
            ->when($dataInicio && $dataFim, function ($query) use ($dataInicio, $dataFim) {
                return $query->whereBetween('date', [$dataInicio, $dataFim]);
            })
            ->orderBy('id', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();


        return view("profile.pixentrada", compact(
            'valorAprovadoHoje',
            'valorAprovadoMes',
            'valorAprovadoTotal',
            'valorDepositoAprovadoHoje',
            'valorDepositoAprovadoMes',
            'valorDepositoAprovadoTotal',
            'totalaprovadasHoje',
            'totalaprovadasMes',
            'totalaprovadas',
            'totalsolicitacoes',
            "transactions",
            "total_cash_in_liquido_filtrado",
            "total_cash_in_bruto_filtrada",
            "totalPages",
            "page",
            "dataInicio",
            "dataFim",
        ));
    }

    public function pixsaida(Request $request)
    {
        $userId = Auth::user()->user_id;
        // Data e mês atuais
        $dataHoje = Carbon::now()->format('Y-m-d');
        $mesAtual = Carbon::now()->format('Y-m');

        // Contagem de transações aprovadas hoje
        $totalaprovadasHoje = SolicitacoesCashOut::where('status', 'COMPLETED')
            ->whereDate('date', $dataHoje)
            ->where('user_id', $userId)
            ->count();

        // Contagem de transações aprovadas no mês
        $totalaprovadasMes = SolicitacoesCashOut::where('status', 'COMPLETED')
            ->whereYear('date', Carbon::now()->year)
            ->whereMonth('date', Carbon::now()->month)
            ->where('user_id', $userId)
            ->count();

        // Contagem total de transações aprovadas
        $totalaprovadas = SolicitacoesCashOut::where('status', 'COMPLETED')->count();

        // Contagem total de transações (independente do status)
        $totalsolicitacoes = SolicitacoesCashOut::where('user_id', $userId)->count();

        // Valor total aprovado hoje (COMPLETED)
        $valorAprovadoHoje = SolicitacoesCashOut::where('status', 'COMPLETED')
            ->whereDate('date', $dataHoje)
            ->where('user_id', $userId)
            ->sum('amount') ?: 0;

        // Valor total aprovado no mês (COMPLETED)
        $valorAprovadoMes = SolicitacoesCashOut::where('status', 'COMPLETED')
            ->whereYear('date', Carbon::now()->year)
            ->whereMonth('date', Carbon::now()->month)
            ->where('user_id', $userId)
            ->sum('amount') ?: 0;

        // Valor total aprovado (COMPLETED)
        $valorAprovadoTotal = SolicitacoesCashOut::where('status', 'COMPLETED')->where('user_id', $userId)->sum('amount') ?: 0;

        // Valor total de saque aprovado hoje (COMPLETED)
        $valorSaqueAprovadoHoje = SolicitacoesCashOut::where('status', 'COMPLETED')
            ->whereDate('date', $dataHoje)
            ->where('user_id', $userId)
            ->sum('cash_out_liquido') ?: 0;

        // Valor total de saque aprovado no mês (COMPLETED)
        $valorSaqueAprovadoMes = SolicitacoesCashOut::where('status', 'COMPLETED')
            ->whereYear('date', Carbon::now()->year)
            ->whereMonth('date', Carbon::now()->month)
            ->where('user_id', $userId)
            ->sum('cash_out_liquido') ?: 0;

        // Valor total de saque aprovado (COMPLETED)
        $valorSaqueAprovadoTotal = SolicitacoesCashOut::where('status', 'COMPLETED')
            ->where('user_id', $userId)
            ->sum('cash_out_liquido') ?: 0;

        $dataInicio = $request->input('data_inicio');
        $dataFim = $request->input('data_fim');

        // Configurações de paginação
        $limit = 1000000000; // Número de registros por página
        $page = $request->input('page', 1); // Página atual
        $offset = ($page - 1) * $limit;

        // Consulta para obter a soma filtrada com status COMPLETED
        $filteredQuery = DB::table('solicitacoes_cash_out')
            ->where('user_id', $userId)
            ->where('status', 'COMPLETED');

        if (!empty($dataInicio) && !empty($dataFim)) {
            $filteredQuery->whereBetween('date', [$dataInicio, $dataFim])
                ->orderByDesc('created_at');
        }

        $total_cash_out_liquido_filtrado = $filteredQuery->sum('cash_out_liquido');
        $total_cash_out_bruto_filtrada = $filteredQuery->sum('amount');
        $lucro_plataforma_filtrada = $total_cash_out_bruto_filtrada - $total_cash_out_liquido_filtrado;

        // Consulta para obter o número total de registros, ajustando para o filtro de datas
        $countQuery = DB::table('solicitacoes_cash_out')
            ->where('user_id', $userId)
            ->where('status', 'COMPLETED');

        if (!empty($dataInicio) && !empty($dataFim)) {
            $countQuery->whereBetween('date', [$dataInicio, $dataFim]);
        }

        $totalRecords = $countQuery->count();
        $totalPages = ceil($totalRecords / $limit);

        // Consulta para obter os registros com paginação e filtro de data
        $transactions = DB::table('solicitacoes_cash_out')
            ->where('user_id', $userId)
            ->when($dataInicio && $dataFim, function ($query) use ($dataInicio, $dataFim) {
                return $query->whereBetween('date', [$dataInicio, $dataFim]);
            })
            ->orderByDesc('created_at')
            ->get();

        return view("profile.pixsaida", compact(
            "totalaprovadasHoje",
            "totalaprovadasMes",
            "totalaprovadas",
            "totalsolicitacoes",
            "valorAprovadoHoje",
            "valorAprovadoMes",
            "valorAprovadoTotal",
            "valorSaqueAprovadoHoje",
            "valorSaqueAprovadoMes",
            "valorSaqueAprovadoTotal",
            "transactions",
            "total_cash_out_liquido_filtrado",
            "total_cash_out_bruto_filtrada",
            "lucro_plataforma_filtrada",
            "totalPages",
            "page",
            "dataInicio",
            "dataFim",
        ));
    }

    public function infracoes(Request $request)
    {
        $infracoes = auth()->user()->infracoes;
        return view('profile.infracoes', compact('infracoes'));
    }

    public function infracoesDefesa(Request $request)
    {
        $data = $request->except(['_token']);
        $id = $data['infracao_id'];
        $appeal = $data['appeal'] ?? 'Nao foi feita cobranca indevida';

        $infracao = Infracoes::where('id', $id)->first();
        if (!$infracao) {
            return back()->with('error', 'Transação não encontrada.');
        }

        $adquirente = Solicitacoes::where('idTransaction', $infracao->idTransaction)->first()['adquirente_ref'];

        switch ($adquirente) {
            case 'witetec':
                $witetec = WitetecTrait::defesaMedWitetec($id, $appeal);
                if ($witetec['status']) {
                    $infracao->update([
                        'appealReason' => $appeal,
                        'status' => 'UNDER_REVIEW'
                    ]);
                } else {
                    return back()->with('warning', 'Não foi possível enviar sua defesa. Tente novamente mais tarde.');
                }
                break;
            case 'zoompag':
                $witetec = ZoompagTrait::defesaMedZoompag($id, $appeal);
                if ($witetec['status']) {
                    $infracao->update([
                        'appealReason' => $appeal,
                        'status' => 'UNDER_REVIEW'
                    ]);
                } else {
                    return back()->with('warning', 'Não foi possível enviar sua defesa. Tente novamente mais tarde.');
                }
                break;

            default:
                return back()->with('warning', 'Não é possivel se defender no momento. Entre em contato com seu gerente de conta.');
        }
    }
    public function consulta(Request $request)
    {
        // Pega os filtros de data
        $dataInicio = $request->input('data_inicio');
        $dataFim = $request->input('data_fim');

        // Configurações de paginação
        $limit = 100; // Número de registros por página
        $page = $request->input('page', 1); // Página atual
        $offset = ($page - 1) * $limit;

        // Consulta para obter a soma filtrada com status COMPLETED
        $filteredQuery = DB::table('solicitacoes_cash_out')
            ->where('status', 'COMPLETED');

        if (!empty($dataInicio) && !empty($dataFim)) {
            $filteredQuery->whereBetween('date', [$dataInicio, $dataFim]);
        }

        $total_cash_out_liquido_filtrado = $filteredQuery->sum('cash_out_liquido');
        $total_cash_out_bruto_filtrada = $filteredQuery->sum('amount');
        $lucro_plataforma_filtrada = $total_cash_out_bruto_filtrada - $total_cash_out_liquido_filtrado;

        // Consulta para obter o número total de registros, ajustando para o filtro de datas
        $countQuery = DB::table('solicitacoes_cash_out')
            ->where('status', 'COMPLETED');

        if (!empty($dataInicio) && !empty($dataFim)) {
            $countQuery->whereBetween('date', [$dataInicio, $dataFim])
                ->orderByDesc('created_at');
        }

        $totalRecords = $countQuery->count();
        $totalPages = ceil($totalRecords / $limit);

        // Consulta para obter os registros com paginação e filtro de data
        $transactions = DB::table('solicitacoes_cash_out')
            ->where('status', 'COMPLETED')
            ->when($dataInicio && $dataFim, function ($query) use ($dataInicio, $dataFim) {
                return $query->whereBetween('date', [$dataInicio, $dataFim]);
            })
            ->orderByDesc('date')
            ->offset($offset)
            ->limit($limit)
            ->get();

        return view('profile.consulta', compact(
            "transactions",
            "total_cash_out_liquido_filtrado",
            "total_cash_out_bruto_filtrada",
            "lucro_plataforma_filtrada",
            "totalPages",
            "page",
            "dataInicio",
            "dataFim"
        ));
    }
}
