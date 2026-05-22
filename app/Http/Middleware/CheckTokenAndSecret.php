<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UsersKey;
use Illuminate\Support\Facades\Response;

class CheckTokenAndSecret
{
    public function handle(Request $request, Closure $next)
    {
        // Pegue o token e secret do corpo da requisição
        $aut_token = $request->bearerToken();
       
        $aut_token = base64_decode($aut_token);
        $aut_token = explode(':', $aut_token);

        $token = $aut_token[0];
        $secret = $aut_token[1];

        //dd($apikey, $request->bearerToken());
        // Verifique se ambos os parâmetros token e secret foram enviados
        if (!$token || !$secret) {
            return Response::json([
                'error' => 'Token ou Secret ausentes',
                'message' => 'Você precisa fornecer tanto o token quanto o secret.'
            ], 400); // Retorna um erro 400 se os parâmetros não forem fornecidos
        }

        // Verifique se existe um usuário com esse token e secret
        $chaves = UsersKey::where('token', $token)->where('secret', $secret)->first();

        // Se o usuário não for encontrado, retorna um erro
        if (!$chaves) {
            return Response::json([
                'status' => "error",
                'message' => 'Token ou Secret inválidos'
            ], 401); // Retorna um erro 401 se o token ou secret não forem válidos
        }

        $user = User::where('username', $chaves->user_id)->first();
      	if($user->banido == 1 || $user->status != 1){
			 return Response::json([
                'status' => "error",
                'message' => 'Usuário sem permissões. Fale com seu gerente.'
            ], 401); // Retorna um erro 401 se o token ou secret não forem válidos
        }
        //dd($user);
        // Se o usuário for encontrado, adicione o usuário à requisição
        $request->merge(['user' => $user]);

        // Prossiga com a requisição
        return $next($request);
    }
}
