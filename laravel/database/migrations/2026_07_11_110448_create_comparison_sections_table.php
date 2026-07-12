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
        Schema::create('comparison_sections', function (Blueprint $table) {

            $table->id();

            $table->string('name');

            $table->string('title');

            $table->string('left_title');

            $table->string('right_title');

            $table->json('left_items');

            $table->json('right_items');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comparison_sections');
    }
};
