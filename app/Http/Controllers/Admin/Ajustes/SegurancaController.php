<?php

namespace App\Http\Controllers\Admin\Ajustes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\App;
use App\Models\Fcm;

class SegurancaController extends Controller
{
    public function index()
    {
        $setting = App::first();
        return view("admin.ajustes.gerais", compact('setting'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method', 'gateway_logo', 'gateway_favicon', 'gateway_banner_home']);
        $payload = [];

        foreach ($data as $key => $value) {
            $payload[$key] = (
                $key === 'gateway_name' ||
                $key === 'cnpj' ||
                $key === 'gateway_color' ||
                $key === 'bg_theme'
            ) ? $value : (float) $value;
        }

        $imageFields = ['gateway_logo', 'gateway_favicon', 'gateway_banner_home'];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();

                // Caminho: public/uploads
                $destination = public_path('uploads');
                if (!file_exists($destination)) {
                    mkdir($destination, 0775, true);
                }

                $file->move($destination, $filename);

                // Caminho acessível via navegador
                $payload[$field] = '/uploads/' . $filename;
            } else {
                unset($payload[$field]); // Corrigido: $payload, não $data
            }
        }

        function normalizeDecimal(string $valor): string
        {
            $valor = trim($valor);

            // Se contém vírgula, ela é o separador decimal
            if (str_contains($valor, ',')) {
                $partes = explode(',', $valor);
                $inteiro = preg_replace('/[^0-9]/', '', $partes[0]); // remove pontos e lixo
                $decimal = isset($partes[1]) ? preg_replace('/[^0-9]/', '', $partes[1]) : '00';
                $valor = $inteiro . '.' . $decimal;
            } else {
                // Só ponto ou só números → separador decimal é ponto
                $partes = explode('.', $valor);
                if (count($partes) > 1) {
                    $decimal = array_pop($partes);
                    $inteiro = preg_replace('/[^0-9]/', '', implode('', $partes));
                    $valor = $inteiro . '.' . $decimal;
                } else {
                    $valor = preg_replace('/[^0-9]/', '', $valor) . '.00';
                }
            }

            return number_format((float) $valor, 2, '.', '');
        }

        // Atualiza as configurações
        $setting = App::first();
        if ($setting) {
            $payload['taxa_fixa_padrao_cash_out'] = normalizeDecimal($data['taxa_fixa_padrao_cash_out']);
            $payload['baseline'] = normalizeDecimal($data['baseline']);
            $payload['hour_limit_withdraw'] = $data['hour_limit_withdraw'] === 'none' ? null : (int) $data['hour_limit_withdraw'];
            $payload['limite_saque_automatico'] = normalizeDecimal($data['limite_saque_automatico']);

            $setting->update($payload);
        }

        return back()->with('success', 'Dados alterados com sucesso!');
    }

    public function notificacaoIndex()
    {
        $fcm = Fcm::first();
        return view("admin.ajustes.notificacoes", compact('fcm'));
    }

    public function notificacaoUpdate(Request $request)
    {
        $fcm = [
            "title" => $request->input('title'),
            "body" => $request->input('body'),
            "title_cashout" => $request->input('title_cashout'),
            "body_cashout" => $request->input('body_cashout'),
        ];

        Fcm::first()->update($fcm);

        return back()->with('success', 'Dados alterados com sucesso!');
    }
}
