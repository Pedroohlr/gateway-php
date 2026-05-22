<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\PixKeyType;
use App\Traits\{ApithekeyTrait, CashtimeTrait, SimpayTrait, 
    WitetecTrait, CartwaveTrait, ZoompagTrait, MercadoPagoTrait,
    BlupayTrait};
use App\Models\{App, Adquirente, User, SolicitacoesCashOut};
use App\Helpers\Helper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SaqueController extends Controller
{
    use ApithekeyTrait, CashtimeTrait, SimpayTrait, 
    WitetecTrait, CartwaveTrait, ZoompagTrait, 
    MercadoPagoTrait, BlupayTrait;

    public function makePayment(Request $request)
    {
        $data = $request->all();
      	$userId = $request->user->id;
      	$payl = json_encode($data);
        $lockKey = "withdraw_lock_user_{$userId}";
        if (Cache::has($lockKey)) {
                Log::debug('[SAQUE][DADOS][AGUARDAR NOVO SAQUE]'. $lockKey);
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Aguarde antes de realizar um novo saque.',
                ], 429); // HTTP 429 = Too Many Requests
            }
        Cache::put($lockKey, true, now()->addSeconds(60));
    
        Log::debug('MakePayment request: '.json_encode($request->all()));
        
        Helper::calculaSaldoLiquido($request->user->user_id);
        $setting = App::first();

        $user = User::where('id', $request->user->id)->first();
        
        $ultimaTransacaoPendente = SolicitacoesCashOut::where('user_id', $user->id)
        ->orderBy('id', 'desc')
        ->value('status') === 'PENDING';
        
        if($ultimaTransacaoPendente) {
            return response()->json(['status' => 'error', 'message' => 'Existem saques pendentes.']);
        }

        if ((float) $user->saldo < (float)$request->amount) {
            return response()->json(['status' => 'error', 'message' => 'Saldo Insuficiente.'], 401);
        }

        try {
            $validated = $request->validate([
                'amount' =>    ['required'],
                'pixKey' => ['required', 'string'],
                'pixKeyType' =>    ['required', 'string', 'in:cpf,cnpj,email,telefone,aleatoria'],
                'baasPostbackUrl' =>    ['required', 'string']
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422); // Status code 422 para erros de validação
        }

        if ($request->amount < $setting->saque_minimo) {
            $saqueminimo = "R$ " . number_format($setting->saque_minimo, '2', ',', '.');
            return response()->json([
                'status' => 'error',
                'message' => "O saque mínimo é de $saqueminimo.",
            ], 401);
        }


        $adquirente = Adquirente::where('status', 1)->first()['adquirente'];
        
        switch($adquirente){
            case 'cashtime':
                $response = self::requestPaymentCashtime($request);
                break;
            case 'cartwave':
                $response = self::requestPaymentCartwave($request);
                break;
            case 'apithekey':
                $response = self::requestPaymentApithekey($request);
                break;
            case 'simpay':
                $response = self::requestPaymentSimpay($request);
                break;
             case 'witetec':
                $response = self::requestPaymentWitetec($request);
                break;
             case 'zoompag':
                $response = self::requestPaymentZoompag($request);
                break;
             case 'mercadopago':
                $response = self::requestPaymentMercadopago($request);
                break;
             case 'blupay':
                $response = self::requestPaymentBlupay($request);
                break;
        }
         Log::debug('REPSONSE WITHDRAW ADQUIRENTE: '.json_encode($response));
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
}
