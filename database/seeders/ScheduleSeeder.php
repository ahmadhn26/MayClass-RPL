<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\ScheduleSession;
use App\Models\ScheduleTemplate;
use App\Models\Subject;
use App\Models\User;
use App\Support\ScheduleTemplateGenerator;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // Check if required tables exist
        if (!Schema::hasTable('schedule_sessions') || !Schema::hasTable('schedule_templates')) {
            $this->command->warn('⚠️  Schedule tables do not exist.');
            return;
        }

        if (!Schema::hasTable('users')) {
            $this->command->warn('⚠️  Users table does not exist.');
            return;
        }

        // Get packages
        $packages = Schema::hasTable('packages')
            ? Package::query()->get()->keyBy('slug')
            : collect();

        // Get all tutors
        $tutors = User::query()->where('role', 'tutor')->get();

        if ($tutors->isEmpty()) {
            $this->command->warn('⚠️  No tutors found. Please run seeder for tutors first.');
            return;
        }

        // Get all students for student-specific sessions
        $students = User::query()->where('role', 'student')->get();

        // Get subjects if available
        $subjects = Schema::hasTable('subjects') ? Subject::all() : collect();

        $this->command->info('🗑️  Clearing existing schedule data...');
        ScheduleSession::query()->delete();
        ScheduleTemplate::query()->delete();

        $this->command->info('🗓️  Creating schedule templates...');

        // Define templates for different tutors (mix of online and offline)
        $templateDefinitions = [
            // ONLINE CLASSES
            [
                'package_slug' => 'sd-basic-level-classA',
                'title' => 'Kelas Numerasi Dasar SD',
                'category' => 'Matematika',
                'class_level' => 'SD',
                'location' => 'Zoom Meeting',
                'zoom_link' => 'https://zoom.us/j/123456789',
                'day_of_week' => 1, // Monday
                'start_time' => '14:00',
                'duration_minutes' => 90,
                'student_count' => 10,
            ],
            [
                'package_slug' => 'sd-basic-level-classA',
                'title' => 'Bahasa Indonesia SD',
                'category' => 'Bahasa Indonesia',
                'class_level' => 'SD',
                'location' => 'Google Meet',
                'zoom_link' => 'https://meet.google.com/abc-defg-hij',
                'day_of_week' => 3, // Wednesday
                'start_time' => '15:30',
                'duration_minutes' => 90,
                'student_count' => 12,
            ],
            [
                'package_slug' => 'smp-intermediate-classB',
                'title' => 'Klinik IPA: Sistem Tata Surya',
                'category' => 'IPA',
                'class_level' => 'SMP',
                'location' => 'Zoom Meeting',
                'zoom_link' => 'https://zoom.us/j/987654321',
                'day_of_week' => 2, // Tuesday
                'start_time' => '16:00',
                'duration_minutes' => 90,
                'student_count' => 15,
            ],
            [
                'package_slug' => 'smp-intermediate-classB',
                'title' => 'Matematika SMP Lanjutan',
                'category' => 'Matematika',
                'class_level' => 'SMP',
                'location' => 'Google Meet',
                'zoom_link' => 'https://meet.google.com/xyz-math-smp',
                'day_of_week' => 4, // Thursday
                'start_time' => '18:30',
                'duration_minutes' => 90,
                'student_count' => 14,
            ],
            [
                'package_slug' => 'sma-advanced-classC',
                'title' => 'Bedah Soal UTBK Saintek',
                'category' => 'UTBK',
                'class_level' => 'SMA',
                'location' => 'Zoom Meeting',
                'zoom_link' => 'https://zoom.us/j/321654987',
                'day_of_week' => 5, // Friday
                'start_time' => '19:00',
                'duration_minutes' => 120,
                'student_count' => 18,
            ],
            
            // OFFLINE CLASSES
            [
                'package_slug' => 'sd-basic-level-classB',
                'title' => 'IPA Eksperimen SD (Offline)',
                'category' => 'IPA',
                'class_level' => 'SD',
                'location' => 'MayClass Learning Center - Kemayoran, Jakarta Pusat',
                'zoom_link' => null,
                'day_of_week' => 6, // Saturday
                'start_time' => '09:00',
                'duration_minutes' => 120,
                'student_count' => 15,
            ],
            [
                'package_slug' => 'sd-basic-level-classC',
                'title' => 'Matematika Interaktif SD (Offline)',
                'category' => 'Matematika',
                'class_level' => 'SD',
                'location' => 'Ruang Kelas A - MayClass Kemayoran',
                'zoom_link' => null,
                'day_of_week' => 6, // Saturday
                'start_time' => '13:00',
                'duration_minutes' => 90,
                'student_count' => 12,
            ],
            [
                'package_slug' => 'smp-intermediate-classA',
                'title' => 'Lab Sains SMP (Offline)',
                'category' => 'IPA',
                'class_level' => 'SMP',
                'location' => 'Laboratorium IPA - MayClass Kemayoran',
                'zoom_link' => null,
                'day_of_week' => 7, // Sunday
                'start_time' => '10:00',
                'duration_minutes' => 120,
                'student_count' => 16,
            ],
            [
                'package_slug' => 'smp-intermediate-classC',
                'title' => 'Workshop Bahasa Inggris SMP (Offline)',
                'category' => 'Bahasa Inggris',
                'class_level' => 'SMP',
                'location' => 'Ruang Multimedia - MayClass Jakarta',
                'zoom_link' => null,
                'day_of_week' => 7, // Sunday
                'start_time' => '14:00',
                'duration_minutes' => 90,
                'student_count' => 14,
            ],
            [
                'package_slug' => 'sma-advanced-classA',
                'title' => 'Fisika SMA - Praktikum (Offline)',
                'category' => 'Fisika',
                'class_level' => 'SMA',
                'location' => 'Lab Fisika - MayClass Learning Hub Jakarta',
                'zoom_link' => null,
                'day_of_week' => 6, // Saturday
                'start_time' => '15:00',
                'duration_minutes' => 150,
                'student_count' => 12,
            ],
            [
                'package_slug' => 'sma-advanced-classB',
                'title' => 'Diskusi Kelompok UTBK (Offline)',
                'category' => 'UTBK',
                'class_level' => 'SMA',
                'location' => 'Ruang Diskusi - MayClass Kemayoran',
                'zoom_link' => null,
                'day_of_week' => 7, // Sunday
                'start_time' => '16:00',
                'duration_minutes' => 120,
                'student_count' => 10,
            ],
        ];

        $createdTemplates = [];
        $tutorIndex = 0;

        foreach ($templateDefinitions as $templateData) {
            $package = $packages->get($templateData['package_slug']);

            if (!$package) {
                // Try to get any package if slug doesn't match
                $package = $packages->first();
            }

            if (!$package) {
                continue;
            }

            // Assign to different tutors in rotation
            $tutor = $tutors[$tutorIndex % $tutors->count()];
            $tutorIndex++;

            // Try to find matching subject
            $subjectId = null;
            if ($subjects->isNotEmpty()) {
                $matchingSubject = $subjects->firstWhere('name', 'like', '%' . $templateData['category'] . '%');
                $subjectId = $matchingSubject ? $matchingSubject->id : $subjects->random()->id;
            }

            $template = ScheduleTemplate::create([
                'user_id' => $tutor->id,
                'package_id' => $package->id,
                'subject_id' => $subjectId,
                'title' => $templateData['title'],
                'category' => $templateData['category'],
                'class_level' => $templateData['class_level'],
                'location' => $templateData['location'],
                'zoom_link' => $templateData['zoom_link'],
                'day_of_week' => $templateData['day_of_week'],
                'start_time' => $templateData['start_time'],
                'duration_minutes' => $templateData['duration_minutes'],
                'student_count' => $templateData['student_count'],
                'is_active' => true,
            ]);

            $createdTemplates[] = $template;

            // Generate sessions from template (next 4 weeks)
            if (class_exists('App\Support\ScheduleTemplateGenerator')) {
                ScheduleTemplateGenerator::refreshTemplate($template);
            }
        }

        $this->command->info('✅ Created ' . count($createdTemplates) . ' schedule templates');

        // Count generated sessions
        $sessionsCount = ScheduleSession::count();
        $this->command->info('✅ Generated ' . $sessionsCount . ' schedule sessions from templates');

        // Create highlighted sessions for students
        if ($students->isNotEmpty() && !empty($createdTemplates)) {
            $this->command->info('🌟 Creating highlighted sessions for students...');
            
            $highlightCount = 0;
            foreach ($students->take(3) as $student) {
                // Get 2-3 upcoming sessions for each student
                $upcomingSessions = ScheduleSession::query()
                    ->where('start_at', '>=', Carbon::now())
                    ->where('status', 'scheduled')
                    ->inRandomOrder()
                    ->take(rand(2, 3))
                    ->get();

                foreach ($upcomingSessions as $session) {
                    // Create a copy of the session for the student
                    ScheduleSession::create([
                        'user_id' => $student->id, // Assign to student
                        'package_id' => $session->package_id,
                        'schedule_template_id' => $session->schedule_template_id,
                        'title' => $session->title,
                        'category' => $session->category,
                        'class_level' => $session->class_level,
                        'location' => $session->location,
                        'zoom_link' => $session->zoom_link,
                        'student_count' => $session->student_count,
                        'mentor_name' => $session->mentor_name ?? 'Tutor MayClass',
                        'start_at' => $session->start_at,
                        'duration_minutes' => $session->duration_minutes,
                        'is_highlight' => true,
                        'status' => 'scheduled',
                        'cancelled_at' => null,
                    ]);

                    $highlightCount++;
                }
            }

            $this->command->info('✅ Created ' . $highlightCount . ' highlighted sessions for students');
        }

        // Highlight the first upcoming session for each tutor
        foreach ($tutors as $tutor) {
            $firstSession = ScheduleSession::query()
                ->where('user_id', $tutor->id)
                ->where('start_at', '>=', Carbon::now())
                ->orderBy('start_at')
                ->first();

            if ($firstSession) {
                $firstSession->update(['is_highlight' => true]);
            }
        }

        $this->command->info('🎉 Schedule seeding completed successfully!');
        $this->command->info('📊 Total templates: ' . count($createdTemplates));
        $this->command->info('📅 Total sessions: ' . ScheduleSession::count());
    }
}
