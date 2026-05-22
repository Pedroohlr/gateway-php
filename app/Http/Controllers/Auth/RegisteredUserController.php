<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\App;
use App\Models\User;
use App\Models\UsersKey;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'username' => [
                'required',
                'string',
                'regex:/^\S*$/',
                'unique:users,username',
                'regex:/^[\p{L}\s]+$/u'
            ],
            'name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'telefone' => ['required', 'string', 'unique:users,telefone'],
            'password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                'confirmed'
            ],
        ]);

        $senhaHash = Hash::make($request->password);

        // Gerando IDs e valores adicionais
        $clienteId = Str::uuid()->toString();
        $saldo = 0;
        $status = 0;
        $dataCadastroFormatada = Carbon::now('America/Sao_Paulo')->format('Y-m-d H:i:s');

        $indicador_ref = $request->input('ref') ?? NULL;
        // Taxas padrões
        $app = App::first();
        $taxa_cash_in = $app->taxa_cash_in_padrao ?? 5;
        $taxa_cash_out = $app->taxa_cash_out_padrao ?? 5;
        $taxa_cash_out_fixa = $app->taxa_fixa_padrao_cash_out;
        $taxa_cash_in_fixa = $app->taxa_fixa_padrao;

        $code_ref = uniqid();
        
        $gerenteComMenosClientes = User::where('permission', 9)
        ->withCount('clientes') // Usando relacionamento clientes()
        ->orderBy('clientes_count', 'asc')
        ->first();


        // Criando usuário
        $user = User::create([
            'username' => $request->username,
            'user_id' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'password' => $senhaHash,
            'telefone' => $request->telefone,
            'saldo' => $saldo,
            'data_cadastro' => $dataCadastroFormatada,
            'status' => $status,
            'cliente_id' => $clienteId,
            'taxa_cash_in' => $taxa_cash_in,
            'taxa_cash_out' => $taxa_cash_out,
            'taxa_cash_in_fixa' => $taxa_cash_in_fixa,
            'taxa_cash_out_fixa' => $taxa_cash_out_fixa,
            'code_ref' => $code_ref,
            'indicador_ref' => $indicador_ref,
            'gerente_id' => $gerenteComMenosClientes->id ?? NULL
        ]);

        $token = Str::uuid()->toString();
        $secret = Str::uuid()->toString();
        $apikey = uniqid();

        $user_id = $user->user_id;

        UsersKey::create(compact('user_id','token','secret', 'apikey'));

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
