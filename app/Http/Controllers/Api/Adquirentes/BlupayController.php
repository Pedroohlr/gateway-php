<?php

namespace App\Http\Controllers\Api\Adquirentes;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CheckoutOrders;
use App\Models\Fcm;
use App\Models\Infracoes;
use App\Models\Solicitacoes;
use App\Models\SolicitacoesCashOut;
use App\Models\User;
use App\Services\SendNotification;
use App\Services\WalletService;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BlupayController extends Controller
{

    public function callbackDeposit(Request $request)
    {
        $data = $request->all();

        Log::info("[+][BLUPAY][CALLBACK][DEPOSIT]: ", $data);

        $event = $data['event'] ?? 'transaction.pending';
        $idTransaction = $data['data']['id'] ?? '';
        $status = $data['data']['status'] ?? 'pending';
        //dd($event, $idTransaction, $status);
        switch ($event) {
            case "transaction.paid":
                if (!empty($idTransaction) && $status == "paid") {
                    $cashin = Solicitacoes::where('idTransaction', $idTransaction)->first();
                    //dd($cashin);
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

                    if (str_contains($cashin->callback, 'http')) {
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
            case "transaction.refunded":
            case "transaction.infraction":
                $cashin = Solicitacoes::where('idTransaction', $idTransaction)->first();

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

                if (str_contains($cashin->callback, 'http')) {
                    $payload = [
                        "status" => "med",
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
                        $order = CheckoutOrders::where('idTransaction', $data['idTransaction'])->first();
                        if ($order) {
                            $order->update(['status' => 'med']);
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
        Log::info("[+][BLUPAY][CALLBACK][WITHDRAW]: ", $data);

        $event = $data['event'] ?? 'pending';
        $idTransaction = $data['data']['id'] ?? '';
        $status = $data['data']['status'] ?? 'pending';


        switch ($event) {
            case "transfer.paid":
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

                if (str_contains($cashout->callback, 'http')) {
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
                    if (str_contains($cashout->callback, 'http')) {
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
            case "transfer.failed":
                $cashout = SolicitacoesCashOut::where('idTransaction', $idTransaction)->first();

                $message = 'Erro na Adquirencia.';
                $cashout->update(['status' => 'CANCELLED', 'descricao_externa' => $message]);

                if (str_contains($cashout->callback, 'http')) {
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
}
