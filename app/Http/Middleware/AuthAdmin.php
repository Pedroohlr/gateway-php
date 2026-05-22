<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se o usuário está logado E se a permissão é diferente de 3
        if (auth()->check() && auth()->user()->permission !== 3) {
            return redirect('/dashboard');
        }

        // Se for admin (3), permite que a requisição continue
        return $next($request);
    }
}