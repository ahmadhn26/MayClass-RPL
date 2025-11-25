<?php

namespace App\Support;

use App\Models\ScheduleSession;
use App\Models\ScheduleTemplate;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class ScheduleTemplateGenerator
{
    public static function ensureForTutor(User $tutor, int $weeks = 8): void
    {
        if (! self::ready()) {
            return;
        }

        $templates = ScheduleTemplate::query()
            ->where('user_id', $tutor->id)
            ->where('is_active', true)
            ->with('package')
            ->get();

        if ($templates->isEmpty()) {
            return;
        }

        self::ensureSessions($templates, $weeks);
    }

    public static function ensureForPackage(int $packageId, int $weeks = 8): void
    {
        if (! self::ready()) {
            return;
        }

        $templates = ScheduleTemplate::query()
            ->where('package_id', $packageId)
            ->where('is_active', true)
            ->with('user')
            ->get();

        if ($templates->isEmpty()) {
            return;
        }

        self::ensureSessions($templates, $weeks);
    }

    public static function refreshTemplate(ScheduleTemplate $template, int $weeks = 8): void
    {
        if (! self::ready()) {
            return;
        }

        ScheduleSession::query()
            ->where('schedule_template_id', $template->id)
            ->where('start_at', '>=', CarbonImmutable::now()->startOfDay())
            ->delete();

        self::ensureSessions(collect([$template->fresh()]), $weeks);
    }

    public static function removeTemplateSessions(ScheduleTemplate $template): void
    {
        if (! self::ready()) {
            return;
        }

        ScheduleSession::query()
            ->where('schedule_template_id', $template->id)
            ->where('start_at', '>=', CarbonImmutable::now()->startOfDay())
            ->delete();
    }

    private static function ensureSessions(Collection $templates, int $weeks): void
    {
        $now = CarbonImmutable::now();
        $windowStart = $now->startOfWeek(CarbonImmutable::MONDAY);
        $windowEnd = $windowStart->addWeeks($weeks)->endOfWeek(CarbonImmutable::SUNDAY);

        $templates->each(function (ScheduleTemplate $template) use ($windowStart, $windowEnd, $weeks) {
            $tutor = $template->relationLoaded('user') ? $template->user : $template->user()->first();

            if (! $tutor) {
                return;
            }

            for ($week = 0; $week <= $weeks; $week++) {
                $weekStart = $windowStart->addWeeks($week);
                $candidateDate = self::nextOrSameDay($weekStart, $template->day_of_week);

                if (! $candidateDate || $candidateDate->lt($windowStart) || $candidateDate->gt($windowEnd)) {
                    continue;
                }

                $startAt = $candidateDate->setTimeFromTimeString($template->start_time);

                $exists = ScheduleSession::query()
                    ->where('schedule_template_id', $template->id)
                    ->whereDate('start_at', $startAt->toDateString())
                    ->whereTime('start_at', $startAt->format('H:i:s'))
                    ->exists();

                if ($exists) {
                    continue;
                }

                ScheduleSession::create([
                    'schedule_template_id' => $template->id,
                    'user_id' => $template->user_id,
                    'package_id' => $template->package_id,
                    'title' => $template->title,
                    'category' => $template->category ?? '-',
                    'class_level' => $template->class_level,
                    'location' => $template->location,
                    'student_count' => $template->student_count,
                    'mentor_name' => $tutor->name,
                    'start_at' => $startAt,
                    'duration_minutes' => $template->duration_minutes ?? 90,
                    'is_highlight' => false,
                    'status' => 'scheduled',
                ]);
            }
        });
    }

    private static function nextOrSameDay(CarbonImmutable $date, int $isoDayOfWeek): ?CarbonImmutable
    {
        if ($isoDayOfWeek < 1 || $isoDayOfWeek > 7) {
            return null;
        }

        // Convert ISO (1-7) to Carbon (0-6)
        $carbonDayOfWeek = $isoDayOfWeek === 7 ? 0 : $isoDayOfWeek;

        if ($date->dayOfWeek === $carbonDayOfWeek) {
            return $date;
        }

        return $date->next($carbonDayOfWeek);
    }

    private static function ready(): bool
    {
        return Schema::hasTable('schedule_templates') && Schema::hasTable('schedule_sessions');
    }
}
