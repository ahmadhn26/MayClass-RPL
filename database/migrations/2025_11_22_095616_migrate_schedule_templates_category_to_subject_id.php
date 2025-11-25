<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Migrate existing category strings to structured subject_id references
     */
    public function up(): void
    {
        // Define mapping from category + level to subject_id
        $categoryMapping = [
            'SD' => [
                'Matematika' => 1, // Matematika (SD)
                'IPA' => 3,        // IPA (SD)
            ],
            'SMP' => [
                'IPA' => 8,        // IPA (SMP)
            ],
            'SMA' => [
                'UTBK' => 20,      // UTBK (SMA)
            ],
        ];

        // Get all templates with their package level
        $templates = DB::table('schedule_templates')
            ->join('packages', 'schedule_templates.package_id', '=', 'packages.id')
            ->select('schedule_templates.id', 'schedule_templates.category', 'packages.level')
            ->get();

        foreach ($templates as $template) {
            $level = $template->level;
            $category = $template->category;

            if (isset($categoryMapping[$level][$category])) {
                $subjectId = $categoryMapping[$level][$category];

                DB::table('schedule_templates')
                    ->where('id', $template->id)
                    ->update(['subject_id' => $subjectId]);
            }
        }
    }

    /**
     * Reverse the migrations.
     * Clear subject_id (set to null) - category field remains unchanged
     */
    public function down(): void
    {
        DB::table('schedule_templates')
            ->update(['subject_id' => null]);
    }
};
