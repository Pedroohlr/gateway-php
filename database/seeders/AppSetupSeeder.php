<?php

namespace Database\Seeders;

use App\Models\App;
use App\Models\LandingPage;
use App\Models\Adquirente;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class AppSetupSeeder extends Seeder
{
    public function run(): void
    {
        // Gateway settings
        if (!App::exists()) {
            App::create([
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

        // Landing page
        if (!LandingPage::exists()) {
            DB::table('landingpage')->insert([
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // FCM (push notifications)
        if (DB::table('fcm')->count() === 0) {
            DB::table('fcm')->insert([
                'title'      => 'Venda realizada com sucesso!',
                'body'       => 'Você recebeu um pix no valor de {valor}',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // PWA
        if (DB::table('pwa')->count() === 0) {
            DB::table('pwa')->insert([
                'name'             => 'Gateway',
                'short_name'       => 'Gateway',
                'start_url'        => '/login',
                'display'          => 'standalone',
                'background_color' => '#ffffff',
                'theme_color'      => '#000000',
                'orientation'      => 'portrait',
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }

        // SMTP
        if (DB::table('smtp')->count() === 0) {
            DB::table('smtp')->insert([
                'host'       => null,
                'port'       => 465,
                'user'       => null,
                'pass'       => null,
                'color'      => '#00bd10',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Adquirentes (payment processors registry)
        $adquirentes = [
            ['adquirente' => 'primepag',    'status' => false, 'url' => 'https://api.primepag.com.br', 'referencia' => 'primepag'],
            ['adquirente' => 'zoompag',     'status' => false, 'url' => 'https://api.zoompag.com',     'referencia' => 'zoompag'],
            ['adquirente' => 'blupay',      'status' => false, 'url' => 'https://api.blupay.com.br',   'referencia' => 'blupay'],
            ['adquirente' => 'simpay',      'status' => false, 'url' => 'https://api.simpay.com.br',   'referencia' => 'simpay'],
            ['adquirente' => 'cartwave',    'status' => false, 'url' => 'https://api.cartwave.com.br', 'referencia' => 'cartwave'],
            ['adquirente' => 'mercadopago', 'status' => false, 'url' => 'https://api.mercadopago.com', 'referencia' => 'mercadopago'],
            ['adquirente' => 'witetec',     'status' => false, 'url' => 'https://api.witetec.com.br',  'referencia' => 'witetec'],
            ['adquirente' => 'cashtime',    'status' => false, 'url' => 'https://api.cashtime.com.br', 'referencia' => 'cashtime'],
        ];

        foreach ($adquirentes as $aq) {
            if (!Adquirente::where('adquirente', $aq['adquirente'])->exists()) {
                Adquirente::create($aq);
            }
        }

        // Adquirente config tables (one row each)
        $simpleTables = ['cashtimes', 'cartwave', 'ad_primepags', 'simpay', 'ad_witetec', 'ad_zoompags', 'mercadopago', 'blupay'];
        foreach ($simpleTables as $table) {
            try {
                if (DB::table($table)->count() === 0) {
                    DB::table($table)->insert([
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } catch (\Throwable $e) {
                // table may not exist or have required columns — skip
            }
        }
    }
}
