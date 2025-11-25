<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add subject_id column to materials (only if it doesn't exist)
        Schema::table('materials', function (Blueprint $table) {
            if (!Schema::hasColumn('materials', 'subject_id')) {
                $table->foreignId('subject_id')->nullable()->after('package_id')->constrained()->nullOnDelete();
            }
        });

        // Add subject_id column to quizzes (only if it doesn't exist)
        Schema::table('quizzes', function (Blueprint $table) {
            if (!Schema::hasColumn('quizzes', 'subject_id')) {
                $table->foreignId('subject_id')->nullable()->after('package_id')->constrained()->nullOnDelete();
            }
        });

        // Migrate existing data (only if subject column exists)
        if (Schema::hasColumn('materials', 'subject') && Schema::hasColumn('quizzes', 'subject')) {
            $subjects = DB::table('subjects')->get();
            
            foreach ($subjects as $subject) {
                // Update materials - match by subject name
                DB::table('materials')
                    ->where('subject', 'LIKE', $subject->name)
                    ->update(['subject_id' => $subject->id]);

                // Update quizzes - match by subject name
                DB::table('quizzes')
                    ->where('subject', 'LIKE', $subject->name)
                    ->update(['subject_id' => $subject->id]);
            }
        }

        // Drop old subject column (only if it exists)
        Schema::table('materials', function (Blueprint $table) {
            if (Schema::hasColumn('materials', 'subject')) {
                $table->dropColumn('subject');
            }
        });

        Schema::table('quizzes', function (Blueprint $table) {
            if (Schema::hasColumn('quizzes', 'subject')) {
                $table->dropColumn('subject');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back subject column
        Schema::table('materials', function (Blueprint $table) {
            $table->string('subject')->nullable()->after('package_id');
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->string('subject')->nullable()->after('package_id');
        });

        // Migrate data back (best effort)
        $materials = DB::table('materials')->whereNotNull('subject_id')->get();
        foreach ($materials as $material) {
            $subject = DB::table('subjects')->find($material->subject_id);
            if ($subject) {
                DB::table('materials')->where('id', $material->id)->update(['subject' => $subject->name]);
            }
        }

        $quizzes = DB::table('quizzes')->whereNotNull('subject_id')->get();
        foreach ($quizzes as $quiz) {
            $subject = DB::table('subjects')->find($quiz->subject_id);
            if ($subject) {
                DB::table('quizzes')->where('id', $quiz->id)->update(['subject' => $subject->name]);
            }
        }

        // Drop subject_id columns
        Schema::table('materials', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->dropColumn('subject_id');
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->dropColumn('subject_id');
        });
    }
};
