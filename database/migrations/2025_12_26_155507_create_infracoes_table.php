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
        Schema::create('infracoes', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2)->nullable()->default(NULL);
            $table->enum('status', ['OPEN', 'UNDER_REVIEW', 'REJECTED', 'RESOLVED'])->default('OPEN');
            $table->string('reason')->nullable()->default(NULL);
            $table->string('appealReason')->nullable()->default(NULL);
            $table->string('idTransaction')->nullable()->default(NULL);
            $table->string("createdBy")->nullable()->default(NULL);
            $table->timestamp("createdAt")->nullable()->default(NULL);
            $table->timestamp("resolvedAt")->nullable()->default(NULL);
            $table->string("resolvedBy")->nullable()->default(NULL);

            $table->unsignedBigInteger('transaction_id');
            $table->foreign('transaction_id')->references('id')->on('solicitacoes')->onDelete('cascade');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infracoes');
    }
};
