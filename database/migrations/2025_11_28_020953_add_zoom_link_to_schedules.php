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
        // Add zoom_link column to schedule_templates table
        if (Schema::hasTable('schedule_templates') && !Schema::hasColumn('schedule_templates', 'zoom_link')) {
            Schema::table('schedule_templates', function (Blueprint $table) {
                $table->string('zoom_link', 500)->nullable()->after('location');
            });
        }

        // Add zoom_link column to schedule_sessions table
        if (Schema::hasTable('schedule_sessions') && !Schema::hasColumn('schedule_sessions', 'zoom_link')) {
            Schema::table('schedule_sessions', function (Blueprint $table) {
                $table->string('zoom_link', 500)->nullable()->after('location');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('schedule_templates', 'zoom_link')) {
            Schema::table('schedule_templates', function (Blueprint $table) {
                $table->dropColumn('zoom_link');
            });
        }

        if (Schema::hasColumn('schedule_sessions', 'zoom_link')) {
            Schema::table('schedule_sessions', function (Blueprint $table) {
                $table->dropColumn('zoom_link');
            });
        }
    }
};
