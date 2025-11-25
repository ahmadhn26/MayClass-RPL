<?php

namespace App\Http\Controllers\Tutor;

use App\Models\ScheduleSession;
use App\Models\ScheduleTemplate;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ScheduleController extends BaseTutorController
{
    public function index(): View
    {
        $tutor = Auth::user();
        $now = CarbonImmutable::now();

        $sessions = Schema::hasTable('schedule_sessions')
            ? ScheduleSession::query()
                ->with(['package:id,detail_title'])
                ->when($tutor, fn ($query) => $query->where('user_id', $tutor->id))
                ->orderBy('start_at')
                ->get()
            : collect();

        $formattedSessions = $sessions->map(fn (ScheduleSession $session) => $this->formatSession($session, $now));

        [$upcoming, $history] = $formattedSessions->partition(fn ($session) => $session['is_upcoming']);

        [$todaySessions, $futureSessions] = $upcoming->partition(function ($session) use ($now) {
            return $session['start_at'] && $session['start_at']->isSameDay($now);
        });

        return $this->render('tutor.schedule.index', [
            'metrics' => [
                'upcoming' => $upcoming->count(),
                'history' => $history->count(),
                'total' => $formattedSessions->count(),
            ],
            'todaySessions' => $todaySessions->values(),
            'futureSessions' => $futureSessions->values(),
            'historySessions' => $history->sortByDesc('start_at')->values(),
        ]);
    }

    private function formatSession(ScheduleSession $session, CarbonImmutable $now): array
    {
        $start = $this->parseDate($session->start_at ?? null);
        $duration = (int) ($session->duration_minutes ?? 90);
        $duration = $duration > 0 ? $duration : 90;
        $end = $start?->addMinutes($duration);

        $status = $this->normalizeStatus($session->status ?? null);
        $isCancelled = $status === 'cancelled';
        $isCompleted = $status === 'completed';

        $isUpcoming = ! $isCancelled && ! $isCompleted && $end && $end->greaterThanOrEqualTo($now);

        $statusLabel = match ($status) {
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Dijadwalkan',
        };

        $statusVariant = match ($status) {
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'info',
        };

        $participantSummary = $session->student_count
            ? $session->student_count . ' siswa terdaftar'
            : 'Peserta belum diatur';

        return [
            'id' => $session->id,
            'title' => $session->title,
            'subject' => $session->category ?? '-',
            'package' => optional($session->package)->detail_title
                ?? optional($session->package)->title
                ?? 'Paket MayClass',
            'location' => $session->location ?? 'Lokasi belum ditetapkan',
            'participant_summary' => $participantSummary,
            'status_label' => $statusLabel,
            'status_variant' => $statusVariant,
            'start_at' => $start,
            'end_at' => $end,
            'date_label' => $start ? $start->locale('id')->translatedFormat('dddd, D MMMM YYYY') : '-',
            'time_range' => $start && $end ? $start->format('H.i') . ' - ' . $end->format('H.i') . ' WIB' : '-',
            'is_upcoming' => $isUpcoming,
        ];
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

    private function normalizeStatus(?string $value): string
    {
        return match ($value) {
            'completed', 'done' => 'completed',
            'cancelled', 'canceled' => 'cancelled',
            default => 'scheduled',
        };
    }
}
