<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('packages')) {
            return;
        }

        if (! Schema::hasColumn('packages', 'grade_range')) {
            Schema::table('packages', function (Blueprint $table) {
                $table->string('grade_range')->nullable()->after('level');
            });
        }

        $packages = DB::table('packages')->select('id', 'level', 'grade_range')->get();

        foreach ($packages as $package) {
            $stage = $this->guessStage($package->level ?? '');
            $grade = $this->guessGradeRange($package->level ?? '', $package->grade_range ?? '');

            DB::table('packages')
                ->where('id', $package->id)
                ->update([
                    'level' => $stage,
                    'grade_range' => $grade,
                ]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('packages')) {
            return;
        }

        if (Schema::hasColumn('packages', 'grade_range')) {
            $packages = DB::table('packages')->select('id', 'level', 'grade_range')->get();

            foreach ($packages as $package) {
                $combined = trim(implode(' ', array_filter([
                    $package->level,
                    $package->grade_range,
                ])));

                DB::table('packages')
                    ->where('id', $package->id)
                    ->update([
                        'level' => $combined,
                    ]);
            }

            Schema::table('packages', function (Blueprint $table) {
                $table->dropColumn('grade_range');
            });
        }
    }

    private function guessStage(string $level): string
    {
        $normalized = strtoupper($level);

        if (str_contains($normalized, 'SMA') || str_contains($normalized, 'UTBK') || str_contains($normalized, 'SMK')) {
            return 'SMA';
        }

        if (str_contains($normalized, 'SMP')) {
            return 'SMP';
        }

        if (str_contains($normalized, 'SD')) {
            return 'SD';
        }

        return $level !== '' ? $level : 'SD';
    }

    private function guessGradeRange(string $level, string $existingGrade): ?string
    {
        if ($existingGrade !== '') {
            return $existingGrade;
        }

        $normalized = strtoupper($level);

        if (preg_match('/(SD|SMP|SMA)\s*(KELAS)?\s*([0-9]{1,2})([-–—]|\s*SAMPAI\s*)?([0-9]{1,2})?/u', $normalized, $matches)) {
            $start = $matches[3] ?? null;
            $end = $matches[5] ?? null;

            if ($start && $end) {
                return 'Kelas ' . $start . ' - ' . $end;
            }

            if ($start) {
                return 'Kelas ' . $start;
            }
        }

        if (str_contains($normalized, 'IPA')) {
            return 'Fokus IPA & Saintek';
        }

        if (str_contains($normalized, 'IPS')) {
            return 'Fokus IPS & Soshum';
        }

        if (str_contains($normalized, 'UTBK')) {
            return 'Persiapan UTBK';
        }

        return $level !== '' ? $level : null;
    }
};
