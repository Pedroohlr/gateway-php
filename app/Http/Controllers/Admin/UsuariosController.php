<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\App;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UsersKey;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UsuariosController extends Controller
{
    public function index(Request $request)
    {
        $now = Carbon::now();

        $todayStart = $now->startOfDay();
        $todayEnd = $now->endOfDay();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfWeek = $now->copy()->startOfWeek();

        // Total de cadastros
        $totalCadastros = User::where('permission', '!=', 9)->count();

        // Cadastros hoje
        $cadastrosHoje = User::where('permission', '!=', 9)->whereBetween('data_cadastro', [$todayStart, $todayEnd])->count();

        // Cadastros no mês
        $cadastrosMes = User::where('permission', '!=', 9)->where('data_cadastro', '>=', $startOfMonth)->count();

        // Cadastros na semana
        $cadastrosSemana = User::where('permission', '!=', 9)->where('data_cadastro', '>=', $startOfWeek)->count();

        $limit = 10000000; // Número de registros por página
        $page = $request->query('page', 1); // Página atual
        $list_users = User::where('permission', '!=', 9)->orderBy('data_cadastro', 'DESC')->paginate($limit);

        $gerentes = User::where('permission', 9)->get();
        
        return view("admin.usuarios", compact(
            "totalCadastros",
            "cadastrosHoje",
            "cadastrosMes",
            "cadastrosSemana",
            "list_users",
            "gerentes"
        ));
    }

    public function detalhes($id, Request $request)
    {
        // Obter a data e hora atual usando Carbon
        $now = Carbon::now();

        // Início e fim do dia de hoje
        $todayStart = $now->copy()->startOfDay()->toDateTimeString();
        $todayEnd = $now->copy()->endOfDay()->toDateTimeString();

        // Início do mês
        $startOfMonth = $now->copy()->startOfMonth()->toDateTimeString();

        // Início da semana
        $startOfWeek = $now->copy()->startOfWeek()->toDateTimeString();

        // Consultas para obter os totais
        $totalCadastros = User::count();

        $cadastrosHoje = User::whereBetween('data_cadastro', [$todayStart, $todayEnd])
            ->count();

        $cadastrosMes = User::where('data_cadastro', '>=', $startOfMonth)
            ->count();

        $cadastrosSemana = User::where('data_cadastro', '>=', $startOfWeek)
            ->count();

        $usuario = User::find($id);
        
        return view('admin.usuariodetalhes', compact('usuario'));
    }

    public function usuarioStatus(Request $request)
    {
        
        $message = "";
        $usuarioId = $request->input('id');
        $usuario = User::where('id', $usuarioId)->first();
        //dd($request->all());
        if ($request->tipo === 'status') {
            if(!is_null($request->input('reprovar')) && $request->input('reprovar') == "true"){
                $status = 0;
                $message = "Status alterado para solicitar envio de docs!";
                $usuario->update(['status' => $status]);
            } elseif(!is_null($request->input('reprovar')) && $request->input('reprovar') == "reprovado"){
                $status = 99;
                $message = "Status alterado para reprovado!";
                $usuario->update(['status' => $status]);
            } elseif(!is_null($request->input('reavaliar')) && $request->input('reavaliar') == "true") {
                $status = 5;
                $message = "Status alterado para pendente!";
                $usuario->update(['status' => $status]);
            } elseif(!is_null($request->input('aprovar')) && $request->input('aprovar') == "true") {
                $status = 1;
                $message = "Status alterado para aprovado!";
                $usuario->update(['status' => $status]);
            }
        }

        if ($request->tipo === 'banido') {
            $banido = $usuario->banido == 1 ? 0 : 1;
            $message = $banido == 0 ? "Usuário desbanido com sucesso!" : "Usuário banido com sucesso!";
            $usuario->update(['banido' => $banido]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function destroy($id, Request $request)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('error', "Usuário não encontrado!");
        }

        $user->delete($id);
        return redirect()->route('admin.usuarios')->with('error', "Usuário removido com sucesso!");
    }

    public function edit($id, Request $request)
    {
        
        if (!isset($id)) {
            return redirect()->back()->with('error', "Selecione um usuário!");
        }

        $email = $request->input('email');
        $name = $request->input('name');
        $permission = $request->input('permission');
        $taxa_cash_in = (float) str_replace(",",".", $request->input('taxa_cash_in'));
        $taxa_cash_out = (float) str_replace(",",".", $request->input('taxa_cash_out'));
        $taxa_cash_in_fixa = (float) str_replace(",",".", $request->input('taxa_cash_in_fixa'));
        $taxa_cash_out_fixa = (float) str_replace(",",".", $request->input('taxa_cash_out_fixa'));

        $token = $request->input('token');
        $secret = $request->input('secret');


        $user = User::find($id);

        if (!isset($user)) {
            return redirect()->back()->with('error', "Usuário não encontrado!");
        }

        if ($user->email != $email) {
            $validation = $request->validate([
                'email' => ['unique:users,email'],
            ]);

            if (!$validation) {
                return redirect()->back()->with('error', "Email já cadastrado na base!");
            }
        }

        $payl = [
            'email' => $email,
            'name' => $name,
            'permission' => $permission,
            'taxa_cash_in' => $taxa_cash_in,
            'taxa_cash_out' => $taxa_cash_out,
            'taxa_cash_in_fixa' => $taxa_cash_in_fixa,
            'taxa_cash_out_fixa' => $taxa_cash_out_fixa
        ];

        if (!is_null($request->password)) {
            $payl['password'] = Hash::make($request->input('password'));
        }

        User::where('id', $id)->update($payl);

        $userkey = UsersKey::where('user_id', $user->user_id)->first();
        if (!$userkey) {
            $user_id = $user->user_id;
            $userkey = UsersKey::create(compact('user_id', 'token', 'secret'));
        }

        $userkey->update(compact('token', 'secret'));

        return redirect()->back()->with('success', "Usuário alterado com sucesso!");
    }

    public function definirCarteiraLucro(Request $request)
    {
        $data = $request->except(['_method', '_token']);
        $carteira_lucro = $data['carteira_lucro'];

        if(auth()->user()->permission !== 3) {
            return back()->with('error', 'Usuário sem permissões.');
        }
        App::first()->update($data);

        $message = "{$carteira_lucro} é a nova conta de recebimentos de lucros!";
        if(!$carteira_lucro){
            $message = "Conta de recebimento desabilitado com sucesso!";
        }
        return back()->with('success', $message);
    }
}
