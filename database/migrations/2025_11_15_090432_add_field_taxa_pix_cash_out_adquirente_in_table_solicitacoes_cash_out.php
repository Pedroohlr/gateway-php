<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('solicitacoes_cash_out', function (Blueprint $table) {
            $table->decimal('taxa_pix_cash_out_adquirente',10,2)->default(0.00)->after('taxa_cash_out');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitacoes_cash_out', function (Blueprint $table) {
            $table->dropColumn('taxa_pix_cash_out_adquirente');
        });
    }
};
