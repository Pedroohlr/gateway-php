<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\{ApithekeyTrait, CashtimeTrait, SimpayTrait, 
    WitetecTrait, CartwaveTrait, ZoompagTrait, MercadopagoTrait,
    BlupayTrait};
use App\Models\{App, Adquirente, Solicitacoes};

class DepositController extends Controller
{
    use ApithekeyTrait, CashtimeTrait, SimpayTrait, 
    WitetecTrait, CartwaveTrait, ZoompagTrait, 
    MercadopagoTrait, BlupayTrait;

   
    public function makeDeposit(Request $request)
    {
        $setting = App::first();

        if ($setting->deposito_minimo > 0 && $request->amount < $setting->deposito_minimo) {
            $valorret = number_format($setting->deposito_minimo, '2', ',', '.');
            return response()->json([
                'status' => 'error',
                'message' => "O valor mínimo de depósito é de R$ $valorret."
            ], 401);
        }
        try {
            $validated = $request->validate([
                'amount' => ['required'],
                'debtor_name' => ['required', 'string'],
                'email' => ['required', 'string', 'email'],
                'debtor_document_number' => ['required', 'string'],
                'phone' => ['required', 'string'],
                'method_pay' => ['required', 'string'],
                'postback' => ['required', 'string'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422); // Status code 422 para erros de validação
        }

        
        $adquirente = Adquirente::where('status', 1)->first()['adquirente'];
   
        switch($adquirente){
            case 'cashtime':
                $response = self::requestDepositCashtime($request);
                break;
            case 'cartwave':
                $response = self::requestDepositCartwave($request);
                break;
            case 'apithekey':
                $response = self::requestDepositApithekey($request);
                break;
             case 'simpay':
                $response = self::requestDepositSimpay($request);
                break;
             case 'witetec':
                $response = self::requestDepositWitetec($request);
                break;
             case 'zoompag':
                $response = self::requestDepositZoompag($request);
                break;
             case 'mercadopago':
                $response = self::requestDepositMercadopago($request);
                break;
             case 'blupay':
                $response = self::requestDepositBlupay($request);
                break;
        }
        
        if(is_null($response)){
            return response()->json([
                'status' => 'error',
                'message' => 'Erro de transação',
                'errors' => ['msg' => "Tente novamente..."]
            ], 422); // Status code 422 para erros de validação
        }
        // Se passar pela validação, processar o depósito
        return response()->json($response['data'], $response['status']);
    }

    public function statusDeposito(Request $request)
    {
        $deposit = Solicitacoes::where('idTransaction', $request->idTransaction)->first();
        return response()->json(['status' => $deposit->status]);
    }
}
