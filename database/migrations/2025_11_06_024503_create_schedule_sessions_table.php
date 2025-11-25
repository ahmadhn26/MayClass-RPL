<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('package_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('category');
            $table->string('class_level')->nullable();
            $table->string('location')->nullable();
            $table->unsignedSmallInteger('student_count')->nullable();
            $table->string('mentor_name');
            $table->dateTime('start_at');
            $table->boolean('is_highlight')->default(false);
            $table->timestamps();

            $table->index(['start_at', 'is_highlight']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_sessions');
    }
};
