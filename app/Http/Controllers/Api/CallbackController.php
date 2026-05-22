<?php

namespace App\Http\Controllers\Api;

use App\Models\Fcm;
use App\Services\SendNotification;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\Solicitacoes;
use App\Models\User;
use App\Models\SolicitacoesCashOut;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Models\CheckoutOrders;

class CallbackController extends Controller
{
    public function callbackDeposit(Request $request)
    {
        $data = $request->all();
        switch ($data['status']) {
            case 'paid':
                $cashin = Solicitacoes::where('idTransaction', $data['orderId'])->first();
                if (!$cashin || $cashin->status != "WAITING_FOR_APPROVAL") {
                    return response()->json(['status' => false]);
                }

                $updated_at = Carbon::now();
                $cashin->update(['status' => 'PAID_OUT', 'updated_at' => $updated_at]);
                $wallet = new WalletService();
                $wallet->createSaldoIn($cashin);

                $user = User::where('user_id', $cashin->user_id)->with('devices')->first();
                $devices = $user?->devices ?? null;

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
                break;
            case 'refused':
            case 'refunded':
            case 'infraction':
                $cashin = Solicitacoes::where('idTransaction', $data['orderId'])->first();
                if (!$cashin || $cashin->status != "WAITING_FOR_APPROVAL") {
                    return response()->json(['status' => false]);
                }

                $updated_at = Carbon::now();
                $cashin->update(['status' => 'CANCELLED', 'updated_at' => $updated_at]);

                $user = User::where('user_id', $cashin->user_id)->first();
                Helper::calculaSaldoLiquido($user->user_id);
                return response()->json(['status' => true]);
            default:
                return response()->json(['status' => false]);
                break;
        }
    }

    public function callbackWithdraw(Request $request)
    {
        $data = $request->all();
        if ($data['withdrawStatusId'] == "Successfull") {
            $cashout = SolicitacoesCashOut::where('idTransaction', $data['id'])->first();
            if (!$cashout || $cashout->status != "PENDING") {
                return response()->json(['status' => false]);
            }

            $cashout->update(['status' => 'COMPLETED', 'updated_at' => $data['updatedAt']]);
            $wallet = new WalletService();
            $wallet->createSaldoOut($cashout);

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

            Helper::decrementAmount($user, $request->amount, 'valor_saque_pendente');

            if ($cashout->callback) {
                $payload = [
                    "status" => "paid",
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
                        "status" => "paid",
                        "idTransaction" => $cashout->idTransaction,
                        "typeTransaction" => "PAYMENT"
                    ];

                    Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'accept' => 'application/json'
                    ])->post($cashout->callback, $payload);

                    return response()->json(['status' => true]);
                }
            }

        } else {
            $cashout = SolicitacoesCashOut::where('idTransaction', $data['id'])->first();

            $cashout->update(['status' => 'CANCELLED', 'updated_at' => $data['updatedAt']]);

            if ($cashout->callback) {
                $payload = [
                    "status" => "canceled",
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
                        "status" => "canceled",
                        "idTransaction" => $cashout->idTransaction,
                        "typeTransaction" => "PAYMENT"
                    ];

                    Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'accept' => 'application/json'
                    ])->post($cashout->callback, $payload);

                    return response()->json(['status' => true]);
                }
            }
        }
    }


    public function callbackDepositApithekey(Request $request)
    {
        $data = $request->all();

        \Log::debug('DEPOSIT APITHEKEY: ' . json_encode($data));
        if (isset($data['status']) && $data['status'] == "COMPLETED") {
            $cashin = Solicitacoes::where('idTransaction', $data['transaction_id'])->first();

            if (!$cashin) {
                return response()->json(['status' => false]);
            }

            if ($cashin->status != "WAITING_FOR_APPROVAL") {
                return response()->json(['status' => false]);
            }

            $updated_at = Carbon::now();
            $cashin->update(['status' => 'PAID_OUT', 'updated_at' => $updated_at]);
            $wallet = new WalletService();
            $wallet->createSaldoIn($cashin);

            $user = User::where('user_id', $cashin->user_id)->with('devices')->first();
            $devices = $user?->devices ?? null;

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

            $order = CheckoutOrders::where('idTransaction', $data['transaction_id'])->first();
            if ($order) {
                $order->update(['status' => 'pago']);
            }

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

                return response()->json([]);
            } else {
                return response()->json([]);
            }

        } else {
            return response()->json([]);
        }
    }

    public function callbackWithdrawApithekey(Request $request)
    {
        $data = $request->all();
        sleep(3);
        \Log::debug('WITHDRAW APITHEKEY: ' . json_encode($data));
        // dd($data);
        $statusAdquirente = $data['status'] ?? "PENDING";
        switch ($statusAdquirente) {
            case 'COMPLETED':
                $cashout = SolicitacoesCashOut::where('idTransaction', $data['transaction_id'])->first();
                // dd($cashout);
                \Log::debug('WITHDRAW APITHEKEY CASHOUT: ' . json_encode($cashout));
                if (!$cashout || $cashout->status != "PENDING") {
                    \Log::debug('WITHDRAW APITHEKEY != PENDENTE ');
                    return response()->json(['status' => false]);
                }

                $updated_at = Carbon::now();
                $cashout->update(['status' => 'COMPLETED', 'updated_at' => $updated_at]);
                $wallet = new WalletService();
                $wallet->createSaldoOut($cashout);

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

                \Log::debug('WITHDRAW APITHEKEY CASHOUT UPDATE: ' . json_encode($cashout));
                $user = User::where('user_id', $cashout->user_id)->first();
                Helper::decrementAmount($user, $request->amount, 'valor_saque_pendente');

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

                    return response()->json([]);
                } else {
                    return response()->json([]);
                }
                break;
            case 'FAILED':
            case 'REJECTED':
                $cashout = SolicitacoesCashOut::where('idTransaction', $data['transaction_id'])->first();
                // dd($cashout);
                \Log::debug('WITHDRAW APITHEKEY CASHOUT: ' . json_encode($cashout));

                $updated_at = Carbon::now();
                $cashout->update(['status' => 'CANCELLED', 'updated_at' => $updated_at]);
                $user = $cashout->user;


                \Log::debug('WITHDRAW APITHEKEY CASHOUT UPDATE: ' . json_encode($cashout));
                $user = User::where('user_id', $cashout->user_id)->first();

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

                    return response()->json([]);
                } else {
                    return response()->json([]);
                }
                break;

            default:
                # code...
                break;
        }
    }

    public function callbackDepositSimpay(Request $request)
    {
        $data = $request->all();

        \Log::debug('DEPOSIT SIMPAY: ' . json_encode($data));
        if (isset($data['payment_status']) && $data['payment_status'] == "paid") {
            $cashin = Solicitacoes::where('idTransaction', $data['payment_id'])->first();

            if (!$cashin) {
                return response()->json(['status' => false]);
            }

            if ($cashin->status != "WAITING_FOR_APPROVAL") {
                return response()->json(['status' => false]);
            }


            $updated_at = Carbon::now();
            $cashin->update(['status' => 'PAID_OUT', 'updated_at' => $updated_at]);
            $wallet = new WalletService();
            $wallet->createSaldoIn($cashin);


            $user = User::where('user_id', $cashin->user_id)->with('devices')->first();
            $devices = $user?->devices ?? null;

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

            $order = CheckoutOrders::where('idTransaction', $data['payment_id'])->first();
            if ($order) {
                $order->update(['status' => 'pago']);
            }

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

                return response()->json([]);
            } else {
                return response()->json([]);
            }

        } else {
            return response()->json([]);
        }
    }

    public function callbackWithdrawSimpay(Request $request)
    {
        $data = $request->all();
        sleep(3);
        $arraystatus = ["cancelled", "refused"];
        \Log::debug('WITHDRAW SIMPAY: ' . json_encode($data));
        if (isset($data['status']) && $data['status'] == "approved") {
            $cashout = SolicitacoesCashOut::where('idTransaction', $data['withdrawal_id'])->first();
            // dd($cashout);
            \Log::debug('WITHDRAW SIMPAY CASHOUT: ' . json_encode($cashout));
            if (!$cashout) {
                \Log::debug('WITHDRAW SIMPAY: Transação não existe ');
                return response()->json(['status' => false]);
            }

            if ($cashout->status == "COMPLETED") {
                \Log::debug('WITHDRAW SIMPAY: Transação ja foi paga.');
                return response()->json(['status' => false]);
            }

            $updated_at = Carbon::now();
            $cashout->update(['status' => 'COMPLETED', 'updated_at' => $updated_at]);
            $wallet = new WalletService();
            $wallet->createSaldoOut($cashout);

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

            \Log::debug('WITHDRAW SIMPAY CASHOUT UPDATE: ' . json_encode($cashout));
            $user = User::where('user_id', $cashout->user_id)->first();
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

                return response()->json([]);
            } else {
                return response()->json([]);
            }
        } elseif ($data['status'] == 'cancelled' || $data['status'] == 'failed') {
            $cashout = SolicitacoesCashOut::where('idTransaction', $data['withdrawal_id'])->first();
            if (!$cashout) {
                \Log::debug('WITHDRAW SIMPAY: Transação não existe ');
                return response()->json(['status' => false]);
            }

            $cashout->update(['status' => "CANCELLED"]);
            \Log::debug('WITHDRAW SIMPAY CASHOUT UPDATE: ' . json_encode($cashout));

            if ($cashout->callback && $cashout->callback != 'web') {
                $payload = [
                    "status" => "cancelled",
                    "idTransaction" => $cashout->idTransaction,
                    "typeTransaction" => "PAYMENT"
                ];

                $sendcallback = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'accept' => 'application/json'
                ])->post($cashout->callback, $payload);

                return response()->json([]);
            } else {
                return response()->json([]);
            }
        } else {
            return response()->json([]);
        }
    }
}
