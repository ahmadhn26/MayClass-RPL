<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            if (! Schema::hasColumn('materials', 'resource_path')) {
                $table->string('resource_path')->nullable()->after('thumbnail_url');
            }
        });

        Schema::table('quizzes', function (Blueprint $table) {
            if (! Schema::hasColumn('quizzes', 'class_level')) {
                $table->string('class_level')->nullable()->after('subject');
            }

            if (! Schema::hasColumn('quizzes', 'link_url')) {
                $table->string('link_url')->nullable()->after('summary');
            }
        });

        Schema::table('schedule_sessions', function (Blueprint $table) {
            if (! Schema::hasColumn('schedule_sessions', 'class_level')) {
                $table->string('class_level')->nullable()->after('category');
            }

            if (! Schema::hasColumn('schedule_sessions', 'location')) {
                $table->string('location')->nullable()->after('class_level');
            }

            if (! Schema::hasColumn('schedule_sessions', 'student_count')) {
                $table->unsignedSmallInteger('student_count')->nullable()->after('location');
            }
        });

        Schema::table('tutor_profiles', function (Blueprint $table) {
            if (! Schema::hasColumn('tutor_profiles', 'education')) {
                $table->string('education')->nullable()->after('specializations');
            }
        });
    }

    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            if (Schema::hasColumn('materials', 'resource_path')) {
                $table->dropColumn('resource_path');
            }
        });

        Schema::table('quizzes', function (Blueprint $table) {
            if (Schema::hasColumn('quizzes', 'class_level')) {
                $table->dropColumn('class_level');
            }

            if (Schema::hasColumn('quizzes', 'link_url')) {
                $table->dropColumn('link_url');
            }
        });

        Schema::table('schedule_sessions', function (Blueprint $table) {
            foreach (['student_count', 'location', 'class_level'] as $column) {
                if (Schema::hasColumn('schedule_sessions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('tutor_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('tutor_profiles', 'education')) {
                $table->dropColumn('education');
            }
        });
    }
};
