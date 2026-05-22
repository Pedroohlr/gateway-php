<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Symfony\Component\HttpFoundation\Response;

class CustomThrottle extends ThrottleRequests
{
    protected function buildResponse(Request $request, string $key, int $maxAttempts, int $retryAfter): Response
    {
        // Verifica se o client espera JSON
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Você está fazendo muitas requisições. Tente novamente em ' . $retryAfter . ' segundos.',
                'status' => 'error',
            ], 429);
        }

        // Fallback para resposta padrão (HTML)
        return parent::buildResponse($request, $key, $maxAttempts, $retryAfter);
    }
}
