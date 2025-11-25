<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Material;
use App\Models\Package;
use App\Models\Quiz;
use App\Models\ScheduleSession;
use App\Support\PackagePresenter;
use App\Support\ScheduleViewData;
use App\Support\StudentAccess;
use App\Support\SubjectPalette;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Throwable;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $materialsLink = (string) config('mayclass.links.materials_drive');
        $quizLink = (string) config('mayclass.links.quiz_platform');
        $hasEnrollmentsTable = Schema::hasTable('enrollments');

        $activeEnrollment = StudentAccess::activeEnrollment($user);
        $hasActivePackage = StudentAccess::hasActivePackage($user);

        if (! $hasActivePackage) {
            return view('student.dashboard', [
                'page' => 'dashboard',
                'title' => 'Dashboard Siswa',
                'hasActivePackage' => false,
                'activePackage' => $this->formatActivePackage($activeEnrollment),
                'materialsLink' => $materialsLink,
                'quizLink' => $quizLink,
            ]);
        }

        $packageId = optional($activeEnrollment)->package_id;

        $sessions = Schema::hasTable('schedule_sessions')
            ? ScheduleSession::query()
                ->with(['package:id,detail_title'])
                ->where('package_id', $packageId)
                ->when(
                    $hasEnrollmentsTable,
                    function ($query) use ($user) {
                        $query->whereHas('package.enrollments', function ($enrollments) use ($user) {
                            $enrollments->where('user_id', optional($user)->id);

                            if (Schema::hasColumn('enrollments', 'is_active')) {
                                $enrollments->where('is_active', true);
                            }

                            if (Schema::hasColumn('enrollments', 'ends_at')) {
                                $enrollments->where(function ($dateQuery) {
                                    $dateQuery
                                        ->whereNull('ends_at')
                                        ->orWhere('ends_at', '>=', now());
                                });
                            }
                        });
                    }
                )
                ->when(
                    Schema::hasColumn('schedule_sessions', 'status'),
                    fn ($query) => $query->whereNotIn('status', ['cancelled'])
                )
                ->orderBy('start_at')
                ->get()
            : collect();
        $schedule = ScheduleViewData::fromCollection($sessions);

        $materialsAvailable = Schema::hasTable('materials');
        $materialChaptersReady = Schema::hasTable('material_chapters');
        $materialObjectivesReady = Schema::hasTable('material_objectives');
        $quizzesAvailable = Schema::hasTable('quizzes');
        $quizLevelsReady = Schema::hasTable('quiz_levels');

        $recentMaterials = $materialsAvailable
            ? Material::query()
                ->where('package_id', $packageId)
                ->when($materialChaptersReady, fn ($query) => $query->withCount('chapters'))
                ->when($materialObjectivesReady, fn ($query) => $query->withCount('objectives'))
                ->orderByDesc('created_at')
                ->take(4)
                ->get()
                ->map(function (Material $material) use ($materialsLink, $materialChaptersReady, $materialObjectivesReady) {
                    return [
                        'slug' => $material->slug,
                        'subject' => $material->subject,
                        'title' => $material->title,
                        'summary' => $material->summary,
                        'level' => $material->level,
                        'chapter_count' => $materialChaptersReady ? (int) $material->chapters_count : 0,
                        'objective_count' => $materialObjectivesReady ? (int) $material->objectives_count : 0,
                        'resource' => $material->resource_url ?? $materialsLink,
                        'accent' => SubjectPalette::accent($material->subject),
                    ];
                })
            : collect();

        $recentQuizzes = $quizzesAvailable
            ? Quiz::query()
                ->where('package_id', $packageId)
                ->when($quizLevelsReady, fn ($query) => $query->with(['levels' => fn ($levels) => $levels->orderBy('position')]))
                ->orderByDesc('created_at')
                ->take(4)
                ->get()
                ->map(function (Quiz $quiz) use ($quizLink, $quizLevelsReady) {
                    return [
                        'slug' => $quiz->slug,
                        'title' => $quiz->title,
                        'summary' => $quiz->summary,
                        'duration' => $quiz->duration_label,
                        'questions' => $quiz->question_count,
                        'levels' => $quizLevelsReady ? $quiz->levels->pluck('label')->all() : [],
                        'link' => $quiz->link ?? $quizLink,
                        'accent' => SubjectPalette::accent($quiz->subject),
                    ];
                })
            : collect();

        $materialsTotal = $materialsAvailable
            ? Material::where('package_id', $packageId)->count()
            : 0;
        $recentMaterialsCount = $materialsAvailable
            ? Material::where('package_id', $packageId)
                ->where('created_at', '>=', now()->subDays(14))
                ->count()
            : 0;
        $subjectsTotal = $materialsAvailable
            ? Material::where('package_id', $packageId)->distinct('subject')->count('subject')
            : 0;
        $materialLevels = $materialsAvailable
            ? Material::where('package_id', $packageId)->select('level')->distinct()->pluck('level')->filter()->values()->all()
            : [];

        $quizzesTotal = $quizzesAvailable
            ? Quiz::where('package_id', $packageId)->count()
            : 0;
        $recentQuizzesCount = $quizzesAvailable
            ? Quiz::where('package_id', $packageId)
                ->where('created_at', '>=', now()->subDays(14))
                ->count()
            : 0;
        $quizLevels = $quizzesAvailable
            ? Quiz::where('package_id', $packageId)->select('class_level')->distinct()->pluck('class_level')->filter()->values()->all()
            : [];

        $levelSet = collect($materialLevels)->merge($quizLevels);

        if ($quizLevelsReady && $quizzesAvailable) {
            $levelSet = $levelSet->merge(
                Quiz::query()
                    ->where('package_id', $packageId)
                    ->with(['levels' => fn ($levels) => $levels->orderBy('position')])
                    ->get()
                    ->flatMap(fn ($quiz) => $quiz->levels->pluck('label'))
            );
        }

        $levelSet = $levelSet->filter()->unique()->values();

        $upcomingTotal = $sessions
            ->filter(function ($session) {
                $start = $this->parseDate($session->start_at ?? null);

                return $start ? $start->isFuture() : false;
            })
            ->count();

        $weekSessions = $sessions
            ->filter(function ($session) {
                $start = $this->parseDate($session->start_at ?? null);

                return $start ? $start->isSameWeek(CarbonImmutable::now()) : false;
            })
            ->count();

        return view('student.dashboard', [
            'page' => 'dashboard',
            'title' => 'Dashboard Siswa',
            'schedule' => $schedule,
            'recentMaterials' => $recentMaterials,
            'recentQuizzes' => $recentQuizzes,
            'metrics' => [
                'materials_total' => $materialsTotal,
                'recent_materials' => $recentMaterialsCount,
                'quizzes_total' => $quizzesTotal,
                'recent_quizzes' => $recentQuizzesCount,
                'upcoming_total' => $upcomingTotal,
                'week_sessions' => $weekSessions,
                'subjects_total' => $subjectsTotal,
                'levels_total' => $levelSet->count(),
            ],
            'activePackage' => $this->formatActivePackage($activeEnrollment),
            'materialsLink' => $materialsLink,
            'quizLink' => $quizLink,
            'hasActivePackage' => true,
        ]);
    }

    private function formatActivePackage(?Enrollment $enrollment): array
    {
        if (! $enrollment || ! $enrollment->package) {
            return [
                'title' => 'Belum ada paket aktif',
                'period' => 'Silakan pilih paket belajar untuk mulai.',
                'status' => 'Tidak aktif',
            ];
        }

        $package = $enrollment->package;
        $endDate = $enrollment->ends_at ? CarbonImmutable::parse($enrollment->ends_at) : null;

        return [
            'title' => $package->detail_title,
            'period' => $endDate
                ? 'Aktif hingga ' . ScheduleViewData::formatFullDate($endDate)
                : __('Berlangganan aktif'),
            'status' => $endDate
                ? ($endDate->isFuture() ? 'Berjalan' : 'Berakhir')
                : 'Berjalan',
        ];
    }

    private function packagesForUpsell()
    {
        if (! Schema::hasTable('packages')) {
            return collect();
        }

        try {
            $query = Package::query()->orderBy('price');

            if (Schema::hasTable('package_features')) {
                $query->with(['cardFeatures' => fn ($features) => $features->orderBy('position')]);
            }

            return $query->get()->map(fn (Package $package) => PackagePresenter::card($package))->values();
        } catch (Throwable $exception) {
            return collect();
        }
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
