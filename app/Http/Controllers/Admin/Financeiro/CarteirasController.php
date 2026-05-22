<?php

namespace App\Http\Controllers\Admin\Financeiro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarteirasController extends Controller
{
    public function index()
    {
        $total_em_carteiras = DB::table('users')
            ->sum('saldo') ?: 0;

        // Consultar o total de solicitações pagas
        $totalPaidOut = DB::table('solicitacoes')
            ->where('status', 'PAID_OUT')
            ->sum('amount') ?: 0;

        // Consultar o total de cash outs completados
        $totalCompleted = DB::table('solicitacoes_cash_out')
            ->where('status', 'COMPLETED')
            ->sum('amount') ?: 0;

        // Calcular o total bruto no gateway
        $totalBrutoGateway = $totalPaidOut - $totalCompleted;

        $usuarios = DB::table('users')->get();

        // Consulta para obter os 3 usuários com mais saldo (faturamento)
        $topUsuarios = DB::table('users')
            ->orderBy('saldo', 'desc')
            ->limit(3)
            ->get();


        // Passar as variáveis para a view
        return view('admin.financeiro.carteiras', compact(
            'total_em_carteiras',
            'totalPaidOut',
            'totalCompleted',
            'totalBrutoGateway',
            'usuarios',
            'topUsuarios',
        ));
    }
}