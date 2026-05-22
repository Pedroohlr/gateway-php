<?php

namespace App\Http\Controllers\Api\Adquirentes;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CheckoutOrders;
use App\Models\Fcm;
use App\Models\Infracoes;
use App\Models\Mercadopago;
use App\Models\Solicitacoes;
use App\Models\SolicitacoesCashOut;
use App\Models\Transactions;
use App\Models\User;
use App\Services\SendNotification;
use App\Services\WalletService;
use App\Traits\UtmfyTrait;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Traits\ZoompagTrait;

class ZoompagController extends Controller
{

    public function callbackDeposit(Request $request)
    {
        $data = $request->all();
        Log::info("[+][ZOOMPAG][CALLBACK][DEPOSIT]: ", $data);

        $idTransaction = $data['id'];
        $status = $data['status'];

        switch ($data["eventType"]) {
            case "TRANSACTION_PAID":
                if ($idTransaction && $data['status'] == "PAID") {
                    $cashin = Solicitacoes::where('idTransaction', $idTransaction)->first();

                    $updated_at = Carbon::now();
                    $cashin->update(['status' => 'PAID_OUT', 'updated_at' => $updated_at]);
                    $wallet = new WalletService();
                    $wallet->createSaldoIn($cashin);

                    $infracao = Infracoes::where('idTransaction', $idTransaction);
                    if ($infracao) {
                        $infracao->update(['status' => 'RESOLVED', 'resolvedAt' => $updated_at]);
                    }

                    $user = User::where('user_id', $cashin->user_id)->with(['devices'])->first();
                    $devices = $user->devices ?? null;

                    try {
                        if (!empty($devices)) {
                            $fcm = Fcm::first();
                            $titulo = $fcm->title;
                            $valor = "R$ " . number_format($cashin->amount, 2, ',', '.');
                            $body = str_replace('{valor}', $valor, $fcm->body);

                            $send = new SendNotification();
                            $send->one(
                                $user->id,
                                $titulo,
                                $body,
                                "/relatorio/entradas"
                            );
                        }
                    } catch (\Throwable $th) {
                        //throw $th;
                        Log::error("ERRO AO ENVIAR NOTIFICAÇÃO: " . $th);
                    }

                    $user = User::where('user_id', $cashin->user_id)->first();
                    Helper::incrementAmount($user, $cashin->deposito_liquido, 'saldo');
                    Helper::calculaSaldoLiquido($user->user_id);

                    if ($cashin->callback) {
                        $payload = [
                            "status" => "paid",
                            "idTransaction" => $cashin->idTransaction,
                            "typeTransaction" => "PIX"
                        ];

                        Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'accept' => 'application/json'
                        ])->post($cashin->callback, $payload);

                        \Log::debug("[PIX-IN] Send Callback: Para $cashin->callback -> Enviando...");
                        if ($cashin->callback && $cashin->callback != 'web') {
                            $payload = [
                                "status" => "paid",
                                "idTransaction" => $cashin->idTransaction,
                                "typeTransaction" => "PIX"
                            ];

                            Http::withHeaders([
                                'Content-Type' => 'application/json',
                                'accept' => 'application/json'
                            ])->post($cashin->callback, $payload);

                            $success = 'paid';
                            return response()->json(['status' => $success]);
                        } else {
                            $order = CheckoutOrders::where('idTransaction', $data['idTransaction'])->first();
                            if ($order) {
                                $order->update(['status' => 'pago']);
                            }
                        }
                    }
                }
                break;
            case "TRANSACTION_FAILED":
            case "TRANSACTION_REFUSED":
                $cashin = Solicitacoes::where('idTransaction', $idTransaction)->first();

                $updated_at = Carbon::now();
                $cashin->update(['status' => 'CANCELLED', 'updated_at' => $updated_at]);

                if ($cashin->callback) {
                    $payload = [
                        "status" => "cancelled",
                        "idTransaction" => $cashin->idTransaction,
                        "typeTransaction" => "PIX"
                    ];

                    Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'accept' => 'application/json'
                    ])->post($cashin->callback, $payload);

                    \Log::debug("[PIX-IN] Send Callback: Para $cashin->callback -> Enviando...");
                    if ($cashin->callback && $cashin->callback != 'web') {
                        $payload = [
                            "status" => "cancelled",
                            "idTransaction" => $cashin->idTransaction,
                            "typeTransaction" => "PIX"
                        ];

                        Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'accept' => 'application/json'
                        ])->post($cashin->callback, $payload);

                        $success = 'cancelled';
                        return response()->json(['status' => $success]);
                    } else {
                        $order = CheckoutOrders::where('idTransaction', $data['idTransaction'])->first();
                        if ($order) {
                            $order->update(['status' => 'cancelado']);
                        }
                    }
                }
                break;
            case 'TRANSACTION_REFUNDED':
                $cashin = Solicitacoes::where('idTransaction', $idTransaction)->with('user')->first();

                $updated_at = Carbon::now();
                $cashin->update(['status' => 'MED', 'updated_at' => $updated_at]);

                $payloadInfracao = [
                    'amount' => $cashin->amount,
                    'idTransaction' => $idTransaction,
                    'createdAt' => $data['createdAt'] ? new DateTime($data['createdAt']) : null,
                    'transaction_id' => $cashin->id,
                    'user_id' => $cashin->user->id
                ];

                Infracoes::create($payloadInfracao);

                if ($cashin->callback) {
                    \Log::debug("[PIX-IN] Send Callback: Para $cashin->callback -> Enviando...");
                    if ($cashin->callback && $cashin->callback != 'web') {
                        $payload = [
                            "status" => "med",
                            "idTransaction" => $cashin->idTransaction,
                            "typeTransaction" => "PIX"
                        ];

                        Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'accept' => 'application/json'
                        ])->post($cashin->callback, $payload);

                        $success = 'med';
                        return response()->json(['status' => $success]);
                    } else {
                        $order = CheckoutOrders::where('idTransaction', $idTransaction)->first();
                        if ($order) {
                            $order->update(['status' => 'med']);
                        }
                    }
                }
                break;
            case 'TRANSACTION_CHARGEDBACK':
                $cashin = Solicitacoes::where('idTransaction', $idTransaction)->first();

                $updated_at = Carbon::now();
                $cashin->update(['status' => 'CHARGEDBACK', 'updated_at' => $updated_at]);

                $payloadInfracao = ['status' => 'REJECTED'];

                Infracoes::where('idTransaction', $idTransaction)->update($payloadInfracao);

                if ($cashin->callback) {
                    \Log::debug("[PIX-IN] Send Callback: Para $cashin->callback -> Enviando...");
                    if ($cashin->callback && $cashin->callback != 'web') {
                        $payload = [
                            "status" => "rejected",
                            "idTransaction" => $cashin->idTransaction,
                            "typeTransaction" => "PIX"
                        ];

                        Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'accept' => 'application/json'
                        ])->post($cashin->callback, $payload);

                        $success = 'rejected';
                        return response()->json(['status' => $success]);
                    } else {
                        $order = CheckoutOrders::where('idTransaction', $data['idTransaction'])->first();
                        if ($order) {
                            $order->update(['status' => 'rejeitado']);
                        }
                    }
                }
                break;

        }
        return response()->json([]);
    }

    public function callbackWithdraw(Request $request)
    {
        $data = $request->all();
        Log::info("[+][ZOOMPAG][CALLBACK][WITHDRAW]: ", $data);

        $idTransaction = $data['id'];
        $status = $data['status'];

        switch ($data["eventType"]) {
            case "WITHDRAWAL_PAID":
                $cashout = SolicitacoesCashOut::where('idTransaction', $idTransaction)->first();
                if (!$cashout || $cashout->status != "PENDING") {
                    return response()->json(['status' => false]);
                }

                $cashout->update(['status' => 'COMPLETED']);
                $wallet = new WalletService();
                $wallet->createSaldoOut($cashout);

                $user = User::where('user_id', $cashout->user_id)->with('devices')->first();
                $devices = $user?->devices || null;

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

                Helper::decrementAmount($user, $request->amount, 'valor_saque_pendente');

                if ($cashout->callback && $cashout->callback != 'web') {
                    $payload = [
                        "status" => "paid",
                        "idTransaction" => $cashout->idTransaction,
                        "typeTransaction" => "PAYMENT"
                    ];

                    $sendcallback = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'accept' => 'application/json'
                    ])->post($cashout->callback, $payload);

                    Log::debug("[PIX-OUT] Send Callback: Para $cashout->callback -> Enviando...");
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
            case "WITHDRAWAL_FAILED":
            case "WITHDRAWAL_CANCELED":
            case "WITHDRAWAL_REFUNDED":
            case "WITHDRAWAL_REJECTED":
                $cashout = SolicitacoesCashOut::where('idTransaction', $idTransaction)->first();

                $message = 'Erro na Adquirencia.';
                $cashout->update(['status' => 'CANCELLED', 'descricao_externa' => $message]);

                if ($cashout->callback && $cashout->callback != 'web') {
                    $payload = [
                        "status" => "canceled",
                        "idTransaction" => $cashout->idTransaction,
                        "typeTransaction" => "PAYMENT"
                    ];

                    $sendcallback = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'accept' => 'application/json'
                    ])->post($cashout->callback, $payload);

                    Log::debug("[PIX-OUT] Send Callback: Para $cashout->callback -> Enviando...");
                    if ($cashout->callback && $cashout->callback != 'web') {
                        $payload = [
                            "status" => "canceled",
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
        }

        return response()->json([]);
    }

    public static function registerWebhook(Request $request)
    {
        return ZoompagTrait::registerWebhooksZoompag();
    }
}
