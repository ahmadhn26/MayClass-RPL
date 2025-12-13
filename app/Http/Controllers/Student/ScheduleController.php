<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ScheduleSession;
use App\Support\ScheduleViewData;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request): View
    {
        $student = Auth::user();
        $hasEnrollmentsTable = Schema::hasTable('enrollments');

        $enrollments = (! $student || ! $hasEnrollmentsTable)
            ? collect()
            : $student->enrollments()
                ->with('package')
                ->when(
                    Schema::hasColumn('enrollments', 'is_active'),
                    fn ($query) => $query->where('is_active', true)
                )
                ->when(
                    Schema::hasColumn('enrollments', 'ends_at'),
                    fn ($query) => $query->where(function ($subQuery) {
                        $subQuery
                            ->whereNull('ends_at')
                            ->orWhere('ends_at', '>=', now());
                    })
                )
                ->orderByDesc('ends_at')
                ->get();

        $packageIds = $enrollments->pluck('package_id')->filter()->unique();
        $packages = $enrollments->pluck('package')->filter();
        $primaryPackage = $packages->count() === 1 ? $packages->first() : null;

        $sessions = ($packageIds->isEmpty() || ! Schema::hasTable('schedule_sessions'))
            ? collect()
            : ScheduleSession::query()
                ->with(['package:id,detail_title'])
                ->whereIn('package_id', $packageIds)
                ->when(
                    $hasEnrollmentsTable,
                    function ($query) use ($student) {
                        $query->whereHas('package.enrollments', function ($enrollments) use ($student) {
                            $enrollments->where('user_id', optional($student)->id);

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
                ->get();

        $viewMode = $this->resolveViewMode($request->query('view'));
        $referenceDate = $this->parseDate($request->query('date'));

        $schedule = ScheduleViewData::compose($sessions, $viewMode, $referenceDate);

        $upcomingSessions = $sessions->filter(function ($session) {
            $start = $this->parseDate($session->start_at ?? null);

            return $start ? $start->isFuture() : false;
        });

        $stats = [
            'total' => $sessions->count(),
            'upcoming' => $upcomingSessions->count(),
            'completed' => max($sessions->count() - $upcomingSessions->count(), 0),
        ];

        return view('student.schedule', [
            'page' => 'schedule',
            'title' => 'Jadwal Belajar',
            'activePackage' => $primaryPackage,
            'enrolledPackages' => $packages,
            'schedule' => $schedule,
            'stats' => $stats,
        ]);
    }

    private function resolveViewMode(?string $view): string
    {
        return match ($view) {
            'day', 'harian' => 'day',
            'week', 'mingguan' => 'week',
            default => 'month',
        };
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
