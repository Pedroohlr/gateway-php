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
        Schema::create('pwa', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Minha Aplicação');
            $table->string('short_name')->default('LaravelApp');
            $table->string('start_url')->default('/');
            $table->string('display')->default('standalone');
            $table->string('background_color')->default('#ffffff');
            $table->string('theme_color')->default('#0d6efd');
            $table->string('orientation')->default('portrait');
            $table->string('icon_192')->nullable();
            $table->string('icon_512')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pwa');
    }
};
