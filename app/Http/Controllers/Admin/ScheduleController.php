<?php

namespace App\Http\Controllers\Admin;

use App\Models\Package;
use App\Models\ScheduleSession;
use App\Models\ScheduleTemplate;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class ScheduleController extends BaseAdminController
{
    public function index(Request $request)
    {
        return $this->render('admin.schedules.index', [
            'schedule' => $this->buildScheduleOverview($request->input('tutor_id')),
        ]);
    }

    private function buildScheduleOverview($requestedTutor): array
    {
        $tutors = Schema::hasTable('users')
            ? User::query()->where('role', 'tutor')->orderBy('name')->get(['id', 'name'])
            : collect();

        $selectedTutorId = $this->resolveTutorFilter($requestedTutor, $tutors);

        $packages = Schema::hasTable('packages')
            ? Package::orderBy('level')->orderBy('price')
                ->when($selectedTutorId, function ($query) use ($selectedTutorId) {
                    $query->whereHas('tutors', function ($q) use ($selectedTutorId) {
                        $q->where('user_id', $selectedTutorId);
                    });
                })
                ->get(['id', 'detail_title', 'level'])
            : collect();

        $sessionsReady = Schema::hasTable('schedule_sessions');

        $sessions = $sessionsReady
            ? ScheduleSession::query()
                ->when($selectedTutorId, fn ($query) => $query->where('user_id', $selectedTutorId))
                ->orderBy('start_at')
                ->get()
            : collect();

        if ($sessionsReady && Schema::hasTable('users') && $sessions->isNotEmpty()) {
            $sessions->load('user:id,name');
        }

        if ($sessionsReady && Schema::hasTable('packages') && $sessions->isNotEmpty()) {
            $sessions->load('package:id,detail_title');
        }

        $now = CarbonImmutable::now();

        $sessionPayload = $sessions->map(function (ScheduleSession $session) use ($now) {
            $start = $this->parseScheduleDate($session->start_at);
            $duration = (int) ($session->duration_minutes ?? 90);
            $duration = $duration > 0 ? $duration : 90;
            $end = $start ? $start->addMinutes($duration) : null;

            $timeRange = $start && $end
                ? $start->format('H.i') . ' - ' . $end->format('H.i') . ' WIB'
                : __('Waktu belum ditetapkan');

            $dateKey = $start ? $start->format('Y-m-d') : (string) $session->id;

            return [
                'id' => $session->id,
                'date_key' => $dateKey,
                'weekday' => $start ? $start->locale('id')->isoFormat('dddd') : __('Tanggal belum ditetapkan'),
                'full_date' => $start ? $start->locale('id')->isoFormat('D MMMM Y') : '-',
                'label' => $start ? $start->locale('id')->isoFormat('dddd, D MMMM YYYY') : '-',
                'time_range' => $timeRange,
                'subject' => $session->category ?? '-',
                'title' => $session->title,
                'package' => optional($session->package)->detail_title ?? __('Paket MayClass'),
                'location' => $session->location ?? __('Ruang Virtual'),
                'class_level' => $session->class_level ?? '-',
                'student_count' => $session->student_count,
                'status' => $session->status ?? 'scheduled',
                'tutor' => optional($session->user)->name ?? __('Tutor belum ditetapkan'),
                'start_iso' => $start?->toIso8601String(),
                'is_past' => $end ? $end->lt($now) : false,
            ];
        });

        $upcomingSessions = $sessionPayload->filter(fn ($session) => $session['status'] !== 'cancelled' && ! $session['is_past']);
        $historySessions = $sessionPayload
            ->filter(fn ($session) => $session['status'] !== 'cancelled' && $session['is_past'])
            ->sortByDesc('start_iso')
            ->take(6)
            ->values();
        $cancelledSessions = $sessionPayload
            ->filter(fn ($session) => $session['status'] === 'cancelled')
            ->sortByDesc('start_iso')
            ->take(6)
            ->values();

        $upcomingDays = $upcomingSessions
            ->groupBy('date_key')
            ->map(function (Collection $items) {
                $first = $items->first();

                return [
                    'weekday' => $first['weekday'],
                    'full_date' => $first['full_date'],
                    'label' => $first['label'],
                    'items' => $items->map(function (array $item) {
                        return [
                            'id' => $item['id'],
                            'title' => $item['title'],
                            'subject' => $item['subject'],
                            'package' => $item['package'],
                            'time_range' => $item['time_range'],
                            'location' => $item['location'],
                            'class_level' => $item['class_level'],
                            'student_count' => $item['student_count'],
                            'tutor' => $item['tutor'],
                            'status' => $item['status'],
                        ];
                    })->values(),
                ];
            })
            ->sortKeys()
            ->values();

        $templatesReady = Schema::hasTable('schedule_templates') && $selectedTutorId;

        $templates = $templatesReady
            ? ScheduleTemplate::query()
                ->where('user_id', $selectedTutorId)
                ->orderBy('day_of_week')
                ->orderBy('start_time')
                ->with(['package:id,detail_title', 'user:id,name'])
                ->get()
                ->sortBy([
                    ['day_of_week', 'asc'],
                    ['start_time', 'asc']
                ])
                ->values()
                ->map(function (ScheduleTemplate $template) {
                    $nextDate = $this->nextDateForDay($template->day_of_week);

                    return [
                        'id' => $template->id,
                        'package_id' => $template->package_id,
                        'title' => $template->title,
                        'category' => $template->category,
                        'class_level' => $template->class_level,
                        'location' => $template->location,
                        'start_time' => $template->start_time,
                        'duration_minutes' => $template->duration_minutes,
                        'student_count' => $template->student_count,
                        'user_id' => $template->user_id,
                        'package_label' => optional($template->package)->detail_title ?? __('Paket MayClass'),
                        'reference_date_value' => $nextDate?->toDateString(),
                        'reference_date_label' => $nextDate ? $nextDate->locale('id')->translatedFormat('dddd, D MMMM YYYY') : null,
                    ];
                })
            : collect();

        $templateTotal = Schema::hasTable('schedule_templates')
            ? ($selectedTutorId ? $templates->count() : ScheduleTemplate::count())
            : 0;

        return [
            'tutors' => $tutors,
            'selectedTutorId' => $selectedTutorId,
            'activeFilter' => $selectedTutorId ? (string) $selectedTutorId : 'all',
            'packages' => $packages,
            'upcomingDays' => $upcomingDays,
            'historySessions' => $historySessions,
            'cancelledSessions' => $cancelledSessions,
            'templates' => $templates,
            'metrics' => [
                'upcoming' => $upcomingSessions->count(),
                'history' => $historySessions->count(),
                'cancelled' => $cancelledSessions->count(),
                'templates' => $templateTotal,
            ],
            'referenceDate' => CarbonImmutable::now()->toDateString(),
            'ready' => $sessionsReady,
        ];
    }

    private function resolveTutorFilter($requestedTutor, Collection $tutors): ?int
    {
        if ($requestedTutor === 'all') {
            return null;
        }

        if ($requestedTutor !== null && $requestedTutor !== '') {
            $candidate = (int) $requestedTutor;
            $match = $tutors->firstWhere('id', $candidate);

            if ($match) {
                return $match->id;
            }
        }

        return $tutors->count() === 1 ? optional($tutors->first())->id : null;
    }

    private function parseScheduleDate($value): ?CarbonImmutable
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

    private function nextDateForDay(?int $isoDayOfWeek): ?CarbonImmutable
    {
        if ($isoDayOfWeek === null) {
            return null;
        }

        // Convert ISO (1-7) to Carbon (0-6)
        $carbonDayOfWeek = $isoDayOfWeek === 7 ? 0 : $isoDayOfWeek;

        $now = CarbonImmutable::now();

        if ($now->dayOfWeek === $carbonDayOfWeek) {
            return $now;
        }

        return $now->next($carbonDayOfWeek);
    }
}
