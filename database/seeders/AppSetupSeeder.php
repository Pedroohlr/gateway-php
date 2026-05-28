<?php

namespace Database\Seeders;

use App\Models\App;
use App\Models\LandingPage;
use Illuminate\Database\Seeder;

class AppSetupSeeder extends Seeder
{
    public function run(): void
    {
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

        if (!LandingPage::exists()) {
            \Illuminate\Support\Facades\DB::table('landingpage')->insert([
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
