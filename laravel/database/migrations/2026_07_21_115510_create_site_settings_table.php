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
        Schema::create('site_settings', function (Blueprint $table) {

            $table->id();

            $table->string('company_name')->nullable();

            $table->string('phone')->nullable();

            $table->string('email')->nullable();

            $table->string('telegram')->nullable();

            $table->string('whatsapp')->nullable();

            $table->string('address')->nullable();

            $table->unsignedBigInteger('logo_image_id')->nullable();

            $table->unsignedBigInteger('favicon_image_id')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
