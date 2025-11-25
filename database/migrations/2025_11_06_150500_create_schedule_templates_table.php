<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('category')->nullable();
            $table->string('class_level')->nullable();
            $table->string('location')->nullable();
            $table->unsignedTinyInteger('day_of_week');
            $table->time('start_time');
            $table->unsignedSmallInteger('duration_minutes')->default(90);
            $table->unsignedSmallInteger('student_count')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'day_of_week']);
        });

        Schema::table('schedule_sessions', function (Blueprint $table) {
            if (! Schema::hasColumn('schedule_sessions', 'schedule_template_id')) {
                $table->foreignId('schedule_template_id')
                    ->nullable()
                    ->after('package_id')
                    ->constrained('schedule_templates')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('schedule_sessions', 'duration_minutes')) {
                $table->unsignedSmallInteger('duration_minutes')
                    ->default(90)
                    ->after('start_at');
            }

            if (! Schema::hasColumn('schedule_sessions', 'status')) {
                $table->string('status')
                    ->default('scheduled')
                    ->after('is_highlight');
            }

            if (! Schema::hasColumn('schedule_sessions', 'cancelled_at')) {
                $table->timestamp('cancelled_at')
                    ->nullable()
                    ->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('schedule_sessions', function (Blueprint $table) {
            if (Schema::hasColumn('schedule_sessions', 'cancelled_at')) {
                $table->dropColumn('cancelled_at');
            }

            if (Schema::hasColumn('schedule_sessions', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('schedule_sessions', 'duration_minutes')) {
                $table->dropColumn('duration_minutes');
            }

            if (Schema::hasColumn('schedule_sessions', 'schedule_template_id')) {
                $table->dropConstrainedForeignId('schedule_template_id');
            }
        });

        Schema::dropIfExists('schedule_templates');
    }
};
