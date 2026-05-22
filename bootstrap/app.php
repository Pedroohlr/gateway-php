<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            RateLimiter::for('custom-ip-limit', function (Request $request) {
                $ip = $request->header('X-Forwarded-For') ?
                    $request->header('X-Forwarded-For') :
                    ($request->header('CF-Connecting-IP') ?
                        $request->header('CF-Connecting-IP') :
                        $request->ip());

                \Log::debug('RATELIMIT IP: ' . $ip);
                return Limit::perMinute(5)
                    ->by($ip)
                    ->response(function (Request $request, array $headers) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Muitas requisições. Tente novamente mais tarde.'
                        ]);
                    });
            });
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append([
            \App\Http\Middleware\AtualizarSaldosClientes::class,
        ]);
        $middleware->validateCsrfTokens([
            '/cashtime/*',
            '/cartwave/*',
            '/apithekey/*',
            '/simpay/*',
            '/witetec/*',
            '/zoompag/*',
            '/mercadopago/*',
            '/blupay/*',
            '/fcm/*'
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\BlockInvalidUploads::class
        ]);


        $middleware->alias([
            'check.token.secret' => \App\Http\Middleware\CheckTokenAndSecret::class,
            'custom.throttle' => \App\Http\Middleware\CustomThrottle::class,
          	'auth_admin' => \App\Http\Middleware\AuthAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
