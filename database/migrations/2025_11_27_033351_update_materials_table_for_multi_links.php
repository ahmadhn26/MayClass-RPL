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
        Schema::table('materials', function (Blueprint $table) {
            // Change resource_path to JSON to store multiple links
            // Note: In SQLite/MySQL, we might need to drop and recreate or use raw statement if changing type directly is tricky with data.
            // For safety in dev, we will drop the column and recreate it as JSON since we are "refining" and data loss is acceptable per user context.
            $table->dropColumn('resource_path');
        });

        Schema::table('materials', function (Blueprint $table) {
            $table->json('resource_path')->nullable()->after('thumbnail_url');
            $table->json('quiz_urls')->nullable()->after('resource_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn('quiz_urls');
            $table->dropColumn('resource_path');
        });

        Schema::table('materials', function (Blueprint $table) {
            $table->string('resource_path')->nullable()->after('thumbnail_url');
        });
    }
};
