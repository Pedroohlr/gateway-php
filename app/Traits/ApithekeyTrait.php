<?php

namespace App\Traits;

use App\Models\Fcm;
use App\Services\SendNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Solicitacoes;
use App\Models\SolicitacoesCashOut;
use App\Models\App;
use App\Models\User;
use App\Models\AdApiTheKey;
use Faker\Factory as FakerFactory;
use App\Helpers\Helper;

trait ApithekeyTrait
{
    protected static string $accessToken;
    protected static string $urlCashIn;
    protected static string $urlCashOut;
    protected static string $taxaCashIn;
    protected static string $taxaCashOut;

    protected static function generateCredentialsApithekey()
    {

        $setting = AdApiTheKey::first();
        if (!$setting) {
            return false;
        }


        self::$urlCashIn = $setting->url_cash_in;
        self::$urlCashOut = $setting->url_cash_out;
        self::$taxaCashIn = $setting->taxa_pix_cash_in;
        self::$taxaCashOut = $setting->taxa_pix_cash_out;

        $url = $setting->url . "/api/auth/login";
        $client_id = $setting->client_id;
        $client_secret = $setting->client_secret;

        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->post($url, compact('client_id', 'client_secret'));

        if ($response->successful()) {
            self::$accessToken = $response->json()['token'];
        }

        return true;
    }

    public static function requestDepositApithekey($data)
    {
        if (self::generateCredentialsApithekey()) {
            $client_ip = $data->ip();
            $accessToken = self::$accessToken;


            $external_id = uniqid();
            $document = Helper::generateValidCpf();

            $payload = [
                "amount" => floatval($data->amount),
                "external_id" => $external_id,
                "clientCallbackUrl" => url("apithekey/callback/deposit"),
                "payer" => [
                    "name" => $data->debtor_name,
                    "email" => $data->email,
                    "document" => $document
                ],
            ];

            $response = Http::withOptions([
                'curl' => [
                    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4, // Força IPv4 globalmente
                ],
            ])->withHeaders([
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'Authorization' => "Bearer " . $accessToken,
                    ])->post(self::$urlCashIn, $payload);
            //dd($response->json());
            if ($response->successful()) {

                $responseData = $response->json()['qrCodeResponse'];
                $setting = App::first();
                $user = $data->user;
                $taxafixa = $user->taxa_cash_in_fixa;

                $taxatotal = ((float) $data->amount * (float) $user->taxa_cash_in / 100);
                $deposito_liquido = (float) $data->amount - $taxatotal;
                $taxa_cash_in = $taxatotal;
                $descricao = "PORCENTAGEM";

                if ($taxafixa <= 0 && (float) $taxatotal < (float) $setting->baseline) {
                    $deposito_liquido = (float) $data->amount - (float) $setting->baseline;
                    $taxa_cash_in = (float) $setting->baseline;
                    $descricao = "FIXA";
                }

                $deposito_liquido = $deposito_liquido - $taxafixa;
                $taxa_cash_in = $taxa_cash_in + $taxafixa;


                $date = Carbon::now();

                $cashin = [
                    "user_id" => $data->user->username,
                    "externalreference" => $responseData['transactionId'],
                    "amount" => $data->amount,
                    "client_name" => $data->debtor_name,
                    "client_document" => $document,
                    "client_email" => $data->email,
                    "date" => $date,
                    "status" => 'WAITING_FOR_APPROVAL',
                    "idTransaction" => $responseData['transactionId'],
                    "deposito_liquido" => $deposito_liquido,
                    "qrcode_pix" => $responseData['qrcode'],
                    "paymentcode" => $responseData['qrcode'],
                    "paymentCodeBase64" => $responseData['qrcode'],
                    "adquirente_ref" => 'apithekey',
                    "taxa_cash_in" => $taxa_cash_in,
                    "taxa_pix_cash_in_adquirente" => self::$taxaCashIn,
                    "taxa_pix_cash_in_valor_fixo" => $taxafixa,
                    "client_telefone" => $data->phone,
                    "executor_ordem" => 'apithekey',
                    "descricao_transacao" => $descricao,
                    "callback" => $data->postback,
                    "split_email" => null,
                    "split_percentage" => null,
                ];

                Solicitacoes::create($cashin);

                return [
                    "data" => [
                        "idTransaction" => $responseData['transactionId'],
                        "qrcode" => $responseData['qrcode'],
                        "qr_code_image_url" => 'https://quickchart.io/qr?text=' . $responseData['qrcode']
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

    public static function requestPaymentApithekey($request)
    {

        $user = User::where('id', $request->user->id)->first();

        $setting = App::first();
        $taxafixa = $user->taxa_cash_out_fixa ?? 0;
        $amount = $request->amount;

        $taxatotal = ((float) $request->amount * (float) $user->taxa_cash_out / 100);
        $cashout_liquido = (float) $request->amount - $taxatotal;
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

        $limiteSaque = (float) $setting->limite_saque_automatico;
        $valorSaque = (float) $amount;

        $manual = true;
        if ($limiteSaque > 0 && $valorSaque > $limiteSaque)
            $manual = true;
        elseif ($limiteSaque == 0)
            $manual = false;
        //dd($manual);

        if ($manual) {
            $request->baasPostbackUrl = 'web';
            //Helper::incrementAmount($user, $request->amount, 'valor_saque_pendente');
            //Helper::decrementAmount($user, $cashout_liquido, 'saldo');

            $date = Carbon::now();
            return self::generateTransactionPaymentManualSimpay(
                $request,
                $taxa_cash_out,
                $cashout_liquido,
                $date,
                $descricao,
                $user
            );
        }

        $idBloquear = 0; //ALTERAR PARA ID QUE DESEJA BLOQUEA O SAQUE AUTOMATICO // colocar 0 para nao bloquear nenhum
        $idBloqueado = $user->id == $idBloquear ? true : false;
        if ($idBloqueado) {
            $request->baasPostbackUrl = 'web';
            //Helper::incrementAmount($user, $request->amount, 'valor_saque_pendente');
            //Helper::decrementAmount($user, $cashout_liquido, 'saldo');
            $date = Carbon::now();
            return self::generateTransactionPaymentManualSimpay(
                $request,
                $taxa_cash_out,
                $cashout_liquido,
                $date,
                $descricao,
                $user
            );
        }

        // Verifica se ainda está dentro do horário permitido para saque
        $hourLimitWithdraw = $setting->hour_limit_withdraw;
        $dentroHorario = false;
        if (Carbon::now()->lt(Carbon::today()->setHour((int) $hourLimitWithdraw)))
            $dentroHorario = true;
        elseif (is_null($hourLimitWithdraw))
            $dentroHorario = true;

        if (!$dentroHorario) {
            $request->baasPostbackUrl = 'web';
            //Helper::incrementAmount($user, $request->amount, 'valor_saque_pendente');
            //Helper::decrementAmount($user, $cashout_liquido, 'saldo');
            $date = Carbon::now();
            return self::generateTransactionPaymentManualSimpay(
                $request,
                $taxa_cash_out,
                $cashout_liquido,
                $date,
                $descricao,
                $user
            );
        }

        if (self::generateCredentialsApithekey()) {
            $callback = url("apithekey/callback/withdraw");
            $client_ip = $request->ip();
            $accessToken = self::$accessToken;


            $external_id = uniqid();

            $pixKey = $request->pixKey;
            if (strtoupper($request->pixKeyType) == 'CPF' || strtoupper($request->pixKeyType) == 'CNPJ') {
                $pixKey = str_replace(['.', '-', '/'], ['', '', ''], $pixKey);
            }

            if ($request->pixKeyType == "telefone") {
                $request->pixKeyType = "PHONE";
            }

            if ($user->saldo < $cashout_liquido) {
                $taxatotal = ((float) $user->saldo * (float) $user->taxa_cash_out / 100);
                $cashout_liquido = (float) $user->saldo - $taxatotal;
                $taxa_cash_out = $taxatotal;
                $descricao = "PORCENTAGEM";

                $cashout_liquido = $cashout_liquido - $taxafixa;
                $taxa_cash_out = $taxa_cash_out + $taxafixa;

                $amount = $cashout_liquido;

            }

            $payload = [
                "amount" => floatval($cashout_liquido),
                "external_id" => $external_id,
                "pix_key" => $pixKey,
                "key_type" => strtoupper($request->pixKeyType),
                "description" => "Saque referente ao pedido $external_id",
                "clientCallbackUrl" => $callback
            ];

            // dd($payload);
            \Log::debug('PAYLOAD REQUEST WITHDRAW APIKEY: ' . json_encode($payload));
            //dd($payload);
            $response = Http::withOptions([
                'curl' => [
                    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4, // Força IPv4 globalmente
                ],
            ])->withHeaders([
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'Authorization' => "Bearer " . $accessToken,
                    ])->post(self::$urlCashOut, $payload);

            \Log::debug('REPSONSE WITHDRAW APIKEY BODY: ' . json_encode($response->json()));
            //dd($response->json());

            if ($response->successful()) {
                //Helper::incrementAmount($user, $request->amount, 'valor_saque_pendente');
                //Helper::decrementAmount($user, $cashout_liquido, 'saldo');

                $name = "Cliente de " . $request->user->name;
                $pixKey = $request->pixKey;

                switch ($request->pixKeyType) {
                    case 'cpf':
                    case 'cnpj':
                    case 'phone':
                        $pixKey = preg_replace('/[^0-9]/', '', $pixKey);
                        break;
                }

                $date = Carbon::now();

                $responseData = $response->json();

                $status = 'PENDING';
                $responseStatus = "PendingProcessing";
                $statusAdquirente = $responseData['withdrawal']['status'];
                switch ($statusAdquirente) {
                    case 'COMPLETED':
                        $responseStatus = "paid";
                        $status = 'COMPLETED';
                        break;
                    case 'FAILED':
                    case 'REJECTED':
                        $responseStatus = "cancelled";
                        $status = 'CANCELLED';
                        break;
                    default:
                        $status = 'PENDING';
                        $responseStatus = "PendingProcessing";
                        break;
                }

                $pixcashout = [
                    "user_id" => $request->user->username,
                    "externalreference" => $responseData['withdrawal']['transaction_id'],
                    "amount" => $amount,
                    "beneficiaryname" => $name,
                    "beneficiarydocument" => $pixKey ?? $request->pixKey,
                    "pix" => $pixKey ?? $request->pixKey,
                    "pixkey" => strtolower($request->pixKeyType),
                    "date" => $date,
                    "status" => $status,
                    "type" => "PIX",
                    "idTransaction" => $responseData['withdrawal']['transaction_id'],
                    "taxa_cash_out" => $taxa_cash_out,
                    "cash_out_liquido" => $cashout_liquido,
                    "end_to_end" => $responseData['withdrawal']['transaction_id'],
                    "callback" => $request->baasPostbackUrl,
                    "descricao_transacao" => $descricao,
                    "adquirente_ref" => 'apithekey',
                    "taxa_pix_cash_out_adquirente" => self::$taxaCashOut
                ];

                $cashout = SolicitacoesCashOut::create($pixcashout);

                if (isset($responseData['withdrawal']['status'])) {
                    $status = $responseData['withdrawal']['status'];

                    switch ($status) {
                        case 'COMPLETED':
                            $cashout->update(['status' => 'COMPLETED']);
                            if ($cashout->callback) {
                                $payload = [
                                    "status" => "paid",
                                    "idTransaction" => $cashout->idTransaction,
                                    "typeTransaction" => "PAYMENT"
                                ];


                                $user = User::where('user_id', $cashout->user_id)->with('devices')->first();
                                $devices = $user?->devices ?? null;

                                try {
                                    if (!empty($devices)) {
                                        $fcm = Fcm::first();
                                        $titulo = $fcm->title_cashout;
                                        $valor = "R$ " . number_format($cashout->amount, 2, ',', '.');
                                        $body = str_replace('{valor}', $valor, $fcm->body_cashout);

                                        $send = new SendNotification();
                                        $send->one(
                                            $user->id,
                                            $titulo,
                                            $body,
                                            "/relatorio/saidas"
                                        );
                                    }
                                } catch (\Throwable $th) {
                                    //throw $th;
                                    Log::error("ERRO AO ENVIAR NOTIFICAÇÃO: " . $th);
                                }

                                $sendcallback = Http::withHeaders([
                                    'Content-Type' => 'application/json',
                                    'accept' => 'application/json'
                                ])->post($cashout->callback, $payload);

                                \Log::debug("[PIX-OUT] Send Callback: Para $cashout->callback -> Enviando...");
                                if ($cashout->callback && $cashout->callback != 'web') {
                                    $payload = [
                                        "status" => "paid",
                                        "idTransaction" => $cashout->idTransaction,
                                        "typeTransaction" => "PAYMENT"
                                    ];

                                    Http::withHeaders([
                                        'Content-Type' => 'application/json',
                                        'accept' => 'application/json'
                                    ])->post($cashout->callback, $payload);
                                }
                            }
                            break;
                        case 'FAILED':
                        case 'REJECT':
                        case 'REJECTED':
                            $cashout->update(['status' => 'CANCELLED']);
                            if ($cashout->callback) {
                                $payload = [
                                    "status" => "cancelled",
                                    "idTransaction" => $cashout->idTransaction,
                                    "typeTransaction" => "PAYMENT"
                                ];

                                $sendcallback = Http::withHeaders([
                                    'Content-Type' => 'application/json',
                                    'accept' => 'application/json'
                                ])->post($cashout->callback, $payload);

                                \Log::debug("[PIX-OUT] Send Callback: Para $cashout->callback -> Enviando...");
                                if ($cashout->callback && $cashout->callback != 'web') {
                                    $payload = [
                                        "status" => "cancelled",
                                        "idTransaction" => $cashout->idTransaction,
                                        "typeTransaction" => "PAYMENT"
                                    ];

                                    Http::withHeaders([
                                        'Content-Type' => 'application/json',
                                        'accept' => 'application/json'
                                    ])->post($cashout->callback, $payload);
                                }
                            }
                            break;

                        default:
                            break;
                    }


                }


                return [
                    "status" => 200,
                    "data" => [
                        "id" => $responseData['withdrawal']['transaction_id'],
                        "amount" => $request->amount,
                        "pixKey" => $request->pixKey,
                        "pixKeyType" => $request->pixKeyType,
                        "withdrawStatusId" => $responseStatus,
                        "createdAt" => $responseData['createdAt'] ?? $date,
                        "updatedAt" => $responseData['updatedAt'] ?? $date
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

    protected static function generateTransactionPaymentManualApithekey($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user)
    {
        $idTransaction = Str::uuid()->toString();

        $name = "Cliente de " . $request->user->name;

        $pixKey = $request->pixKey;

        switch ($request->pixKeyType) {
            case 'cpf':
            case 'cnpj':
            case 'phone':
                $pixKey = preg_replace('/[^0-9]/', '', $pixKey);
                break;
        }

        $pixcashout = [
            "user_id" => $request->user->username,
            "externalreference" => $idTransaction,
            "amount" => $request->amount,
            "beneficiaryname" => $name,
            "beneficiarydocument" => $pixKey,
            "pix" => $pixKey,
            "pixkey" => strtolower($request->pixKeyType),
            "date" => $date,
            "status" => "PENDING",
            "type" => "PIX",
            "idTransaction" => $idTransaction,
            "taxa_cash_out" => $taxa_cash_out,
            "cash_out_liquido" => $cashout_liquido,
            "end_to_end" => $idTransaction,
            "callback" => $request->baasPostbackUrl,
            "descricao_transacao" => "WEB",
            "adquirente_ref" => 'apithekey',
            "taxa_pix_cash_out_adquirente" => self::$taxaCashOut
        ];

        $cashout = SolicitacoesCashOut::create($pixcashout);

        return [
            "status" => 200,
            "data" => [
                "id" => $idTransaction,
                "amount" => $request->amount,
                "pixKey" => $request->pixKey,
                "pixKeyType" => $request->pixKeyType,
                "withdrawStatusId" => "PendingProcessing",
                "createdAt" => $date,
                "updatedAt" => $date
            ]
        ];
    }

    public static function liberarSaqueManualApithekey($id)
    {
        if (self::generateCredentialsApithekey()) {
            $cashout = SolicitacoesCashOut::where('id', $id)->first();
            $callback = url("apithekey/callback/withdraw");
            $accessToken = self::$accessToken;

            $pixkeytype = strtoupper($cashout->pixkey);

            if ($pixkeytype == "TELEFONE") {
                $pixkeytype = "PHONE";
            }

            $external_id = uniqid();

            $pixKey = $cashout->pixKey;
            if (strtoupper($pixkeytype) == 'CPF' || strtoupper($pixkeytype) == 'CNPJ') {
                $pixKey = str_replace(['.', '-', '/'], '', $pixKey);
            }


            $payload = [
                "amount" => (float) number_format($cashout->cash_out_liquido, 2),
                "external_id" => $external_id,
                "pix_key" => $cashout->pix,
                "key_type" => $pixkeytype,
                "description" => "Saque referente ao pedido $external_id",
                "clientCallbackUrl" => $callback
            ];
            //dd($payload);
            $response = Http::withOptions([
                'curl' => [
                    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4, // Força IPv4 globalmente
                ],
            ])->withHeaders([
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer ' . $accessToken,
                    ])->post(self::$urlCashOut, $payload);

            \Log::debug('DATA BODY REQUEST PAYMENT THEKEY: ' . json_encode($response->json()));
            if ($response->successful()) {
                $responseData = $response->json();
                $status = 'PENDING';
                $status_response = $responseData['withdrawal']['status'];
                switch ($status_response) {
                    case 'COMPLETED':
                        $status = 'COMPLETED';
                        break;

                    case 'FAILED':
                    case 'REJECT':
                    case 'REJECTED':
                        $status = "CANCELLED";
                        break;
                    default:
                        # code...
                        break;
                }

                $pixcashout = [
                    "status" => $status,
                    "externalreference" => $responseData['withdrawal']['transaction_id'],
                    "idTransaction" => $responseData['withdrawal']['transaction_id'],
                    "end_to_end" => $responseData['withdrawal']['transaction_id'],
                    "descricao_transacao" => "LIBERADOADMIN"
                ];

                $cashout = SolicitacoesCashOut::where('id', $id);
                $cashout->update($pixcashout);

                if ($status_response === 'COMPLETED') {

                    $user = User::where('user_id', $cashout->user_id)->with('devices')->first();
                    $devices = $user?->devices ?? null;

                    try {
                        if (!empty($devices)) {
                            $fcm = Fcm::first();
                            $titulo = $fcm->title_cashout;
                            $valor = "R$ " . number_format($cashout->amount, 2, ',', '.');
                            $body = str_replace('{valor}', $valor, $fcm->body_cashout);

                            $send = new SendNotification();
                            $send->one(
                                $user->id,
                                $titulo,
                                $body,
                                "/relatorio/saidas"
                            );
                        }
                    } catch (\Throwable $th) {
                        //throw $th;
                        Log::error("ERRO AO ENVIAR NOTIFICAÇÃO: " . $th);
                    }
                }

                return back()->with('success', 'Pedido de saque enviado com sucesso!');
            } else {
                $message = $response->json()['message'];
                return back()->with('error', $message ?? 'Houve um erro ao liberar saque.');
            }
        }
    }
}
