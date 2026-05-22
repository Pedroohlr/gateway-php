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
        Schema::create('fcm', function (Blueprint $table) {
            $table->id();
            $table->string("apiKey")->nullable()->default(NULL); 
            $table->string("authDomain")->nullable()->default(NULL); 
            $table->string("projectId")->nullable()->default(NULL); 
            $table->string("storageBucket")->nullable()->default(NULL); 
            $table->string("messagingSenderId")->nullable()->default(NULL); 
            $table->string("appId")->nullable()->default(NULL); 
            $table->string("measurementId")->nullable()->default(NULL); 
             $table->string('firebase_config')->nullable()->default(NULL);
            $table->string('title')->default('Venda realizada com sucesso!');
            $table->string('body')->default('Você recebeu um pix no valor de {valor}');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fcm');
    }
};
