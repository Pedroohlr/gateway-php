<?php

namespace App\traits;

use App\Helpers\Helper;
use App\Models\Mercadopago;
use App\Models\App;
use App\Models\Solicitacoes;
use App\Models\SolicitacoesCashOut;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Log;

trait MercadoPagoTrait
{
    protected static string $access_token;
    protected static string $taxa_cashIn;

    protected static function generateCredentialsMercadoPago()
    {
        $setting = Mercadopago::first();
        self::$access_token = $setting->access_token;
        self::$taxa_cashIn = $setting->taxa_pix_cash_in;

        return true;
    }


    public static function requestDepositMercadopago($data)
    {
        
        if (self::generateCredentialsMercadoPago()) {
            $client_ip = $data->ip();

            $document = Helper::generateValidCpf();
            $valor = $data->amount;

            $stringGenerate = Str::uuid();
            $token = Helper::MakeToken([
                'total' => $valor,
                'qty' => 1,
                'user_id' => $stringGenerate
            ]);

            $pessoa = Helper::gerarPessoa();
            $name = count(explode(' ', $data->debtor_name || "")) > 0 ? explode(' ', $data->debtor_name)[0] : explode(' ', $pessoa['nome'])[0];
            $lastname = count(explode(' ', $data->debtor_name || "")) > 1 ? explode(' ', $data->debtor_name)[1] : explode(' ', $pessoa['nome'])[1];
            $cpf = $data->debtor_document_number;
            if(Helper::validarCPF($cpf)) {
                $cpf = $pessoa['cpf'];
            }
            $email = $data->email;

            $response = Http::withHeaders([
                'X-Idempotency-Key' => $token,
                'Authorization' => 'Bearer ' . self::$access_token,
            ])->post('https://api.mercadopago.com/v1/payments', [
                "transaction_amount" => floatval($valor),
                "description" => 'Pagamento',
                "payment_method_id" => "pix",
                "notification_url" => url('mercadopago/callback/deposit'),
                "external_reference" => $stringGenerate,
                "payer" => [
                    "email" => $email,
                    "first_name" => $name,
                    "last_name" => $lastname,
                    "identification" => [
                        "type" => "CPF",
                        "number" => Helper::soNumero($cpf)
                    ]
                ]
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info("[+][MERCADOPAGO][RESPONSE][QRCODE]". json_encode($responseData));
               //dd($responseData);
                $transactionData = $responseData['point_of_interaction']['transaction_data'];
                //dd($transactionData);

                $setting = App::first();
                $user = $data->user;
                $taxafixa = $user->taxa_cash_in_fixa;


                $taxatotal = ((float)$data->amount * (float)$user->taxa_cash_in / 100);
                $deposito_liquido = (float)$data->amount - $taxatotal;
                $taxa_cash_in = $taxatotal;
                $descricao = "PORCENTAGEM";

                if ((float)$taxatotal < (float)$setting->baseline) {
                    $deposito_liquido = (float)$data->amount - (float)$setting->baseline;
                    $taxa_cash_in = (float)$setting->baseline;
                    $descricao = "FIXA";
                }


                $deposito_liquido = $deposito_liquido - $taxafixa;
                $taxa_cash_in = $taxa_cash_in + $taxafixa;

                $date = Carbon::now();

                $cashin = [
                    "user_id"                       => $data->user->username,
                    "externalreference"             => $responseData['external_reference'],
                    "amount"                        => $data->amount,
                    "client_name"                   => $data->debtor_name,
                    "client_document"               => $document,
                    "client_email"                  => $data->email,
                    "date"                             => $date,
                    "status"                        => 'WAITING_FOR_APPROVAL',
                    "idTransaction"                 => $responseData['id'],
                    "deposito_liquido"              => $deposito_liquido,
                    "qrcode_pix"                    => $transactionData['qr_code'],
                    "paymentcode"                   => $transactionData['qr_code'],
                    "paymentCodeBase64"             => $transactionData['qr_code'],
                    "adquirente_ref"                => 'mercadopago',
                    "taxa_cash_in"                  => $taxa_cash_in,
                    "taxa_pix_cash_in_adquirente"   => self::$taxa_cashIn,
                    "taxa_pix_cash_in_valor_fixo"   => $taxafixa,
                    "client_telefone"               => $data->phone,
                    "executor_ordem"                => 'mercadopago',
                    "descricao_transacao"           => $descricao,
                    "callback"                      => $data->postback,
                    "split_email"                   => null,
                    "split_percentage"              => null,
                ];

                Solicitacoes::create($cashin);

                                return [
                    "data" => [
                        "idTransaction" => $responseData['external_reference'],
                        "qrcode" => $transactionData['qr_code'],
                        "qr_code_image_url" => 'https://quickchart.io/qr?text=' . $transactionData['qr_code']
                    ],
                    "status" => 200
                ];
            } else {
                $responseData = $response->json();
                return [
                    "data" => [
                        'status' => 'error',
                        'message' => $responseData['message'] ?? "Houve um erro. tente novamente."
                    ],
                    "status" => 401
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

    public static function requestPaymentMercadopago($request)
    {
        $user = User::where('id', $request->user->id)->first();

        $setting = App::first();
        $taxafixa = $setting->taxa_fixa_padrao_cash_out ?? 0;

        $taxatotal = ((float)$request->amount * (float)$setting->taxa_cash_out_padrao / 100);
        $cashout_liquido = (float)$request->amount - $taxatotal;
        $taxa_cash_out = $taxatotal;
        $descricao = "PORCENTAGEM";

        if ((float)$taxatotal < (float)$setting->baseline) {
            $cashout_liquido = (float)$request->amount - (float)$setting->baseline;
            $taxa_cash_out = (float)$setting->baseline;
            $descricao = "FIXA";
        }

        if (!is_null($taxafixa) && $taxafixa > 0) {
            $cashout_liquido = $cashout_liquido - $taxafixa;
            $taxa_cash_out = $taxa_cash_out + $taxafixa;
        }



        if ($user->saldo < $cashout_liquido) {
            return response()->json([
                'status' => 'error',
                'message' => "Saldo insuficiente.",
            ], 401);
        }

        $date = Carbon::now();
        return self::generateTransactionPaymentManualMercadopago($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user);
    }

    protected static function generateTransactionPaymentManualMercadopago($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user)
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
            "descricao_transacao"   => "WEB"
        ];

        SolicitacoesCashOut::create($pixcashout);

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

    public static function liberarSaqueManualMercadopago($id)
    {
        $pixcashout = [
            "status"                => "COMPLETED",
            "descricao_transacao"   => "LIBERADOADMIN"
        ];

        SolicitacoesCashOut::where('id', $id)->update($pixcashout);
        return back()->with('success', "Saque atualizado para 'PAGO' com sucesso!");
    }
}
