<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('zoompag', function (Blueprint $table) {
            $table->id();
            $table->string('url')->nullable()->default('https://api.zoompag.com');
            $table->string('api_token')->nullable()->default(NULL);
            $table->decimal('taxa_pix_cash_in', 10,2)->nullable()->default(5.0);
            $table->decimal('taxa_pix_cash_out', 10,2)->nullable()->default(5.0);
            $table->decimal('tx_billet_fixed', 10,2)->nullable()->default(5.0);
            $table->decimal('tx_billet_percent', 10,2)->nullable()->default(5.0);
            $table->decimal('tx_card_fixed', 10,2)->nullable()->default(5.0);
            $table->decimal('tx_card_percent', 10,2)->nullable()->default(5.0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zoompag');
    }
};
