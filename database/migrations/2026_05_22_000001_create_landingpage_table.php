<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('landingpage', function (Blueprint $table) {
            $table->id();
            $table->string('section1_title')->nullable();
            $table->text('section1_description')->nullable();
            $table->string('section1_image')->nullable();
            $table->string('section1_link')->nullable();
            $table->string('section2_title')->nullable();
            $table->text('section2_description')->nullable();
            $table->string('section2_image1')->nullable();
            $table->string('section2_image2')->nullable();
            $table->string('section2_image3')->nullable();
            $table->string('section3_title')->nullable();
            $table->string('section3_item1_image')->nullable();
            $table->string('section3_item1_title')->nullable();
            $table->text('section3_item1_description')->nullable();
            $table->string('section3_item2_image')->nullable();
            $table->string('section3_item2_title')->nullable();
            $table->text('section3_item2_description')->nullable();
            $table->string('section3_item3_image')->nullable();
            $table->string('section3_item3_title')->nullable();
            $table->text('section3_item3_description')->nullable();
            $table->string('section4_title')->nullable();
            $table->string('section4_image')->nullable();
            $table->text('section4_description')->nullable();
            $table->string('section4_link')->nullable();
            $table->string('section5_title')->nullable();
            $table->text('section5_description')->nullable();
            $table->string('section5_image')->nullable();
            $table->string('section6_title')->nullable();
            $table->text('section6_description')->nullable();
            $table->string('section6_image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landingpage');
    }
};
