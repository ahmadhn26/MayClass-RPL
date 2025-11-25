<?php

namespace App\Http\Controllers\Tutor;

use App\Models\Enrollment;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\ScheduleSession;
use App\Models\ScheduleTemplate;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class DashboardController extends BaseTutorController
{
    public function index()
    {
        $tutor = Auth::user();
        $assignedPackageIds = Schema::hasTable('schedule_templates') && $tutor
            ? ScheduleTemplate::query()
                ->where('user_id', $tutor->id)
                ->pluck('package_id')
                ->filter()
                ->unique()
            : collect();

        $stats = [
            'students' => Schema::hasTable('enrollments')
                ? Enrollment::where('is_active', true)->distinct('user_id')->count('user_id')
                : 0,
            'materials' => Schema::hasTable('materials') ? Material::count() : 0,
            'quizzes' => Schema::hasTable('quizzes') ? Quiz::count() : 0,
        ];

        $today = CarbonImmutable::now();

        $sessions = Schema::hasTable('schedule_sessions')
            ? ScheduleSession::query()
                ->when($tutor, fn ($query) => $query->where('user_id', $tutor->id))
                ->when(
                    $assignedPackageIds->isNotEmpty(),
                    fn ($query) => $query->whereIn('package_id', $assignedPackageIds)
                )
                ->orderBy('start_at')
                ->get()
            : collect();

        $todaySessions = $sessions
            ->filter(function ($session) use ($today) {
                $start = $this->parseDate($session->start_at ?? null);

                return $start ? $start->isSameDay($today) : false;
            })
            ->map(function (ScheduleSession $session) {
                $start = $this->parseDate($session->start_at ?? null);
                $end = $start ? $start->addMinutes(90) : null;

                return [
                    'subject' => $session->category,
                    'title' => $session->title,
                    'class_level' => $session->class_level ?? 'Kelas',
                    'time_range' => $start && $end
                        ? $start->format('H.i') . ' - ' . $end->format('H.i')
                        : 'Jadwal belum tersedia',
                    'location' => $session->location ?? 'Ruang Virtual',
                    'student_count' => $session->student_count,
                    'highlight' => (bool) $session->is_highlight,
                ];
            })
            ->values();

        $nextSessions = $sessions
            ->reject(function ($session) use ($today) {
                $start = $this->parseDate($session->start_at ?? null);

                return $start ? $start->isSameDay($today) : false;
            })
            ->sortBy('start_at')
            ->take(6)
            ->map(function (ScheduleSession $session) {
                $start = $this->parseDate($session->start_at ?? null);
                $end = $start ? $start->addMinutes(90) : null;

                return [
                    'day' => $start ? $start->locale('id')->translatedFormat('l') : '-',
                    'title' => $session->title,
                    'subject' => $session->category,
                    'class_level' => $session->class_level ?? '-',
                    'date_label' => $start ? $start->locale('id')->translatedFormat('d F Y') : '-',
                    'time_range' => $start && $end
                        ? $start->format('H.i') . ' - ' . $end->format('H.i')
                        : 'Jadwal belum tersedia',
                ];
            })
            ->values();

        $totalStudentsToday = $todaySessions->sum('student_count');
        $teachingHours = round($sessions
            ->map(fn ($session) => $this->parseDate($session->start_at ?? null))
            ->filter()
            ->count() * 1.5, 1);

        $highlightStats = [
            [
                'label' => 'Materi Aktif',
                'display' => number_format($stats['materials']),
                'suffix' => 'materi',
                'description' => 'Siap dibagikan kepada siswa',
                'accent' => 'orange',
            ],
            [
                'label' => 'Quiz Siap Pakai',
                'display' => number_format($stats['quizzes']),
                'suffix' => 'kuis',
                'description' => 'Evaluasi pembelajaran yang tersedia',
                'accent' => 'purple',
            ],
        ];

        $materialsShowcase = Schema::hasTable('materials')
            ? Material::query()
                ->latest('id')
                ->take(3)
                ->get()
                ->map(function (Material $material) {
                    return [
                        'title' => $material->title,
                        'subject' => $material->subject,
                        'level' => $material->level,
                        'thumbnail' => $material->thumbnail_asset,
                        'link' => route('tutor.materials.edit', ['material' => $material->slug]),
                    ];
                })
            : collect();

        $quizShowcase = Schema::hasTable('quizzes')
            ? Quiz::query()
                ->latest('id')
                ->take(3)
                ->get()
                ->map(function (Quiz $quiz) {
                    return [
                        'title' => $quiz->title,
                        'subject' => $quiz->subject,
                        'level' => $quiz->class_level,
                        'thumbnail' => $quiz->thumbnail_asset,
                        'link' => route('tutor.quizzes.edit', ['quiz' => $quiz->slug]),
                    ];
                })
            : collect();

        $quickActions = [
            [
                'label' => 'Buat Materi Baru',
                'description' => 'Susun modul dan sumber belajar terkini.',
                'href' => route('tutor.materials.create'),
            ],
            [
                'label' => 'Lihat Jadwal Mengajar',
                'description' => 'Pantau agenda yang sudah ditetapkan admin.',
                'href' => route('tutor.schedule.index'),
            ],
            [
                'label' => 'Rancang Quiz',
                'description' => 'Siapkan evaluasi untuk siswa.',
                'href' => route('tutor.quizzes.create'),
            ],
        ];

        return $this->render('tutor.dashboard', [
            'stats' => $stats,
            'todaySessions' => $todaySessions,
            'nextSessions' => $nextSessions,
            'todayLabel' => $today->locale('id')->translatedFormat('l, d F Y'),
            'totalStudentsToday' => $totalStudentsToday,
            'teachingHours' => $teachingHours,
            'highlightStats' => $highlightStats,
            'materialsShowcase' => $materialsShowcase,
            'quizShowcase' => $quizShowcase,
            'quickActions' => $quickActions,
        ]);
    }

    private function parseDate($value): ?CarbonImmutable
    {
        if (! $value) {
            return null;
        }

        try {
            return CarbonImmutable::parse($value);
        } catch (\Throwable $exception) {
            return null;
        }
    }
}
