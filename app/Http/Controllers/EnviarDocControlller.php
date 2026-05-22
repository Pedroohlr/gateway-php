<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EnviarDocControlller extends Controller
{
    public function index()
    {

        if(auth()->user()->status === 1) {
            return redirect()->route('dashboard')->with(['success'=> "Sua conta já está ativa."]);
        }

        return view("profile.enviardoc");
    }

    public function enviarDocs($id, Request $request)
    {


         //dd($request);
        try {
          $request->validate([
              'data_nascimento' => 'required',
              'cpf_cnpj' => 'required|string',
              'cep' => 'required|string',
              'rua' => 'required|string',
              'numero_residencia' => 'required|string',
              'complemento' => 'nullable|string',
              'bairro' => 'required|string',
              'cidade' => 'required|string',
              'estado' => 'required|string',
              'media_faturamento' => 'required|string',
              'foto_rg_frente' => 'required|image|max:10240',
              'foto_rg_verso' => 'required|image|max:10240',
              'selfie_rg' => 'required|image|max:10240',
          ]);

          $user = auth()->user();


              $fotoRgFrente = self::salvarArquivo($request, 'foto_rg_frente');
              $fotoRgVerso  = self::salvarArquivo($request, 'foto_rg_verso');
              $selfieRg     = self::salvarArquivo($request, 'selfie_rg');


              // Salvar os caminhos corretamente no banco de dados
              DB::table('users')->where('id', $id)->update([
                  'data_nascimento' => $request->data_nascimento,
                  'cpf_cnpj' => $request->cpf_cnpj,
                  'cep' => $request->cep,
                  'rua' => $request->rua,
                  'numero_residencia' => $request->numero_residencia,
                  'complemento' => $request->complemento,
                  'bairro' => $request->bairro,
                  'cidade' => $request->cidade,
                  'estado' => $request->estado,
                  'media_faturamento' => $request->media_faturamento,
                  'foto_rg_frente' => $fotoRgFrente ?? "",
                  'foto_rg_verso' => $fotoRgVerso ?? "",
                  'selfie_rg' => $selfieRg ?? "",
                  'status' => 5,
              ]);

              return redirect()
                  ->route("dashboard")
                  ->with("success", "Documentos enviados com sucesso.");

      } catch (ValidationException $e) {
          return redirect()->back()
              ->withErrors($e->validator)
              ->withInput();
      }

}

  public static function salvarArquivo($request, $inputName)
{
    if ($request->hasFile($inputName)) {
        $destination = public_path('uploads');
        if (!file_exists($destination)) {
            mkdir($destination, 0775, true);
        }

        $file = $request->file($inputName);
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        if ($file->move($destination, $filename)) {
            return 'uploads/' . $filename;
        } else {
            \Log::error("Erro ao mover arquivo $inputName");
            return null;
        }
    }

    return null;
}
}
