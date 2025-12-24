<?php

namespace App\Observers;

use App\Jobs\SendScheduleEmailToUser;
use App\Models\ScheduleSession;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ScheduleSessionObserver
{
    /**
     * Fields that are relevant for triggering email updates.
     * Only changes to these fields will trigger a schedule_updated email.
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
     */
    public function created(ScheduleSession $session): void
    {
        $this->dispatchToRecipients($session, 'schedule_created');
    }

    /**
     * Handle the ScheduleSession "updated" event.
     * Only triggers if relevant fields were changed.
     */
    public function updated(ScheduleSession $session): void
    {
        // Only trigger if relevant fields were changed
        if (!$session->wasChanged(self::RELEVANT_FIELDS)) {
            Log::debug('ScheduleSessionObserver: No relevant fields changed, skipping email', [
                'session_id' => $session->id,
                'dirty' => $session->getDirty(),
            ]);
            return;
        }

        // Don't send update email for cancelled sessions
        if ($session->status === 'cancelled') {
            return;
        }

        $this->dispatchToRecipients($session, 'schedule_updated');
    }

    /**
     * Dispatch email jobs to all relevant recipients.
     */
    private function dispatchToRecipients(ScheduleSession $session, string $type): void
    {
        $recipients = $this->getUniqueRecipients($session);

        Log::info("ScheduleSessionObserver: Dispatching {$type} emails", [
            'session_id' => $session->id,
            'recipient_count' => $recipients->count(),
        ]);

        foreach ($recipients as $user) {
            SendScheduleEmailToUser::dispatch($session->id, $user->id, $type)
                ->afterCommit();
        }
    }

    /**
     * Get unique recipients (students enrolled in package + tutor).
     */
    private function getUniqueRecipients(ScheduleSession $session)
    {
        $recipients = collect();

        // Get students enrolled in the package
        if ($session->package_id) {
            $students = User::where('role', 'student')
                ->whereHas('enrollments', function ($query) use ($session) {
                    $query->where('package_id', $session->package_id)
                        ->where('is_active', true);
                })
                ->get();

            $recipients = $recipients->merge($students);
        }

        // Add tutor
        if ($session->user_id) {
            $tutor = User::find($session->user_id);
            if ($tutor) {
                $recipients->push($tutor);
            }
        }

        // Return unique recipients by ID
        return $recipients->unique('id');
    }
}
