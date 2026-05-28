<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('app', function (Blueprint $table) {
            if (!Schema::hasColumn('app', 'gateway_name'))        $table->string('gateway_name')->nullable();
            if (!Schema::hasColumn('app', 'gateway_logo'))        $table->string('gateway_logo')->nullable();
            if (!Schema::hasColumn('app', 'gateway_favicon'))     $table->string('gateway_favicon')->nullable();
            if (!Schema::hasColumn('app', 'gateway_banner_home')) $table->string('gateway_banner_home')->nullable();
            if (!Schema::hasColumn('app', 'gateway_color'))       $table->string('gateway_color')->nullable()->default('#000000');
            if (!Schema::hasColumn('app', 'baseline'))            $table->string('baseline')->nullable();
            if (!Schema::hasColumn('app', 'taxa_fixa_padrao_cash_out')) $table->decimal('taxa_fixa_padrao_cash_out', 10, 2)->default(0);
            if (!Schema::hasColumn('app', 'limite_saque_mensal')) $table->decimal('limite_saque_mensal', 10, 2)->nullable();
            if (!Schema::hasColumn('app', 'deposito_minimo'))     $table->decimal('deposito_minimo', 10, 2)->nullable();
            if (!Schema::hasColumn('app', 'saque_minimo'))        $table->decimal('saque_minimo', 10, 2)->nullable();
            if (!Schema::hasColumn('app', 'contato'))             $table->string('contato')->nullable();
            if (!Schema::hasColumn('app', 'cnpj'))                $table->string('cnpj')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('app', function (Blueprint $table) {
            $table->dropColumn([
                'gateway_name', 'gateway_logo', 'gateway_favicon', 'gateway_banner_home',
                'gateway_color', 'baseline', 'taxa_fixa_padrao_cash_out',
                'limite_saque_mensal', 'deposito_minimo', 'saque_minimo', 'contato', 'cnpj',
            ]);
        });
    }
};
