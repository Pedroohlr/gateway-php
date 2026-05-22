<?php

namespace App\Http\Controllers\Admin\Financeiro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SolicitacoesCashOut;
use Carbon\Carbon;

class SaidasController extends Controller
{
    public function index(Request $request)
    {

        $dataHoje = Carbon::today()->toDateString();
        $mesAtual = Carbon::now()->format('Y-m');

        $totalaprovadasHoje = $this->contarTransacoes('COMPLETED', $dataHoje);
        $totalaprovadasMes = $this->contarTransacoes('COMPLETED', null, $mesAtual);
        $totalaprovadas = $this->contarTransacoes('COMPLETED');
        $totalaprovadas = $this->contarTransacoes();

        $valorAprovadoHoje = $this->somarValores('amount', 'COMPLETED', $dataHoje);
        $valorAprovadoMes = $this->somarValores('amount', 'COMPLETED', null, $mesAtual);
        $valorAprovadoTotal = $this->somarValores('amount', 'COMPLETED');

        $valorSaqueAprovadoHoje = $this->somarValores('cash_out_liquido', 'COMPLETED', $dataHoje);
        $valorSaqueAprovadoMes = $this->somarValores('cash_out_liquido', 'COMPLETED', null, $mesAtual);
        $valorSaqueAprovadoTotal = $this->somarValores('cash_out_liquido', 'COMPLETED');

        $totalsolicitacoes = SolicitacoesCashOut::count();

        $limit = 100000000; // Número de registros por página
        $page = $request->input('page', 1); // Página atual
        $offset = ($page - 1) * $limit;

        // Filtros de data
        $dataInicio = $request->input('data_inicio', '');
        $dataFim = $request->input('data_fim', '');

        // Query para obter a soma filtrada com status COMPLETED
        $query = SolicitacoesCashOut::where('id', '!=', 0);

        if (!empty($dataInicio) && !empty($dataFim)) {
            $query->whereBetween('date', [$dataInicio, $dataFim]);
        }

        $totalResults = $query->selectRaw('SUM(cash_out_liquido) AS total_cash_out_liquido_filtrado, SUM(amount) AS total_cash_out_bruto_filtrada')->first();

        $total_cash_out_liquido_filtrado = $totalResults->total_cash_out_liquido_filtrado ?: 0;
        $total_cash_out_bruto_filtrada = $totalResults->total_cash_out_bruto_filtrada ?: 0;

        $lucro_plataforma_filtrada = $total_cash_out_liquido_filtrado - $total_cash_out_bruto_filtrada;

        // Consulta para obter o número total de registros
        $totalRecords = SolicitacoesCashOut::where('status', 'COMPLETED');

        if (!empty($dataInicio) && !empty($dataFim)) {
            $totalRecords->whereBetween('date', [$dataInicio, $dataFim]);
        }

        $totalRecords = $totalRecords->count();
        $totalPages = ceil($totalRecords / $limit);

        // Consulta para obter os registros com paginação e filtro de data
        $cashOuts = SolicitacoesCashOut::when(!empty($dataInicio) && !empty($dataFim), function ($query) use ($dataInicio, $dataFim) {
                return $query->whereBetween('date', [$dataInicio, $dataFim]);
            })
            ->orderBy('date', 'desc')
            ->paginate($limit);


        return view('admin.financeiro.saidas', compact(
            'totalaprovadasHoje',
            'totalaprovadasMes',
            'totalaprovadas',
            'totalaprovadas',
            'valorAprovadoHoje',
            'valorAprovadoMes',
            'valorAprovadoTotal',
            'valorSaqueAprovadoHoje',
            'valorSaqueAprovadoMes',
            'valorSaqueAprovadoTotal',
            'totalsolicitacoes',
            'cashOuts',
            'total_cash_out_liquido_filtrado',
            'total_cash_out_bruto_filtrada',
            'lucro_plataforma_filtrada',
            'totalPages',
            'page',
            'limit',
            'dataInicio',
            'dataFim'
        ));
    }

    private function contarTransacoes($status = null, $data = null, $mes = null)
    {
        return DB::table('solicitacoes_cash_out')
            ->when($status, fn($query) => $query->where('status', $status))
            ->when($data, fn($query) => $query->whereDate('date', $data))
            ->when($mes, fn($query) => $query->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$mes]))
            ->count();
    }

    private function somarValores($campo, $status = null, $data = null, $mes = null)
    {
        return DB::table('solicitacoes_cash_out')
            ->when($status, fn($query) => $query->where('status', $status))
            ->when($data, fn($query) => $query->whereDate('date', $data))
            ->when($mes, fn($query) => $query->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$mes]))
            ->sum($campo) ?? 0;
    }
}
