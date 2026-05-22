<?php

namespace App\Traits;

use App\DTO\WitetecDTO\CustomerDTO;
use App\DTO\WitetecDTO\DepositDTO;
use App\DTO\WitetecDTO\Enums\DepositMethod;
use App\DTO\WitetecDTO\ItemDTO;
use App\Services\WitetecService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\{App, User, AdWitetec, Solicitacoes, SolicitacoesCashOut};
use App\Helpers\Helper;
use App\DTO\ApiDepositDTO;
use App\DTO\WitetecDTO\Enums\PixKeyType;
use App\DTO\WitetecDTO\WithdrawDTO;

trait WitetecTrait
{
    protected static string $apiKey;
    protected static string $baseUrl;
    protected static string $taxaCashIn;
    protected static string $taxaCashOut;
    protected static string $txBilletFixed;
    protected static string $txBilletPercent;
    protected static string $txCardFixed;
    protected static string $txCardPercent;

    protected static function generateCredentialWitetec()
    {

        $setting = AdWitetec::first();
        if (!$setting) {
            return false;
        }

        self::$apiKey = $setting->api_token;
        self::$baseUrl = $setting->url;
        self::$taxaCashIn = $setting->taxa_pix_cash_in;
        self::$taxaCashOut = $setting->taxa_pix_cash_out;
        self::$txBilletFixed = $setting->tx_billet_fixed;
        self::$txBilletPercent = $setting->tx_billet_percent;
        self::$txCardFixed = $setting->tx_card_fixed;
        self::$txCardPercent = $setting->tx_card_percent;

        return true;
    }

    public static function requestDepositWitetec($request)
    {

        if (self::generateCredentialWitetec()) {

            /** @var ApiDepositDTO $data */
            $data = $request;

            if (Helper::validarCPF($data->debtor_document_number)) {
                $cpf = $data->debtor_document_number;
            } else {
                $cpf = Helper::generateValidCpf(false);
            }

            $customer = new CustomerDTO(
                $data->debtor_name,
                $data->email,
                $data->phone,
                "CPF",
                $cpf

            );

            $item = new ItemDTO(
                "Produto X",
                $data->amount * 100,
                1,
                false,
                uniqid("PROD_")
            );

            $deposit = new DepositDTO(
                $data->amount * 100,
                DepositMethod::PIX,
                $customer,
                [$item],
                null

            );
            //dd($deposit);
            $api = new WitetecService(self::$baseUrl, self::$apiKey);
            $response = $api->deposit($deposit);
            //dd($response);
            if (isset($response['message'])) {
                return [
                    "data" => [
                        'status' => 'error',
                        'message' => 'Houve um erro, tente novamente.'
                    ],
                    "status" => 401
                ];
            }

            if ($response['status']) {

                $responseData = $response['data'];
                //dd($responseData);
                $user = $data->user;
                $setting = App::first();
                $taxafixa = $user->taxa_cash_in_fixa;


                $taxatotal = ((float) $data->amount * (float) $user->taxa_cash_in / 100);
                $deposito_liquido = (float) $data->amount - $taxatotal;
                $taxa_cash_in = $taxatotal;
                $descricao = "PORCENTAGEM";

                if ((float) $taxatotal < (float) $setting->baseline) {
                    $deposito_liquido = (float) $data->amount - (float) $setting->baseline;
                    $taxa_cash_in = (float) $setting->baseline;
                    $descricao = "FIXA";
                }


                $deposito_liquido = $deposito_liquido - $taxafixa;
                $taxa_cash_in = $taxa_cash_in + $taxafixa;

                $date = Carbon::now();

                $ip = $request->header('X-Forwarded-For') ?
                    $request->header('X-Forwarded-For') : ($request->header('CF-Connecting-IP') ?
                        $request->header('CF-Connecting-IP') :
                        $request->ip());


                $cashin = [
                    "user_id" => $data->user->username,
                    "externalreference" => $responseData['id'],
                    "amount" => $data->amount,
                    "client_name" => $data->debtor_name,
                    "client_document" => $data->debtor_document_number,
                    "client_email" => $data->email,
                    "date" => $date,
                    "status" => 'WAITING_FOR_APPROVAL',
                    "idTransaction" => $responseData['id'],
                    "deposito_liquido" => $deposito_liquido,
                    "qrcode_pix" => $responseData['pix']['copyPaste'],
                    "paymentcode" => $responseData['pix']['copyPaste'],
                    "paymentCodeBase64" => $responseData['pix']['copyPaste'],
                    "adquirente_ref" => 'witetec',
                    "taxa_cash_in" => $taxa_cash_in,
                    "taxa_pix_cash_in_adquirente" => 0,
                    "taxa_pix_cash_in_valor_fixo" => $taxafixa,
                    "client_telefone" => $data->phone,
                    "executor_ordem" => 'witetec',
                    "descricao_transacao" => $descricao,
                    "callback" => $data->postback,
                    "split_email" => null,
                    "split_percentage" => null,
                ];

                Solicitacoes::create($cashin);

                if (!is_null($user->integracao_utmfy)) {
                    $msg = "PIX Gerado " . env('APP_NAME');
                    UtmfyTrait::gerarUTM('pix', 'waiting_payment', $cashin, $user->integracao_utmfy, $ip, $msg);
                }

                return [
                    "data" => [
                        "idTransaction" => $responseData['id'],
                        "qrcode" => $responseData['pix']['copyPaste'],
                        "qr_code_image_url" => 'https://quickchart.io/qr?text=' . urlencode($responseData['pix']['copyPaste'])
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

    public static function requestPaymentWitetec($request)
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


        $date = Carbon::now();

        // Verifica se o valor do saque pode ser processado automaticamente
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

            return self::generateTransactionPaymentManualWitetec(
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

            return self::generateTransactionPaymentManualWitetec(
                $request,
                $taxa_cash_out,
                $cashout_liquido,
                $date,
                $descricao,
                $user
            );
        }

        if (self::generateCredentialWitetec()) {
            $pixKeyType = "CPF";
            switch (strtolower($request->pixKeyType)) {
                case 'email':
                    $pixKeyType = "EMAIL";
                    break;
                case 'telefone':
                    $pixKeyType = "PHONE";
                    break;
                case 'aleatoria':
                    $pixKeyType = "EVP";
                    break;
                default:
                    $pixKeyType = "CPF";
                    break;
            }

            $payload = new WithdrawDTO(
                $cashout_liquido * 100,
                $request->pixKey,
                $pixKeyType,
                "PIX"
            );

            $api = new WitetecService(self::$baseUrl, self::$apiKey);
            $response = $api->withdraw($payload);



            if ($response['status']) {
                Helper::incrementAmount($user, $request->amount, 'valor_saque_pendente');
                Helper::decrementAmount($user, $cashout_liquido, 'saldo');

                $name = "Cliente de " . $request->user->name;
                $responseData = $response['data'];

                $pixKey = $request->pixKey;

                switch ($request->pixKeyType) {
                    case 'cpf':
                    case 'cnpj':
                    case 'phone':
                        $pixKey = preg_replace('/[^0-9]/', '', $pixKey);
                        break;
                }

                $ip = $request->header('X-Forwarded-For') ?
                    $request->header('X-Forwarded-For') : ($request->header('CF-Connecting-IP') ?
                        $request->header('CF-Connecting-IP') :
                        $request->ip());

                $internal_id = str_replace('-', '', (string) Str::uuid());
                $internal_id = strtoupper($internal_id);

                $pixcashout = [
                    "user_id" => $request->user->username,
                    "externalreference" => $response['data']['id'],
                    "amount" => $request->amount,
                    "beneficiaryname" => $name,
                    "beneficiarydocument" => $pixKey,
                    "pix" => $pixKey,
                    "pixkey" => strtolower($request->pixKeyType),
                    "date" => $date,
                    "status" => "PENDING",
                    "type" => "PIX",
                    "idTransaction" => $response['data']['id'],
                    "taxa_cash_out" => $taxa_cash_out,
                    "cash_out_liquido" => $cashout_liquido,
                    "end_to_end" => $response['data']['id'],
                    "callback" => $request->baasPostbackUrl,
                    "descricao_transacao" => $descricao,
                    "adquirente_ref" => 'witetec',
                    "taxa_pix_cash_out_adquirente" => self::$taxaCashOut
                ];

                SolicitacoesCashOut::create($pixcashout);

                return [
                    "status" => 200,
                    "data" => [
                        "id" => $response['data']['id'],
                        "amount" => $request->amount,
                        "pixKey" => $request->pixKey,
                        "pixKeyType" => $request->pixKeyType,
                        "withdrawStatusId" => "PendingProcessing",
                        "createdAt" => $date,
                        "updatedAt" => $date
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

    protected static function generateTransactionPaymentManualWitetec($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user)
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
            "type" => $request->pixKeyType == "crypto" ? "CRYPTO" : "PIX",
            "idTransaction" => $idTransaction,
            "taxa_cash_out" => $taxa_cash_out,
            "cash_out_liquido" => $cashout_liquido,
            "end_to_end" => $idTransaction,
            "callback" => $request->baasPostbackUrl,
            "blockchainNetwork" => $request->blockchainNetwork ?? null,
            "cryptocurrency" => $request->cryptocurrency ?? null,
            "descricao_transacao" => "WEB",
            "adquirente_ref" => 'witetec',
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

    public static function liberarSaqueManualWitetec($id)
    {
        //dd($id);
        if (self::generateCredentialWitetec()) {
            $cashout = SolicitacoesCashOut::where('id', $id)->first();

            $pixKeyType = "CPF";
            switch (strtolower($cashout->pixkey)) {
                case 'email':
                    $pixKeyType = "EMAIL";
                    break;
                case 'telefone':
                    $pixKeyType = "PHONE";
                    break;
                case 'aleatoria':
                    $pixKeyType = "EVP";
                    break;
                default:
                    $pixKeyType = "CPF";
                    break;
            }

            $sacar = (float) number_format($cashout->cash_out_liquido, 2, '.', '') * 100;
            $payload = new WithdrawDTO(
                $sacar,
                $cashout->pix,
                $pixKeyType,
                "PIX"
            );

            $api = new WitetecService(self::$baseUrl, self::$apiKey);
            $response = $api->withdraw($payload);


            if (isset($response['message'])) {
                return back()->with('error', $response['message']);
            }
            if ($response['status']) {
                $responseData = $response['data'];
                $pixcashout = [
                    "externalreference" => $responseData['id'],
                    "idTransaction" => $responseData['id'],
                    "end_to_end" => $responseData['id'],
                    "descricao_transacao" => "LIBERADOADMIN"
                ];

                $cashout = SolicitacoesCashOut::where('id', $id)->update($pixcashout);
                return back()->with('success', 'Pedido de saque enviado com sucesso!');
            } else {
                return back()->with('error', 'Houve um erro ao liberar saque.');
            }
        }
    }

    public static function registerWebhooksWitetec()
    {
        if (self::generateCredentialWitetec()) {
            $api = new WitetecService(self::$baseUrl, self::$apiKey);
            $api->webhooks();

            return back()->with('success', 'Webhooks registrado com sucesso para Witetec');
        } else {
            return back()->with('error', 'Insira as credênciais da Witetec primeiro, antes de registar.');
        }
    }

    public static function defesaMedWitetec($appealId, $appealReason)
    {
        if (self::generateCredentialWitetec()) {
            $witetec = new WitetecService(
                self::$baseUrl,
                self::$apiKey
            );

            $response = $witetec->disputesAppeal(
                $appealId,
                $appealReason
            );

            return $response->json();
        } else {
            return back()->with('error', 'Credenciais Witetec não encontradas.');
        }
    }
}
