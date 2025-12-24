<?php

namespace App\Observers;

use App\Jobs\SendScheduleEmailToUser;
use App\Models\ScheduleSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ScheduleSessionObserver
{
    /**
     * Fields that are relevant for triggering email updates.
     */
    private const RELEVANT_FIELDS = [
        'start_at',
        'duration_minutes',
        'user_id',
        'zoom_link',
        'package_id',
        'title',
        'mentor_name',
    ];

    /**
     * Handle the ScheduleSession "created" event.
     * Only sends email for sessions within 7 days from now.
     */
    public function created(ScheduleSession $session): void
    {
        Log::info('ScheduleSessionObserver::created triggered', [
            'session_id' => $session->id,
            'title' => $session->title,
            'start_at' => $session->start_at,
            'package_id' => $session->package_id,
        ]);

        // Check if session is within next 7 days
        if (!$this->isWithinSevenDays($session)) {
            Log::debug('ScheduleSessionObserver: Session not within 7 days, skipping', [
                'session_id' => $session->id,
            ]);
            return;
        }

        $this->sendEmailsToRecipients($session, 'schedule_created');
    }

    /**
     * Handle the ScheduleSession "updated" event.
     */
    public function updated(ScheduleSession $session): void
    {
        if (!$session->wasChanged(self::RELEVANT_FIELDS)) {
            return;
        }

        if ($session->status === 'cancelled') {
            return;
        }

        $this->sendEmailsToRecipients($session, 'schedule_updated');
    }

    /**
     * Check if session is within next 7 days
     */
    private function isWithinSevenDays(ScheduleSession $session): bool
    {
        if (!$session->start_at) {
            return false;
        }

        $sessionDate = Carbon::parse($session->start_at)->startOfDay();
        $today = Carbon::now()->startOfDay();
        $sevenDaysLater = Carbon::now()->addDays(7)->endOfDay();

        return $sessionDate->gte($today) && $sessionDate->lte($sevenDaysLater);
    }

    /**
     * Send emails directly (no queue) for shared hosting compatibility.
     */
    private function sendEmailsToRecipients(ScheduleSession $session, string $type): void
    {
        $recipients = $this->getRecipients($session);

        Log::info("ScheduleSessionObserver: Sending {$type} emails", [
            'session_id' => $session->id,
            'recipient_count' => $recipients->count(),
            'recipients' => $recipients->pluck('email')->toArray(),
        ]);

        if ($recipients->isEmpty()) {
            Log::warning('ScheduleSessionObserver: No recipients found', [
                'session_id' => $session->id,
                'package_id' => $session->package_id,
            ]);
            return;
        }

        foreach ($recipients as $user) {
            try {
                Log::info("ScheduleSessionObserver: Sending email to {$user->email}");

                // Send synchronously - no queue needed
                SendScheduleEmailToUser::dispatchSync($session->id, $user->id, $type);

                Log::info("ScheduleSessionObserver: Email sent successfully to {$user->email}");
            } catch (\Exception $e) {
                // Log error but don't break - continue sending to other recipients
                Log::error("ScheduleSessionObserver: Failed to send email to {$user->email}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }
    }

    /**
     * Get all recipients (enrolled students + tutor).
     */
    private function getRecipients(ScheduleSession $session)
    {
        $recipients = collect();

        // Get active students enrolled in this package
        if ($session->package_id) {
            $students = User::where('role', 'student')
                ->whereHas('enrollments', function ($q) use ($session) {
                    $q->where('package_id', $session->package_id)
                        ->where('is_active', true);
                })
                ->get();

            Log::debug('ScheduleSessionObserver: Found students', [
                'package_id' => $session->package_id,
                'count' => $students->count(),
            ]);

            $recipients = $recipients->merge($students);
        }

        // Add tutor
        if ($session->user_id) {
            $tutor = User::find($session->user_id);
            if ($tutor) {
                $recipients->push($tutor);
            }
        }

        return $recipients->unique('id');
    }
}
