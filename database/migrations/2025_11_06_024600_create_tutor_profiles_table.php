<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tutor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('headline')->nullable();
            $table->text('bio')->nullable();
            $table->string('specializations')->nullable();
            $table->string('education')->nullable();
            $table->unsignedTinyInteger('experience_years')->default(0);
            $table->unsignedInteger('students_taught')->default(0);
            $table->unsignedInteger('hours_taught')->default(0);
            $table->decimal('rating', 3, 2)->nullable();
            $table->json('certifications')->nullable();
            $table->string('avatar_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutor_profiles');
    }
};
