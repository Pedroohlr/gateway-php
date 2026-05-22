<?php

namespace App\Http\Controllers;

use App\Models\App;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PushSubscription;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class PushController extends Controller
{
    public function save(Request $request)
    {
        $data = $request->all()['subscription'];

        PushSubscription::updateOrCreate(
            ['endpoint' => $data['endpoint']],
            [
                'public_key' => $data['keys']['p256dh'],
                'auth_token' => $data['keys']['auth'],
                'user_id' => $request->input('user_id'),
                'device_id' => $request->input('device_id'),
                'device_name' => $request->input('device_name'),
            ]
        );

        return response()->json(['success' => true]);
    }

    public function send(Request $request)
    {
        $setting = App::first();
        $notifications = [];

        foreach (PushSubscription::all() as $sub) {
            $notifications[] = [
                'subscription' => Subscription::create([
                    'endpoint' => $sub->endpoint,
                    'publicKey' => $sub->public_key,
                    'authToken' => $sub->auth_token,
                ]),
                'payload' => json_encode([
                    'title' => $request->title ?? 'Título padrão',
                    'body' => $request->body ?? 'Mensagem padrão',
                    'icon' => asset($setting->gateway_favicon),
                    'url' => $request->url ?? '/'
                ]),
            ];
        }

        $webPush = new WebPush([
            'VAPID' => [
                'subject' => env('VAPID_EMAIL'),
                'publicKey' => env('VAPID_PUBLIC_KEY'),
                'privateKey' => env('VAPID_PRIVATE_KEY'),
            ],
        ]);

        foreach ($notifications as $n) {
            $webPush->sendOneNotification(
                $n['subscription'],
                $n['payload']
            );
        }

        return back()->with('success', 'Notificação enviada com sucesso.');
    }

    public function sendAll(Request $request)
    {
        $setting = App::first();
        $notifications = [];

        foreach (PushSubscription::all() as $sub) {
            $notifications[] = [
                'subscription' => Subscription::create([
                    'endpoint' => $sub->endpoint,
                    'publicKey' => $sub->public_key,
                    'authToken' => $sub->auth_token,
                ]),
                'payload' => json_encode([
                    'title' => $request->title ?? 'Título padrão',
                    'body' => $request->body ?? 'Mensagem padrão',
                    'icon' => asset($setting->gateway_favicon),
                    'url' => $request->url ?? '/'
                ]),
            ];
        }

        $webPush = new WebPush([
            'VAPID' => [
                'subject' => env('VAPID_EMAIL'),
                'publicKey' => env('VAPID_PUBLIC_KEY'),
                'privateKey' => env('VAPID_PRIVATE_KEY'),
            ],
        ]);

        foreach ($notifications as $n) {
            $webPush->sendOneNotification(
                $n['subscription'],
                $n['payload']
            );
        }

        return true;
    }

    public function sendToUser(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $setting = App::first();

        $subscriptions = $user->devices()
            ->whereNotNull('endpoint')
            ->get();

        $webPush = new WebPush([
            'VAPID' => [
                'subject' => env('VAPID_EMAIL'),
                'publicKey' => env('VAPID_PUBLIC_KEY'),
                'privateKey' => env('VAPID_PRIVATE_KEY'),
            ],
        ]);

        foreach ($subscriptions as $device) {
            $subscription = Subscription::create([
                'endpoint' => $device->endpoint,
                'publicKey' => $device->public_key,
                'authToken' => $device->auth_token,
            ]);

            $payload = json_encode([
                'title' => $request->title ?? 'Nova notificação!',
                'body' => $request->body ?? 'Mensagem padrão',
                'icon' => asset($setting->gateway_favicon),
                'url' => $request->url ?? '/'
            ]);

            $webPush->sendOneNotification($subscription, $payload);
        }

        return response()->json(['sent' => true]);
    }
}

