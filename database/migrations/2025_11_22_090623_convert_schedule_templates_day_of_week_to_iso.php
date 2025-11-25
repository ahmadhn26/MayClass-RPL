<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Convert day_of_week from Carbon format (0=Sunday) to ISO format (1=Monday)
     */
    public function up(): void
    {
        DB::table('schedule_templates')
            ->where('day_of_week', 0)
            ->update(['day_of_week' => 7]);
    }

    /**
     * Reverse the migrations.
     * Convert back from ISO to Carbon format
     */
    public function down(): void
    {
        DB::table('schedule_templates')
            ->where('day_of_week', 7)
            ->update(['day_of_week' => 0]);
    }
};
