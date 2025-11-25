<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\ScheduleSession;
use App\Models\ScheduleTemplate;
use App\Models\User;
use App\Support\ScheduleTemplateGenerator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('schedule_sessions') || ! Schema::hasTable('schedule_templates')) {
            return;
        }

        if (! Schema::hasTable('users')) {
            return;
        }

        $packages = Schema::hasTable('packages')
            ? Package::query()->get()->keyBy('slug')
            : collect();

        $tutor = User::query()->where('role', 'tutor')->first();

        if (! $tutor) {
            return;
        }

        if (! Schema::hasTable('users')) {
            return;
        }

        $packages = Schema::hasTable('packages')
            ? Package::query()->get()->keyBy('slug')
            : collect();

        $tutor = User::query()->where('role', 'tutor')->first();

        if (! $tutor) {
            return;
        }

        if (! Schema::hasTable('users')) {
            return;
        }

        $packages = Schema::hasTable('packages')
            ? Package::query()->get()->keyBy('slug')
            : collect();

        $tutor = User::query()->where('role', 'tutor')->first();

        if (! $tutor) {
            return;
        }

        if (! Schema::hasTable('users')) {
            return;
        }

        $packages = Schema::hasTable('packages')
            ? Package::query()->get()->keyBy('slug')
            : collect();

        $tutor = User::query()->where('role', 'tutor')->first();

        if (! $tutor) {
            return;
        }

        if (! Schema::hasTable('users')) {
            return;
        }

        $packages = Schema::hasTable('packages')
            ? Package::query()->get()->keyBy('slug')
            : collect();

        $tutor = User::query()->where('role', 'tutor')->first();

        if (! $tutor) {
            return;
        }

        if (! Schema::hasTable('users')) {
            return;
        }

        $packages = Schema::hasTable('packages')
            ? Package::query()->get()->keyBy('slug')
            : collect();

        $tutor = User::query()->where('role', 'tutor')->first();

        if (! $tutor) {
            return;
        }

        if (! Schema::hasTable('users')) {
            return;
        }

        $packages = Schema::hasTable('packages')
            ? Package::query()->get()->keyBy('slug')
            : collect();

        $tutor = User::query()->where('role', 'tutor')->first();

        if (! $tutor) {
            return;
        }

        if (! Schema::hasTable('users')) {
            return;
        }

        $packages = Schema::hasTable('packages')
            ? Package::query()->get()->keyBy('slug')
            : collect();

        $tutor = User::query()->where('role', 'tutor')->first();

        if (! $tutor) {
            return;
        }

        if (! Schema::hasTable('users')) {
            return;
        }

        $packages = Schema::hasTable('packages')
            ? Package::query()->get()->keyBy('slug')
            : collect();

        $tutor = User::query()->where('role', 'tutor')->first();

        if (! $tutor) {
            return;
        }

        $packages = Schema::hasTable('packages')
            ? Package::query()->get()->keyBy('slug')
            : collect();

        ScheduleSession::query()->delete();
        ScheduleTemplate::query()->where('user_id', $tutor->id)->delete();

        $templates = [
            [
                'package_slug' => 'mayclass-sd-fundamental',
                'title' => 'Kelas Numerasi Dasar',
                'category' => 'Matematika',
                'class_level' => 'SD',
                'location' => 'Zoom Meeting',
                'day_of_week' => 2,
                'start_time' => '16:00',
                'duration_minutes' => 90,
                'student_count' => 18,
            ],
            [
                'package_slug' => 'mayclass-sd-unggul',
                'title' => 'Lab Sains Terapan',
                'category' => 'IPA',
                'class_level' => 'SD',
                'location' => 'Studio MayClass Bandung',
                'day_of_week' => 3,
                'start_time' => '15:30',
                'duration_minutes' => 90,
                'student_count' => 20,
            ],
            [
                'package_slug' => 'mayclass-smp-eksplor',
                'title' => 'Klinik IPA: Sistem Tata Surya',
                'category' => 'IPA',
                'class_level' => 'SMP',
                'location' => 'Google Meet',
                'day_of_week' => 4,
                'start_time' => '18:30',
                'duration_minutes' => 90,
                'student_count' => 22,
            ],
            [
                'package_slug' => 'mayclass-sma-premium',
                'title' => 'Bedah Soal UTBK Saintek',
                'category' => 'UTBK',
                'class_level' => 'SMA',
                'location' => 'MayClass Learning Hub',
                'day_of_week' => 5,
                'start_time' => '19:00',
                'duration_minutes' => 120,
                'student_count' => 15,
            ],
        ];

        foreach ($templates as $templateData) {
            $package = $packages->get($templateData['package_slug']);

            if (! $package) {
                continue;
            }

            $template = ScheduleTemplate::create([
                'user_id' => $tutor->id,
                'package_id' => $package->id,
                'title' => $templateData['title'],
                'category' => $templateData['category'],
                'class_level' => $templateData['class_level'],
                'location' => $templateData['location'],
                'day_of_week' => $templateData['day_of_week'],
                'start_time' => $templateData['start_time'],
                'duration_minutes' => $templateData['duration_minutes'],
                'student_count' => $templateData['student_count'],
                'is_active' => true,
            ]);

            ScheduleTemplateGenerator::refreshTemplate($template);
        }

        $firstSession = ScheduleSession::query()
            ->where('user_id', $tutor->id)
            ->orderBy('start_at')
            ->first();

        if ($firstSession) {
            $firstSession->update(['is_highlight' => true]);
        }
    }
}
