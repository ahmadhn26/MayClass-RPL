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
        Schema::table('quizzes', function (Blueprint $table) {
            // Change link_url to JSON to store multiple links
            // We drop and recreate for simplicity in this dev phase
            $table->dropColumn('link_url');
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->json('link_url')->nullable()->after('summary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('link_url');
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->string('link_url')->nullable()->after('summary');
        });
    }
};
