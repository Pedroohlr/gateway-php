<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\BlupayTrait;
use Illuminate\Http\Request;
use App\Models\SolicitacoesCashOut;
use App\Models\{Adquirente, User};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helper;
use App\Traits\{ApithekeyTrait, CashtimeTrait, SimpayTrait, 
    WitetecTrait, CartwaveTrait, ZoompagTrait, MercadoPagoTrait};

class SaquesController extends Controller
{
    public function index(Request $request)
    {
        $limit = 10;

        // Página atual
        $page = $request->input('page', 1);
        $offset = ($page - 1) * $limit;

        $totalRecords = DB::table('solicitacoes_cash_out')
            ->where('descricao_transacao', "WEB")
            ->where('status', 'PENDING')->count();
        $totalPages = ceil($totalRecords / $limit);

        // Consultar os registros com paginação
        $saques = DB::table('solicitacoes_cash_out')
            ->where('descricao_transacao', "WEB")
            ->where('status', 'PENDING')
            ->orderByDesc('id')
            ->offset($offset)
            ->limit($limit)
            ->get();

        return view("admin.aprovar-saques", compact('saques', 'page', 'totalRecords', 'totalPages'));
    }

    public function aprovar($id, Request $request)
    {
        if (auth()->user()->permission != 3) {
            return back()->with("error", "Usuário sem permissões.");
        }

        $adquirente = Adquirente::where('status', 1)->first()['adquirente'];
        
        switch($adquirente){
            case 'cashtime':
               return CashtimeTrait::liberarSaqueManual($id);
            case 'cartwave':
               return CartwaveTrait::liberarSaqueManualCartwave($id);
            case 'apithekey':
                return ApithekeyTrait::liberarSaqueManualApithekey($id);
            case 'simpay':
                return SimpayTrait::liberarSaqueManualSimpay($id);
            case 'witetec':
                return WitetecTrait::liberarSaqueManualWitetec($id);
            case 'zoompag':
                return ZoompagTrait::liberarSaqueManualZoompag($id);
            case 'mercadopago':
                return MercadopagoTrait::liberarSaqueManualMercadopago($id);
            case 'blupay':
                return BlupayTrait::liberarSaqueManualBlupay($id);
        }
        
    }

    public function rejeitar($id, Request $request)
    {
        if (auth()->user()->permission != 3) {
            return back()->with("error", "Usuário sem permissões.");
        }

        $saque = SolicitacoesCashOut::where('id', $id)->first();
        if (!$saque) {
            return back()->with("error", "Solicitação de saque não encontrado.");
        }

        $saque->update(['status' => 'CANCELLED']);
        $user = User::where('user_id', $saque->user_id)->first();
        $user->increment('transacoes_recused', 1);
        $user->save();

        Helper::calculaSaldoLiquido($user->user_id);

        return back()->with('success', 'Solicitação rejeitada com sucesso.');
    }
}
