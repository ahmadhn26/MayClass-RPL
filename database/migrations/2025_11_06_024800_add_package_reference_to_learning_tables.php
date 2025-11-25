<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('packages')) {
            if (Schema::hasTable('materials') && ! Schema::hasColumn('materials', 'package_id')) {
                Schema::table('materials', function (Blueprint $table) {
                    $table->foreignId('package_id')->nullable()->after('id')->constrained()->nullOnDelete();
                    $table->index('package_id');
                });
            }

            if (Schema::hasTable('quizzes') && ! Schema::hasColumn('quizzes', 'package_id')) {
                Schema::table('quizzes', function (Blueprint $table) {
                    $table->foreignId('package_id')->nullable()->after('id')->constrained()->nullOnDelete();
                    $table->index('package_id');
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('materials') && Schema::hasColumn('materials', 'package_id')) {
            Schema::table('materials', function (Blueprint $table) {
                $table->dropConstrainedForeignId('package_id');
            });
        }

        if (Schema::hasTable('quizzes') && Schema::hasColumn('quizzes', 'package_id')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->dropConstrainedForeignId('package_id');
            });
        }
    }
};
