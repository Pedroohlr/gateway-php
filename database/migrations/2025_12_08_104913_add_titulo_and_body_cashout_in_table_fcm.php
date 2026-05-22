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
        Schema::table('fcm', function (Blueprint $table) {
            $table->string('title_cashout')->default('Saque realizado com sucesso!')->after('body');
            $table->string('body_cashout')->default('Você realizou um saque no valor de {valor}')->after('title_cashout');
            $table->dropColumn("authDomain");
            $table->dropColumn("projectId");
            $table->dropColumn("storageBucket");
            $table->dropColumn("messagingSenderId");
            $table->dropColumn("appId");
            $table->dropColumn("measurementId");
            $table->dropColumn('firebase_config');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fcm', function (Blueprint $table) {
            //
        });
    }
};
