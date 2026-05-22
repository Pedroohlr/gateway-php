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
        Schema::create('ad_simpay', function (Blueprint $table) {
            $table->id();
            $table->string('x_api_key')->nullable()->default(NULL);
            $table->string('url')->nullable()->default("https://api.somossympay.com.br/api/");
            $table->string('url_cash_in')->nullable()->default("https://api.somossympay.com.br/api/checkout/external/sales");
            $table->string('url_cash_out')->nullable()->default("https://api.somossympay.com.br/api/admin/external/account/withdrawal");
            $table->decimal('taxa_pix_cash_in', 10,2)->nullable()->default(0.00);
            $table->decimal('taxa_pix_cash_out', 10,2)->nullable()->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_simpay');
    }
};
