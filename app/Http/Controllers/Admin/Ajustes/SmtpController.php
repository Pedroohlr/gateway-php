<?php

namespace App\Http\Controllers\Admin\Ajustes;

use App\Http\Controllers\Controller;
use App\Models\App;
use App\Models\Smtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SmtpController extends Controller
{
    public function index()
    {
        $smtp = Smtp::first();
        return view("admin.ajustes.smtp", compact('smtp'));
    }

    public function store(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        // Começa com os dados enviados
        $payload = $data;

        if (empty($payload['auth_title'])) {
            unset($payload['auth_title']);
        }

        if (empty($payload['auth_message'])) {
            unset($payload['auth_message']);
        }
        // ========== UPLOAD DA IMAGEM ==========
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();

            // pasta public/uploads
            $destination = public_path('uploads');
            if (!file_exists($destination)) {
                mkdir($destination, 0775, true);
            }

            $file->move($destination, $filename);

            // Caminho que será salvo no banco
            $payload['image'] = '/uploads/' . $filename;
        }

        // Atualiza tabela smtp com todos os dados
        Smtp::first()->update($payload);

        // ========== ATUALIZA .ENV ==========
        $setting = App::first();
        $app_name = $setting->gateway_name ?? env('APP_NAME');

        $smtp_host = $data['host'];
        $smtp_port = $data['port'];
        $smtp_user = $data['user'];
        $smtp_pass = $data['pass'];

        $this->updateEnv('MAIL_MAILER', 'smtp');
        $this->updateEnv('MAIL_HOST', "'$smtp_host'");
        $this->updateEnv('MAIL_PORT', "'$smtp_port'");
        $this->updateEnv('MAIL_USERNAME', "'$smtp_user'");
        $this->updateEnv('MAIL_PASSWORD', "'$smtp_pass'");
        $this->updateEnv('MAIL_FROM_ADDRESS', "'$smtp_user'");
        $this->updateEnv('MAIL_FROM_NAME', "'$app_name'");

        return back()->with('success', 'Configurações alteradas com sucesso.');
    }


    public function updateEnv($key, $value)
    {
        $path = base_path('.env');

        if (File::exists($path)) {
            // carrega conteúdo
            $env = File::get($path);

            // se já existe a variável, substitui
            if (str_contains($env, $key . '=')) {
                $env = preg_replace(
                    '/^' . $key . '=.*/m',
                    $key . '=' . $value,
                    $env
                );
            } else {
                // senão adiciona no final
                $env .= "\n{$key}={$value}\n";
            }

            File::put($path, $env);
        }
    }
}