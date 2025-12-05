<?php

namespace App\Support;

use App\Models\ScheduleSession;
use Carbon\CarbonImmutable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ScheduleViewData
{
    private const DAY_LABELS = [
        1 => 'Sen',
        2 => 'Sel',
        3 => 'Rab',
        4 => 'Kam',
        5 => 'Jum',
        6 => 'Sab',
        7 => 'Min',
    ];

    private const DAY_NAMES = [
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
        7 => 'Minggu',
    ];

    private const MONTH_NAMES = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    public static function compose(Collection $sessions, string $view, ?CarbonImmutable $referenceDate = null): array
    {
        $normalizedView = in_array($view, ['day', 'week'], true) ? $view : 'month';

        $validSessions = $sessions->filter(fn($session) => self::parseDate($session->start_at ?? null));

        $reference = $referenceDate ?? CarbonImmutable::now();

        $sessionGroups = $validSessions->mapToGroups(function ($session) {
            $date = self::parseDate($session->start_at ?? null);

            return $date ? [$date->toDateString() => $session] : [];
        });

        $highlightSession = $validSessions->firstWhere('is_highlight', true)
            ?? $validSessions->firstWhere(function ($session) {
                $start = self::parseDate($session->start_at ?? null);

                return $start ? $start->isFuture() : false;
            })
            ?? $validSessions->first();

        $highlight = $highlightSession
            ? self::formatSession($highlightSession)
            : [
                'title' => 'Belum ada jadwal',
                'category' => '-',
                'date' => self::formatFullDate($reference),
                'time' => '-',
                'mentor' => '-',
            ];

        // Find current/ongoing session
        $now = CarbonImmutable::now();
        $currentSession = $validSessions->first(function ($session) use ($now) {
            $start = self::parseDate($session->start_at ?? null);
            if (!$start) {
                return false;
            }

            $duration = (int) ($session->duration_minutes ?? 90);
            $duration = $duration > 0 ? $duration : 90;
            $end = $start->addMinutes($duration);

            // Session is current if now is between start and end time
            return $now->greaterThanOrEqualTo($start) && $now->lessThan($end);
        });

        $current = $currentSession ? self::formatSession($currentSession) : null;

        $upcoming = $validSessions
            ->filter(function ($session) {
                $start = self::parseDate($session->start_at ?? null);

                return $start ? $start->isFuture() : false;
            })
            ->sortBy('start_at')
            ->map(fn($session) => self::formatSession($session))
            ->values()
            ->take(3);

        if ($upcoming->isEmpty() && $highlightSession) {
            $upcoming = collect([$highlight]);
        }

        $calendar = self::buildCalendarGrid($normalizedView, $reference, $sessionGroups, $validSessions);

        // Build history sessions (past sessions only)
        $now = CarbonImmutable::now();
        $historySessions = $validSessions
            ->filter(function ($session) use ($now) {
                $start = self::parseDate($session->start_at ?? null);
                if (!$start) {
                    return false;
                }

                $duration = (int) ($session->duration_minutes ?? 90);
                $duration = $duration > 0 ? $duration : 90;
                $end = $start->addMinutes($duration);

                // Session is past if end time has passed
                return $end->lessThan($now);
            })
            ->sortByDesc('start_at')
            ->map(fn($session) => self::formatSession($session))
            ->values()
            ->take(10); // Limit to last 10 sessions

        return [
            'view' => $normalizedView,
            'current' => $current,
            'highlight' => $highlight,
            'upcoming' => $upcoming,
            'calendar' => Arr::except($calendar, ['range_start', 'range_end']),
            'rangeSessions' => $historySessions, // Reuse same key to avoid breaking view
        ];

    }

    public static function fromCollection(Collection $sessions): array
    {
        return self::compose($sessions, 'month');
    }

    public static function formatFullDate(CarbonImmutable $date): string
    {
        $dayLabel = self::DAY_NAMES[$date->dayOfWeekIso];
        $monthLabel = self::MONTH_NAMES[$date->month];

        return sprintf('%s, %d %s %d', $dayLabel, $date->day, $monthLabel, $date->year);
    }

    public static function formatSession(ScheduleSession $session): array
    {
        $startAt = self::parseDate($session->start_at ?? null);

        if (!$startAt) {
            return [
                'title' => $session->title,
                'category' => $session->category,
                'date' => '-',
                'time' => '-',
                'mentor' => $session->mentor_name,
                'start_time' => null,
                'start_at_iso' => null,
            ];
        }

        $duration = (int) ($session->duration_minutes ?? 90);
        $duration = $duration > 0 ? $duration : 90;
        $endAt = $startAt->addMinutes($duration);

        return [
            'title' => $session->title,
            'category' => $session->category,
            'date' => self::formatFullDate($startAt),
            'time' => $startAt->format('H.i') . ' - ' . $endAt->format('H.i') . ' WIB',
            'mentor' => $session->mentor_name,
            'location' => $session->location,
            'zoom_link' => $session->zoom_link,
            'start_time' => $startAt->format('H.i'),
            'start_at_iso' => $startAt->toIso8601String(),
            'start_at' => $startAt, // Add Carbon object for time comparison
        ];


    }

    private static function buildCalendarGrid(string $view, CarbonImmutable $referenceDate, Collection $sessionGroups, Collection $sessions): array
    {
        $now = CarbonImmutable::now();

        [$rangeStart, $rangeEnd, $gridStart, $gridEnd, $columns, $label, $prevDate, $nextDate] = match ($view) {
            'day' => [
                $referenceDate->startOfDay(),
                $referenceDate->endOfDay(),
                $referenceDate->startOfDay(),
                $referenceDate->startOfDay(),
                1,
                self::formatFullDate($referenceDate),
                $referenceDate->subDay()->toDateString(),
                $referenceDate->addDay()->toDateString(),
            ],
            'week' => $thisWeek = [
                $referenceDate->startOfWeek(CarbonImmutable::MONDAY),
                $referenceDate->startOfWeek(CarbonImmutable::MONDAY)->addDays(6),
                $referenceDate->startOfWeek(CarbonImmutable::MONDAY),
                $referenceDate->startOfWeek(CarbonImmutable::MONDAY)->addDays(6),
                7,
                self::formatWeekRange(
                    $referenceDate->startOfWeek(CarbonImmutable::MONDAY),
                    $referenceDate->startOfWeek(CarbonImmutable::MONDAY)->addDays(6)
                ),
                $referenceDate->startOfWeek(CarbonImmutable::MONDAY)->subWeek()->toDateString(),
                $referenceDate->startOfWeek(CarbonImmutable::MONDAY)->addWeek()->toDateString(),
            ],
            default => [
                $referenceDate->startOfMonth(),
                $referenceDate->endOfMonth(),
                $referenceDate->startOfMonth()->startOfWeek(CarbonImmutable::MONDAY),
                $referenceDate->endOfMonth()->endOfWeek(CarbonImmutable::SUNDAY),
                7,
                self::MONTH_NAMES[$referenceDate->month] . ' ' . $referenceDate->year,
                $referenceDate->startOfMonth()->subMonth()->toDateString(),
                $referenceDate->startOfMonth()->addMonth()->toDateString(),
            ],
        };

        $days = [];
        for ($date = $gridStart; $date->lessThanOrEqualTo($gridEnd); $date = $date->addDay()) {
            $dayKey = $date->toDateString();
            $sessionsForDay = $sessionGroups->get($dayKey, collect());

            $days[] = [
                'date' => $dayKey,
                'display' => $view === 'day' ? $date->format('d M') : (string) $date->day,
                'weekday' => self::DAY_LABELS[$date->dayOfWeekIso],
                'fullLabel' => self::formatFullDate($date),
                'isMuted' => $view === 'month' ? $date->month !== $referenceDate->month : false,
                'isToday' => $date->isSameDay($now),
                'isActive' => $sessionsForDay->isNotEmpty(),
                'sessions' => $sessionsForDay->map(fn($session) => self::formatSession($session))->values()->all(),
            ];
        }

        $weeks = [];
        $chunk = [];
        foreach ($days as $day) {
            $chunk[] = $day;

            if (count($chunk) === $columns) {
                $weeks[] = $chunk;
                $chunk = [];
            }
        }

        if (!empty($chunk)) {
            $weeks[] = $chunk;
        }

        return [
            'view' => $view,
            'label' => $label,
            'currentDate' => $referenceDate->toDateString(),
            'prevDate' => $prevDate,
            'nextDate' => $nextDate,
            'columns' => $columns,
            'weekdays' => array_values(self::DAY_LABELS),
            'weeks' => $weeks,
            'range_start' => $rangeStart->toDateString(),
            'range_end' => $rangeEnd->toDateString(),
        ];
    }

    private static function buildRangeSessions(string $startDate, string $endDate, Collection $sessionGroups): array
    {
        $start = CarbonImmutable::parse($startDate);
        $end = CarbonImmutable::parse($endDate);

        $days = [];

        for ($date = $start; $date->lessThanOrEqualTo($end); $date = $date->addDay()) {
            $key = $date->toDateString();
            $sessions = $sessionGroups->get($key, collect());

            $formatted = $sessions->map(fn($session) => self::formatSession($session))->values()->all();

            if (!empty($formatted)) {
                $days[] = [
                    'date' => $key,
                    'label' => self::formatFullDate($date),
                    'sessions' => $formatted,
                ];
            }
        }

        return $days;
    }

    private static function formatWeekRange(CarbonImmutable $start, CarbonImmutable $end): string
    {
        if ($start->month === $end->month && $start->year === $end->year) {
            return sprintf(
                'Minggu %d - %d %s %d',
                $start->day,
                $end->day,
                self::MONTH_NAMES[$end->month],
                $end->year
            );
        }

        return sprintf(
            'Minggu %d %s %d - %d %s %d',
            $start->day,
            self::MONTH_NAMES[$start->month],
            $start->year,
            $end->day,
            self::MONTH_NAMES[$end->month],
            $end->year
        );
    }

    private static function parseDate($value): ?CarbonImmutable
    {
        if (!$value) {
            return null;
        }

        try {
            return CarbonImmutable::parse($value);
        } catch (\Throwable $exception) {
            return null;
        }
    }
}
