<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Solicitacoes;
use App\Models\SolicitacoesCashOut;
use App\Models\App;
use App\Models\User;
use App\Models\AdSimpay;
use Faker\Factory as FakerFactory;
use App\Helpers\Helper;

trait SimpayTrait
{
    protected static string $apiKey;
    protected static string $urlCashIn;
    protected static string $urlCashOut;
    protected static string $taxaCashIn;
    protected static string $taxaCashOut;

    protected static function generateCredentialsSimpay()
    {

        $setting = AdSimpay::first();
        if (!$setting) {
            return false;
        }

        
        self::$urlCashIn = $setting->url_cash_in;
        self::$urlCashOut = $setting->url_cash_out;
        self::$taxaCashIn = $setting->taxa_pix_cash_in;
        self::$taxaCashOut = $setting->taxa_pix_cash_out;
        self::$apiKey = $setting->x_api_key; 
       
        return true;
    }

    public static function requestDepositSimpay($data)
    {
        if (self::generateCredentialsSimpay()) {
            $client_ip = $data->ip();
            
            
            $external_id = uniqid();
            $document = Helper::generateValidCpf();

            $payload = [
                "payment_method" => "pix",
                "postback_url"   => url("simpay/callback/deposit"),
                "tax_cost" => 0,
                "discount" => 0,
                "delivery_cost" => 0,
                "products" => [
                    [
                        "id" => "1",
                        "name" => "Product X",
                        "price" => $data->amount,
                        "amount" => 1,
                        "is_digital" => false
                    ]
                ],
                "customer" => [
                    "name"     => $data->debtor_name,
                    "email"    => $data->email,
                    "phone_number" => "1190000000",
                    "official_id_type" => "CPF",
                    "official_id_number" => str_replace(['.','-'],'',$document)
                ]
            ];


            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-API-Token' => self::$apiKey,
            ])->post(self::$urlCashIn, $payload);
//dd($response->json());
            if ($response->successful()) {

                $responseData = $response->json()['sale']['payment'];
                $setting = App::first();
                $user = $data->user;
                $taxafixa = $user->taxa_cash_in_fixa;

                $taxatotal = ((float)$data->amount * (float)$user->taxa_cash_in / 100);
                $deposito_liquido = (float)$data->amount - $taxatotal;
                $taxa_cash_in = $taxatotal;
                $descricao = "PORCENTAGEM";

                if ($taxafixa <= 0 && (float)$taxatotal < (float)$setting->baseline) {
                    $deposito_liquido = (float)$data->amount - (float)$setting->baseline;
                    $taxa_cash_in = (float)$setting->baseline;
                    $descricao = "FIXA";
                }

                $deposito_liquido = $deposito_liquido - $taxafixa;
                $taxa_cash_in = $taxa_cash_in + $taxafixa;
                

                $date = Carbon::now();

            
                $cashin = [
                    "user_id"                       => $data->user->username,
                    "externalreference"             => $responseData['id'],
                    "amount"                        => $data->amount,
                    "client_name"                   => $data->debtor_name,
                    "client_document"               => $document,
                    "client_email"                  => $data->email,
                    "date"                     => $date,
                    "status"                        => 'WAITING_FOR_APPROVAL',
                    "idTransaction"                 => $responseData['id'],
                    "deposito_liquido"              => $deposito_liquido,
                    "qrcode_pix"                    => $responseData['details']['response']['qrcode']['content'],
                    "paymentcode"                   => $responseData['details']['response']['qrcode']['content'],
                    "paymentCodeBase64"             => $responseData['details']['response']['qrcode']['content'],
                    "adquirente_ref"                => 'simpay',
                    "taxa_cash_in"                  => $taxa_cash_in,
                    "taxa_pix_cash_in_adquirente"   => self::$taxaCashIn,
                    "taxa_pix_cash_in_valor_fixo"   => $taxafixa,
                    "client_telefone"               => $data->phone,
                    "executor_ordem"                => 'simpay',
                    "descricao_transacao"           => $descricao,
                    "callback"                      => $data->postback,
                    "split_email"                   => null,
                    "split_percentage"              => null,
                ];

                Solicitacoes::create($cashin);

                return [
                    "data" => [
                        "idTransaction" => $responseData['id'],
                        "qrcode" =>$responseData['details']['response']['qrcode']['content'],
                        "qr_code_image_url" => 'https://quickchart.io/qr?text='.$responseData['details']['response']['qrcode']['content']
                    ],
                    "status" => 200
                ];
            }
        } else {
            return [
                "data" => [
                    'status' => 'error'
                ],
                "status" => 401
            ];
        }
    }

    public static function requestPaymentSimpay($request)
    {
        
        $user = User::where('id', $request->user->id)->first();

        $setting = App::first();
        $taxafixa = $user->taxa_cash_out_fixa ?? 0;
        $amount = $request->amount;
        
        $taxatotal = ((float)$request->amount * (float)$user->taxa_cash_out / 100);
        $cashout_liquido = (float)$request->amount - $taxatotal;
        $taxa_cash_out = $taxatotal;
        $descricao = "PORCENTAGEM";

        $cashout_liquido = $cashout_liquido - $taxafixa;
        $taxa_cash_out = $taxa_cash_out + $taxafixa;

        if ($user->saldo < $cashout_liquido) {
            return response()->json([
                'status' => 'error',
                'message' => "Saldo insuficiente.",
            ], 401);
        }
        
       //dd($automatico);
      // dd((float) $setting->limite_saque_automatico, (float) $cashout_liquido > (float) $setting->limite_saque_automatico, $limite_automatico);
        $date = Carbon::now();

       $automatico = (float) $setting->limite_saque_automatico > 0 && (float) $amount <= (float) $setting->limite_saque_automatico;
        if ($automatico == false) {
            $request->baasPostbackUrl = 'web';
            //Helper::incrementAmount($user, $request->amount, 'valor_saque_pendente');
            //Helper::decrementAmount($user, $cashout_liquido, 'saldo');

            return self::generateTransactionPaymentManualSimpay($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user);
        }
        
        $hoursLimit = false;
        $hourLimitWithdraw = $setting->hour_limit_withdraw;
        $liberarSaquePorHorario = is_null($hourLimitWithdraw) ? true : Carbon::now()->lt(Carbon::today()->setHour((int) $hourLimitWithdraw));
       
         if ($liberarSaquePorHorario == false) {
            $request->baasPostbackUrl = 'web';
            //Helper::incrementAmount($user, $request->amount, 'valor_saque_pendente');
            //Helper::decrementAmount($user, $cashout_liquido, 'saldo');

            return self::generateTransactionPaymentManualSimpay($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user);
        }
        

        if (self::generateCredentialsSimpay()) {
            $callback = url("simpay/callback/withdraw");
            $client_ip = $request->ip();
            
            
            $external_id = uniqid();
            
            $pixKey = $request->pixKey;
            if(strtoupper($request->pixKeyType) == 'CPF' || strtoupper($request->pixKeyType) == 'CNPJ'){
                $pixKey = str_replace(['.','-','/'], '', $pixKey);
            }
            
             if($request->pixKeyType == "telefone"){
                $request->pixKeyType = "PHONE";
            }
            
                if($user->saldo < $cashout_liquido){
                    $taxatotal = ((float)$user->saldo * (float)$user->taxa_cash_out / 100);
                    $cashout_liquido = (float)$user->saldo - $taxatotal;
                    $taxa_cash_out = $taxatotal;
                    $descricao = "PORCENTAGEM";
            
                    $cashout_liquido = $cashout_liquido - $taxafixa;
                    $taxa_cash_out = $taxa_cash_out + $taxafixa;
                    
                    $amount = $cashout_liquido;
                    
                }

            $payload = [
                "amount"        => floatval($cashout_liquido),
                "key"           => $pixKey,
                "type"          => strtoupper($request->pixKeyType),
                "postback_url"  => $callback
            ];
            
             \Log::debug('PAYLOAD REQUEST WITHDRAW SIMPAY: '.json_encode($payload));

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-API-Token' => self::$apiKey,
            ])->get(self::$urlCashOut, $payload);
            
             \Log::debug('REPSONSE WITHDRAW SIMPAY BODY: '.json_encode($response->json()));
            //dd($response->json());

            if ($response->successful()) {
                //Helper::incrementAmount($user, $request->amount, 'valor_saque_pendente');
                //Helper::decrementAmount($user, $cashout_liquido, 'saldo');

                $name = "Cliente de ".$request->user->name;
                $pixKey = $request->pixKey;

                switch ($request->pixKeyType) {
                    case 'cpf':
                    case 'cnpj':
                    case 'phone':
                        $pixKey = preg_replace('/[^0-9]/', '', $pixKey);
                        break;
                }

                $responseData = $response->json();
                
                $status = 'PENDING';
                if(isset($responseData['withdrawal']['status']) && $responseData['withdrawal']['status'] == "COMPLETED"){
                    $status = 'COMPLETED';
                }

                $pixcashout = [
                    "user_id"               => $request->user->username,
                    "externalreference"     => $responseData['id'],
                    "amount"                => $amount,
                    "beneficiaryname"       => $name,
                    "beneficiarydocument"   => $pixKey,
                    "pix"                   => $pixKey,
                    "pixkey"                => strtolower($request->pixKeyType),
                    "date"                  => $date,
                    "status"                => $status,
                    "type"                  => "PIX",
                    "idTransaction"         => $responseData['id'],
                    "taxa_cash_out"         => $taxa_cash_out,
                    "cash_out_liquido"      => $cashout_liquido,
                    "end_to_end"            => $responseData['id'],
                    "callback"              => $request->baasPostbackUrl,
                    "descricao_transacao"   => $descricao,
                    "adquirente_ref"                => 'simpay',
                    "taxa_pix_cash_out_adquirente"  => self::$taxaCashOut
                ];

                $cashout = SolicitacoesCashOut::create($pixcashout);
                
                return [
                    "status" => 200,
                    "data" => [
                        "id"                => $responseData['id'],
                        "amount"            => $request->amount,
                        "pixKey"            => $request->pixKey,
                        "pixKeyType"        => $request->pixKeyType,
                        "withdrawStatusId"  => "PendingProcessing",
                        "createdAt"         => $responseData['createdAt'] ?? $date,
                        "updatedAt"         => $responseData['updatedAt'] ?? $date
                    ]
                ];
            }
        } else {
            return [
                "status" => 200,
                "data" => [
                    "status" => "error"
                ]
            ];
        }
    }

    protected static function generateTransactionPaymentManualSimpay($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user)
    {
        self::generateCredentialsSimpay();
        
        $idTransaction = Str::uuid()->toString();

        $name = "Cliente de " .$request->user->name;

        $pixKey = $request->pixKey;

        switch ($request->pixKeyType) {
            case 'cpf':
            case 'cnpj':
            case 'phone':
                $pixKey = preg_replace('/[^0-9]/', '', $pixKey);
                break;
        }

        $pixcashout = [
            "user_id"               => $request->user->username,
            "externalreference"     => $idTransaction,
            "amount"                => $request->amount,
            "beneficiaryname"       => $name,
            "beneficiarydocument"   => $pixKey,
            "pix"                   => $pixKey,
            "pixkey"                => strtolower($request->pixKeyType),
            "date"                  => $date,
            "status"                => "PENDING",
            "type"                  => "PIX",
            "idTransaction"         => $idTransaction,
            "taxa_cash_out"         => $taxa_cash_out,
            "cash_out_liquido"      => $cashout_liquido,
            "end_to_end"            => $idTransaction,
            "callback"              => $request->baasPostbackUrl,
            "descricao_transacao"   => "WEB",
            "adquirente_ref"                => 'simpay',
            "taxa_pix_cash_out_adquirente"  => self::$taxaCashOut
        ];

        $cashout = SolicitacoesCashOut::create($pixcashout);

        return [
            "status" => 200,
            "data" => [
                "id"                => $idTransaction,
                "amount"            => $request->amount,
                "pixKey"            => $request->pixKey,
                "pixKeyType"        => $request->pixKeyType,
                "withdrawStatusId"  => "PendingProcessing",
                "createdAt"         => $date,
                "updatedAt"         => $date
            ]
        ];
    }

    public static function liberarSaqueManualSimpay($id)
    {
        if (self::generateCredentialsSimpay()) {
            $cashout = SolicitacoesCashOut::where('id', $id)->first();
            $callback = url("simpay/callback/withdraw");
          
            $pixkeytype = strtoupper($cashout->pixkey);
            
            if($pixkeytype == "TELEFONE"){
                $pixkeytype = "PHONE";
            }
            
            $external_id = uniqid();
    
            $pixKey = $cashout->pixkey;
            if(strtoupper($pixkeytype) == 'CPF' || strtoupper($pixkeytype) == 'CNPJ'){
                $pixKey = str_replace(['.','-','/'], '', $pixKey);
            }
            
            
             $payload = [
                "amount"        => floatval($cashout->cash_out_liquido),
                "key"           => strtoupper($cashout->pix),
                "type"          => strtoupper($pixkeytype),
                "postback_url"  => $callback
            ];
            
           // dd($payload);
             \Log::debug('PAYLOAD REQUEST WITHDRAW SIMPAY: '.json_encode($payload));
//dd($payload);
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-API-Token' => self::$apiKey,
            ])->get(self::$urlCashOut, $payload);
            
 \Log::debug('REPSONSE WITHDRAW SIMPAY BODY: '.json_encode($response->json()));
 
           
            if ($response->successful()) {
                $responseData = $response->json();
                $status = 'PENDING';
                if(isset($responseData['status']) && $responseData['status'] == "COMPLETED"){
                    $status = 'COMPLETED';
                }
                $pixcashout = [
                    "status"                => $status,
                    "externalreference"     => $responseData['id'],
                    "idTransaction"         => $responseData['id'],
                    "end_to_end"            => $responseData['id'],
                    "descricao_transacao"   => "LIBERADOADMIN"
                ];

                $cashout = SolicitacoesCashOut::where('id', $id)->update($pixcashout);
                
                return back()->with('success', 'Pedido de saque enviado com sucesso!');
            } else {
                $message = $response->json()['message'];
                return back()->with('error', $message ?? 'Houve um erro ao liberar saque.');
            }
        }
    }
}
