<?php

namespace App\Jobs;

use App\Mail\ScheduleCreated;
use App\Mail\ScheduleReminder;
use App\Mail\ScheduleUpdated;
use App\Models\EmailLog;
use App\Models\ScheduleSession;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendScheduleEmailToUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Maximum number of attempts before marking as failed.
     */
    public int $tries = 3;

    /**
     * Backoff intervals in seconds between retries.
     */
    public array $backoff = [60, 300, 900];

    public function __construct(
        public int $sessionId,
        public int $userId,
        public string $type
    ) {
    }

    public function handle(): void
    {
        $session = ScheduleSession::with(['user', 'package'])->find($this->sessionId);
        $user = User::find($this->userId);

        if (!$session || !$user) {
            Log::warning('SendScheduleEmailToUser: Session or User not found', [
                'session_id' => $this->sessionId,
                'user_id' => $this->userId,
            ]);
            return;
        }

        // Skip if user has no email
        if (empty($user->email)) {
            Log::info('SendScheduleEmailToUser: User has no email', [
                'user_id' => $this->userId,
            ]);
            return;
        }

        // Idempotency: firstOrCreate the log entry
        $log = EmailLog::firstOrCreate(
            [
                'user_id' => $this->userId,
                'schedule_session_id' => $this->sessionId,
                'type' => $this->type,
            ],
            [
                'subject' => $this->getSubject($session),
                'status' => 'pending',
                'attempts' => 0,
            ]
        );

        // Skip if already successfully sent
        if ($log->status === 'sent') {
            Log::info('SendScheduleEmailToUser: Email already sent, skipping', [
                'log_id' => $log->id,
            ]);
            return;
        }

        // Update attempts
        $newAttempts = $log->attempts + 1;
        $log->update([
            'attempts' => $newAttempts,
            'last_attempt_at' => now(),
        ]);

        try {
            $mailable = $this->getMailable($session, $user);

            Mail::to($user->email)->send($mailable);

            $log->update([
                'status' => 'sent',
                'sent_at' => now(),
                'error_message' => null,
            ]);

            Log::info('SendScheduleEmailToUser: Email sent successfully', [
                'log_id' => $log->id,
                'user_id' => $this->userId,
                'type' => $this->type,
            ]);

        } catch (\Exception $e) {
            $errorMessage = Str::limit($e->getMessage(), 500);

            $log->update([
                'error_message' => $errorMessage,
                'status' => $newAttempts >= 3 ? 'failed' : 'pending',
            ]);

            Log::error('SendScheduleEmailToUser: Failed to send email', [
                'log_id' => $log->id,
                'user_id' => $this->userId,
                'error' => $errorMessage,
                'attempts' => $newAttempts,
            ]);

            // Rethrow so Laravel queue retry mechanism works
            throw $e;
        }
    }

    private function getMailable(ScheduleSession $session, User $user)
    {
        return match ($this->type) {
            'schedule_created' => new ScheduleCreated($session, $user),
            'schedule_updated' => new ScheduleUpdated($session, $user),
            'reminder_h1' => new ScheduleReminder($session, $user),
            default => throw new \InvalidArgumentException("Unknown email type: {$this->type}"),
        };
    }

    private function getSubject(ScheduleSession $session): string
    {
        $date = $session->start_at->translatedFormat('d M Y');

        return match ($this->type) {
            'schedule_created' => "📚 Jadwal Baru - {$session->title} ({$date})",
            'schedule_updated' => "📝 Jadwal Diperbarui - {$session->title} ({$date})",
            'reminder_h1' => "⏰ Reminder Besok - {$session->title} ({$date})",
            default => "Notifikasi Jadwal - {$session->title}",
        };
    }
}
