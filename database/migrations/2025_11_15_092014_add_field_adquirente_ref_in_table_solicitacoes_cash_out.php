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
            $table->string('adquirente_ref')->nullable()->default('cashtime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitacoes_cash_out', function (Blueprint $table) {
            $table->dropColumn('adquirente_ref');
        });
    }
};
