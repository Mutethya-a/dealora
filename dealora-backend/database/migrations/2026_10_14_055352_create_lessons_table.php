<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_id')
                ->constrained('courses')
                ->cascadeOnDelete();

            $table->string('title');

            $table->string('slug');

            $table->longText('content')->nullable();

            $table->string('video_url')->nullable();

            $table->unsignedInteger('duration_minutes')
                ->default(0);

            $table->unsignedInteger('position');

            $table->boolean('is_preview')->default(false);

            $table->boolean('is_published')->default(false);

            $table->timestamps();

            $table->unique(['course_id', 'slug']);
            $table->unique(['course_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
