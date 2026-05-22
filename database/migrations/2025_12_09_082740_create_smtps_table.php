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
        Schema::create('smtp', function (Blueprint $table) {
            $table->id();
            $table->string('host')->nullable();
            $table->integer('port')->nullable()->default(465);
            $table->string('user')->nullable();
            $table->string('pass')->nullable();
            $table->string('color')->nullable()->default('#00bd10');
            $table->string('image')->nullable();
            $table->longText('auth_title')->nullable()->default('<h1 style="text-align:center;">
            Autenticação em Duas Etapas
        </h1>');

            $table->longText('auth_message')->nullable()->default('<div>
            <h3 style="color:gray;">
                Olá Guilherme vieira,
            </h3>

            <h3 style="color:gray;">
                Use o código abaixo para concluir seu login na PagVIVA.
                Ele é válido por <span style="color:black">15 minutos</span>.
            </h3>
        </div>');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smtp');
    }
};
