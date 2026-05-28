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
            $table->longText('auth_title')->nullable();
            $table->longText('auth_message')->nullable();
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
