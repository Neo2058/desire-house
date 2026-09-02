<?php

use App\Models\Page;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('model_id');
            $table->string('model_type');
            $table->longText('description')->nullable();
            $table->string('title')->nullable();
            $table->string('image')->nullable();
            $table->string('author')->nullable();
            $table->string('robots')->nullable();
            $table->string('canonical_url')->nullable();
            $table->timestamps();

            $table->unique(['model_type', 'model_id']);
        });

        foreach ([Page::class, Service::class, Project::class] as $class) {
            $class::query()->each(function ($model): void {
                $model->seo()->firstOrCreate([]);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('seo');
    }
};
