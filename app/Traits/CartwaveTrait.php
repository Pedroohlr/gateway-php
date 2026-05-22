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
use App\Models\Cartwave;
use Faker\Factory as FakerFactory;
use App\Helpers\Helper;

trait CartwaveTrait
{
    protected static string $secret;
    protected static string $urlCashIn;
    protected static string $urlCashOut;
    protected static string $taxaCashIn;
    protected static string $taxaCashOut;

    protected static function generateCredentialsCartwave()
    {

        $setting = Cartwave::first();
        if (!$setting) {
            return false;
        }

        self::$secret = $setting->secret;
        self::$urlCashIn = $setting->url_cash_in;
        self::$urlCashOut = $setting->url_cash_out;
        self::$taxaCashIn = $setting->taxa_pix_cash_in;
        self::$taxaCashOut = $setting->taxa_pix_cash_out;

        return true;
    }

    public static function requestDepositCartwave($data)
    {
        if (self::generateCredentialsCartwave()) {
            $client_ip = $data->ip();

            $productid = uniqid();
            $document = Helper::generateValidCpf();

            $payload = [
                "postbackUrl"   => url("cartwave/callback/deposit"),
                "amount" => intval($data->amount * 100)
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'x-authorization-key' => self::$secret,
            ])->post(self::$urlCashIn, $payload);

            if ($response->successful()) {

                $responseData = $response->json();
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
                    "date"                          => $date,
                    "status"                        => 'WAITING_FOR_APPROVAL',
                    "idTransaction"                 => $responseData['id'],
                    "deposito_liquido"              => $deposito_liquido,
                    "qrcode_pix"                    => $responseData['pix']['payload'],
                    "paymentcode"                   => $responseData['pix']['payload'],
                    "paymentCodeBase64"             => $responseData['pix']['payload'],
                    "adquirente_ref"                => 'cartwave',
                    "taxa_cash_in"                  => $taxa_cash_in,
                    "taxa_pix_cash_in_adquirente"   => self::$taxaCashIn,
                    "taxa_pix_cash_in_valor_fixo"   => $taxafixa,
                    "client_telefone"               => $data->phone,
                    "executor_ordem"                => 'cartwave',
                    "descricao_transacao"           => $descricao,
                    "callback"                      => $data->postback,
                    "split_email"                   => null,
                    "split_percentage"              => null,
                ];

                Solicitacoes::create($cashin);

                return [
                    "data" => [
                        "idTransaction" => $responseData['id'],
                        "qrcode" => $responseData['pix']['payload'],
                        "qr_code_image_url" => $responseData['pix']['encodedImage']
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

    public static function requestPaymentCartwave($request)
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
        
        
        $date = Carbon::now();
        
        // Verifica se o valor do saque pode ser processado automaticamente
        $limiteSaque = (float) $setting->limite_saque_automatico;
        $valorSaque = (float) $amount;
        
        $manual = true;
        if($limiteSaque > 0 && $valorSaque > $limiteSaque) $manual = true;
        elseif($limiteSaque == 0) $manual = false;
       //dd($manual);
        if ($manual) {
            $request->baasPostbackUrl = 'web';
            //Helper::incrementAmount($user, $request->amount, 'valor_saque_pendente');
            //Helper::decrementAmount($user, $cashout_liquido, 'saldo');
        
            return self::generateTransactionPaymentManualCartwaveSimpay(
                $request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user
            );
        }
        
        // Verifica se ainda está dentro do horário permitido para saque
        $hourLimitWithdraw = $setting->hour_limit_withdraw;
        $dentroHorario = false;
        if(Carbon::now()->lt(Carbon::today()->setHour((int) $hourLimitWithdraw))) $dentroHorario = true;
        elseif(is_null($hourLimitWithdraw)) $dentroHorario = true;
        
        if (!$dentroHorario) {
            $request->baasPostbackUrl = 'web';
            //Helper::incrementAmount($user, $request->amount, 'valor_saque_pendente');
            //Helper::decrementAmount($user, $cashout_liquido, 'saldo');
        
            return self::generateTransactionPaymentManualCartwaveSimpay(
                $request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user
            );
        }

       // if ($request->baasPostbackUrl === 'web') {
            //Helper::incrementAmount($user, $request->amount, 'valor_saque_pendente');
            //Helper::decrementAmount($user, $cashout_liquido, 'saldo');

        //    return self::generateTransactionPaymentManualCartwave($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user);
      //  }

        if (self::generateCredentialsCartwave()) {
            $callback = url("cartwave/callback/withdraw");
            $client_ip = $request->ip();

            $payload = [
                "amount"            => floatval($cashout_liquido * 100),
                "pixKey"            => $request->pixKey,
                "pixKeyType"        => $request->pixKeyType,
                "baasPostbackUrl"   => $callback
            ];


            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'x-authorization-key' => self::$secret,
            ])->post(self::$urlCashOut, $payload);


            if ($response->successful()) {
                //Helper::incrementAmount($user, $request->amount, 'valor_saque_pendente');
                //Helper::decrementAmount($user, $cashout_liquido, 'saldo');

                $name = "Cliente de " . $request->user->name;
                $responseData = $response->json();

                $pixKey = $request->pixKey;

                switch ($request->pixKeyType) {
                    case 'cpf':
                    case 'cnpj':
                    case 'phone':
                        $pixKey = preg_replace('/[^0-9]/', '', $pixKey);
                        break;
                }


                $pixcashout = [
                    "user_id"                       => $request->user->username,
                    "externalreference"             => $responseData['id'],
                    "amount"                        => $request->amount,
                    "beneficiaryname"               => $name,
                    "beneficiarydocument"           => $pixKey,
                    "pix"                           => $pixKey,
                    "pixkey"                        => strtolower($request->pixKeyType),
                    "date"                          => $date,
                    "status"                        => "PENDING",
                    "type"                          => "PIX",
                    "idTransaction"                 => $responseData['id'],
                    "taxa_cash_out"                 => $taxa_cash_out,
                    "cash_out_liquido"              => $cashout_liquido,
                    "end_to_end"                    => $responseData['id'],
                    "callback"                      => $request->baasPostbackUrl,
                    "descricao_transacao"           => $descricao,
                    "adquirente_ref"                => 'cartwave',
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
                        "withdrawStatusId"  => $responseData["PendingProcessing"] ?? "PendingProcessing",
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

    protected static function generateTransactionPaymentManualCartwave($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user)
    {
        $idTransaction = Str::uuid()->toString();

        $name = "Cliente de " . explode(' ', $request->user->name)[0] . ' ' . explode(' ', $request->user->name)[1];

        $pixKey = $request->pixKey;

        switch ($request->pixKeyType) {
            case 'cpf':
            case 'cnpj':
            case 'phone':
                $pixKey = preg_replace('/[^0-9]/', '', $pixKey);
                break;
        }

        $pixcashout = [
            "user_id"                       => $request->user->username,
            "externalreference"             => $idTransaction,
            "amount"                        => $request->amount,
            "beneficiaryname"               => $name,
            "beneficiarydocument"           => $pixKey,
            "pix"                           => $pixKey,
            "pixkey"                        => strtolower($request->pixKeyType),
            "date"                          => $date,
            "status"                        => "PENDING",
            "type"                          => "PIX",
            "idTransaction"                 => $idTransaction,
            "taxa_cash_out"                 => $taxa_cash_out,
            "cash_out_liquido"              => $cashout_liquido,
            "end_to_end"                    => $idTransaction,
            "callback"                      => $request->baasPostbackUrl,
            "descricao_transacao"           => "WEB",
            "adquirente_ref"                => 'cartwave',
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

    public static function liberarSaqueManualCartwave($id)
    {
        if (self::generateCredentialsCartwave()) {
            $cashout = SolicitacoesCashOut::where('id', $id)->first();
            $callback = url("cartwave/callback/withdraw");

            $payload = [
                "amount"            => floatval($cashout->cash_out_liquido * 100),
                "pixKey"            => $cashout->pix,
                "pixKeyType"        => $cashout->pixkey == 'aleatoria' ? 'random' : $cashout->pixkey,
                "baasPostbackUrl"   => $callback
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'x-authorization-key' => self::$secret,
            ])->post(self::$urlCashOut, $payload);


            if ($response->successful()) {
                $responseData = $response->json();
                $pixcashout = [
                    "externalreference"     => $responseData['id'],
                    "idTransaction"         => $responseData['id'],
                    "end_to_end"            => $responseData['id'],
                    "descricao_transacao"   => "LIBERADOADMIN"
                ];

                $cashout = SolicitacoesCashOut::where('id', $id)->update($pixcashout);
                return back()->with('success', 'Pedido de saque enviado com sucesso!');
            } else {
                return back()->with('error', 'Houve um erro ao liberar saque.');
            }
        }
    }
}
