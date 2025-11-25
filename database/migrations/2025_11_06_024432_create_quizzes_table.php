<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('subject');
            $table->string('class_level')->nullable();
            $table->string('title');
            $table->text('summary');
            $table->string('link_url')->nullable();
            $table->string('thumbnail_url');
            $table->string('duration_label');
            $table->unsignedInteger('question_count');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
