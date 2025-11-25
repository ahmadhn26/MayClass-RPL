<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            return;
        }

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username', 50)->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['visitor', 'student', 'tutor', 'admin'])->default('visitor');
            $table->string('student_id', 20)->nullable()->unique();
            $table->string('phone', 30)->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('parent_name')->nullable();
            $table->string('address')->nullable();
            $table->string('avatar_path')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
