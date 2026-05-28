<?php

namespace App\Helpers;

use App\Models\Gamefication;
use App\Models\Solicitacoes;
use App\Models\SolicitacoesCashOut;
use App\Models\User;
use App\Models\App;
use App\Models\Pwa;
use App\Models\CheckoutBuild;
use Illuminate\Support\Facades\Http;

class Helper
{

    public static function calculaSaldoLiquido($user_id)
    {
        $gamefications = Gamefication::orderBy('min')->get(); // garantir ordem crescente por min
        $nivelSelecionado = null;
        $proxNivelId = null;

        try {
            // Soma dos depósitos líquidos com status "PAID_OUT"
            $totalDepositoLiquido = Solicitacoes::where('user_id', $user_id)
                ->where('status', 'PAID_OUT')
                ->sum('deposito_liquido');

            // Soma dos saques aprovados com status "COMPLETED"
            $totalSaquesAprovados = SolicitacoesCashOut::where('user_id', $user_id)
                ->whereIn('status', ['COMPLETED'])
                ->sum('amount');

            $totalSaldoBloqueado = SolicitacoesCashOut::where('user_id', $user_id)
                ->where('status', 'PENDING')
                ->whereIn('descricao_transacao', ['WEB', 'LIBERADOADMIN'])
                ->sum('amount');

            // Cálculo do saldo líquido
            $saldoLiquido = (float) $totalDepositoLiquido - (float) $totalSaquesAprovados - (float) $totalSaldoBloqueado;

            foreach ($gamefications as $nivel) {
                if ($totalDepositoLiquido >= $nivel->min && $totalDepositoLiquido <= $nivel->max) {
                    $nivelSelecionado = $nivel->id;
                } elseif ($totalDepositoLiquido < $nivel->min && $proxNivelId === null) {
                    $proxNivelId = $nivel->id;
                }
            }

            // Atualizar o saldo do usuário
            $updated = User::where('user_id', $user_id)->first();
            $updated->saldo = $saldoLiquido;
            $updated->valor_saque_pendente = $totalSaldoBloqueado;
            $updated->nivel = $nivelSelecionado;
            $updated->prox_nivel = $proxNivelId;
            $updated->save();

            if ($updated->permission == 9) {
                $updated->saldo = 0;
                $updated->save();
            }

            return $updated ? true : false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function calcularSaldoLiquidoUsuarios()
    {
        $users = User::get();
        foreach ($users as $user) {
            self::calculaSaldoLiquido($user->user_id);
        }
    }

    public static function gerarPessoa()
    {
        $url = "https://www.4devs.com.br/ferramentas_online.php";

        $data = [
            'acao' => 'gerar_pessoa',
            'sexo' => 'I',
            'pontuacao' => 'N',
            'idade' => 0,
            'cep_estado' => '',
            'txt_qtde' => 1,
            'cep_cidade' => ''
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Referer' => 'https://www.4devs.com.br/gerador_de_pessoas',
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36 OPR/114.0.0.0',
        ])->asForm()->post($url, $data);

        if ($response->successful()) {
            $dados = $response->json();
            if (isset($dados[0]['nome']) && isset($dados[0]['cpf']) && isset($dados[0]['email'])) {
                return $dados[0];
            }
        }

        return null;
    }

    public static function soNumero($str)
    {
        return preg_replace("/[^0-9]/", "", $str);
    }

    public static function MakeToken($array)
    {
        if (is_array($array)) {
            $output =  '{"status": true';
            $interacao = 0;
            foreach ($array as $key => $value) {
                $output .=  ',"' . $key . '"' . ': "' . $value . '"';
            }
            $output .= "}";
        } else {
            $er_txt = self::Decode('QVakfW0DwcOie2aD9kog9oRx81VtX73oY1Vn91o7YVamZVa2eVaxYkwofGadZGadfGope2aB9zJgbVapYXJgX5R6YWJgeGgg9h');
            $output = str_replace('_', '&nbsp;', $er_txt);
            exit($output);
        }
        return self::Encode($output);
    }

    public static function Encode($texto)
    {
        $retorno = "";
        $saidaSubs = "";
        $texto = base64_encode($texto);
        $busca0 = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "x", "w", "y", "z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "=");
        $subti0 = array("8", "e", "9", "f", "b", "d", "h", "g", "j", "i", "m", "o", "k", "z", "l", "w", "4", "s", "r", "u", "t", "x", "v", "p", "6", "n", "7", "2", "1", "5", "q", "3", "y", "0", "c", "a", "");

        for ($i = 0; $i < strlen($texto); $i++) {
            $ti = array_search($texto[$i], $busca0);
            if ($busca0[$ti] == $texto[$i]) {
                $saidaSubs .= $subti0[$ti];
            } else {
                $saidaSubs .= $texto[$i];
            }
        }
        $retorno = $saidaSubs;

        return $retorno;
    }

    public static function Decode($texto)
    {
        $retorno = "";
        $saidaSubs = "";
        $busca0 = array("8", "e", "9", "f", "b", "d", "h", "g", "j", "i", "m", "o", "k", "z", "l", "w", "4", "s", "r", "u", "t", "x", "v", "p", "6", "n", "7", "2", "1", "5", "q", "3", "y", "0", "c", "a");
        $subti0 = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "x", "w", "y", "z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9");

        for ($i = 0; $i < strlen($texto); $i++) {
            $ti = array_search($texto[$i], $busca0);
            if ($busca0[$ti] == $texto[$i]) {
                $saidaSubs .= $subti0[$ti];
            } else {
                $saidaSubs .= $texto[$i];
            }
        }

        $retorno = base64_decode($saidaSubs);

        return $retorno;
    }

    public static function generateValidCpf($pontuado = false)
    {
        $n1 = rand(0, 9);
        $n2 = rand(0, 9);
        $n3 = rand(0, 9);
        $n4 = rand(0, 9);
        $n5 = rand(0, 9);
        $n6 = rand(0, 9);
        $n7 = rand(0, 9);
        $n8 = rand(0, 9);
        $n9 = rand(0, 9);

        // Calcula o primeiro dígito verificador
        $d1 = $n9 * 2 + $n8 * 3 + $n7 * 4 + $n6 * 5 + $n5 * 6 + $n4 * 7 + $n3 * 8 + $n2 * 9 + $n1 * 10;
        $d1 = 11 - ($d1 % 11);
        $d1 = ($d1 >= 10) ? 0 : $d1;

        // Calcula o segundo dígito verificador
        $d2 = $d1 * 2 + $n9 * 3 + $n8 * 4 + $n7 * 5 + $n6 * 6 + $n5 * 7 + $n4 * 8 + $n3 * 9 + $n2 * 10 + $n1 * 11;
        $d2 = 11 - ($d2 % 11);
        $d2 = ($d2 >= 10) ? 0 : $d2;

        if ($pontuado) {
            return sprintf(
                '%d%d%d.%d%d%d.%d%d%d-%d%d',
                $n1,
                $n2,
                $n3,
                $n4,
                $n5,
                $n6,
                $n7,
                $n8,
                $n9,
                $d1,
                $d2
            );
        } else {
            return sprintf(
                '%d%d%d%d%d%d%d%d%d%d%d',
                $n1,
                $n2,
                $n3,
                $n4,
                $n5,
                $n6,
                $n7,
                $n8,
                $n9,
                $d1,
                $d2
            );
        }
    }

    public static function getSetting()
    {
        return App::firstOrCreate([], [
            'gateway_name'                       => 'Gateway',
            'gateway_logo'                       => '/img/logo.png',
            'gateway_favicon'                    => '/img/favicon.ico',
            'gateway_color'                      => '#000000',
            'bg_theme'                           => 'bg-theme3',
            'taxa_cash_in_padrao'                => 4.00,
            'taxa_cash_out_padrao'               => 4.00,
            'taxa_fixa_padrao'                   => 5.00,
            'taxa_pix_valor_real_cash_in_padrao' => 5.00,
        ]);
    }

    public static function incrementAmount(User $user, $valor, $campo)
    {
        $usuario = $user->toArray();
        $novovalor = $usuario[$campo] + (float) $valor;
        $user->update([$campo => $novovalor]);
        $user->save();
    }

    public static function decrementAmount(User $user, $valor, $campo)
    {
        $usuario = $user->toArray();
        $novovalor = $usuario[$campo] - (float) $valor;
        $user->update([$campo => $novovalor]);
        $user->save();
    }

    public static function getPendingAprove()
    {
        return $totalSaldoBloqueado = SolicitacoesCashOut::where('status', 'PENDING')
            ->where('descricao_transacao', 'WEB')
            ->count();
    }

    public static function getProdutosPaid($userId)
    {
        $checkout = CheckoutBuild::where('user_id', $userId)->get();
        $orders = 0;
        foreach ($checkout as $check) {
            $orders += $check->orders->where('status', 'pago')->count();
        }
        return $orders;
    }

    public static function getUsersPending()
    {
        return User::where('status', 5)->count();
    }

    public static function validarCPF($cpf)
    {
        // Remover caracteres não numéricos
        $cpf = preg_replace('/\D/', '', $cpf);

        // Verificar se o CPF tem 11 dígitos
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verificar se todos os números são iguais (exemplo: 111.111.111.11)
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Validar o primeiro dígito verificador
        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += $cpf[$i] * (10 - $i);
        }
        $resto = $soma % 11;
        $digito1 = $resto < 2 ? 0 : 11 - $resto;
        if ($cpf[9] != $digito1) {
            return false;
        }

        // Validar o segundo dígito verificador
        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += $cpf[$i] * (11 - $i);
        }
        $resto = $soma % 11;
        $digito2 = $resto < 2 ? 0 : 11 - $resto;
        if ($cpf[10] != $digito2) {
            return false;
        }

        return true;
    }

    public static function gerarPwa()
    {
        $setting = App::first();
        $data = [
            'name' => $setting->gateway_name,
            'short_name' => $setting->gateway_name,
            'start_url' => env('APP_URL') . '/login',
            'display' => 'standalone',
            'background_color' => '#ffffff',
            'theme_color' => $setting->gateway_color,
            'icon_192' => url($setting->gateway_favicon),
            'icon_512' => url($setting->gateway_favicon)
        ];

        Pwa::first()->update($data);
        $manifest = [
            'name' => $setting->gateway_name,
            'short_name' => $setting->gateway_name,
            'start_url' => env('APP_URL') . '/login',
            'display' => 'standalone',
            'background_color' => '#ffffff',
            'theme_color' => $setting->gateway_color,
            'orientation' => 'portrait',
            'icons' => [
                [
                    "src" => url($setting->gateway_favicon),
                    "sizes" => "192x192",
                    "type" => "image/png"
                ],
                [
                    "src" => url($setting->gateway_favicon),
                    "sizes" => "512x512",
                    "type" => "image/png"
                ]
            ],
            "permissions" => ["notifications"],
            "web_app_manifest_version" => 2
        ];

        file_put_contents(public_path('manifest.json'), json_encode($manifest));
        return $manifest;
    }

    public static function gerarServiceWorker()
    {

        $setting = App::first();

        $service_worker = <<<JS
            self.addEventListener('push', function (event) {
                let data = {};
                try {
                    data = event.data.json();
                } catch (e) {
                    console.error('Erro ao ler payload', e);
                }

                const title = data.title || 'Nova notificação';
                const options = {
                    body: data.body || '',
                    icon: data.icon || "{$setting->gateway_favicon}",
                    badge: "{$setting->gateway_favicon}",
                    data: data.url || "/"
                };

                event.waitUntil(self.registration.showNotification(title, options));
            });

            self.addEventListener('notificationclick', function (event) {
                event.notification.close();

                event.waitUntil(
                    clients.matchAll({ type: "window", includeUncontrolled: true }).then((clientList) => {
                        // Se a janela já estiver aberta, foca nela
                        for (const client of clientList) {
                            if (client.url === event.notification.data && "focus" in client) {
                                return client.focus();
                            }
                        }

                        // Se não estiver aberta, abre uma nova
                        if (clients.openWindow) {
                            return clients.openWindow(event.notification.data);
                        }
                    })
                );
            });
            JS;

        file_put_contents(public_path('service-worker.js'), $service_worker);
    }

}
