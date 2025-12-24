<?php

namespace App\Console\Commands;

use App\Jobs\SendScheduleEmailToUser;
use App\Models\ScheduleSession;
use App\Models\User;
use Illuminate\Console\Command;

class SendScheduleReminders extends Command
{
    protected $signature = 'schedule:send-reminders';
    protected $description = 'Send H-1 schedule reminder emails to tutors and students';

    public function handle(): int
    {
        $tomorrow = now()->addDay()->toDateString();

        $sessions = ScheduleSession::whereDate('start_at', $tomorrow)
            ->where('status', '!=', 'cancelled')
            ->get();

        if ($sessions->isEmpty()) {
            $this->info('No sessions scheduled for tomorrow.');
            return Command::SUCCESS;
        }

        $this->info("Found {$sessions->count()} sessions for tomorrow ({$tomorrow}).");

        $totalRecipients = 0;

        foreach ($sessions as $session) {
            $recipients = $this->getRecipients($session);

            foreach ($recipients as $user) {
                SendScheduleEmailToUser::dispatch($session->id, $user->id, 'reminder_h1');
                $totalRecipients++;
            }

            $this->line("  - {$session->title}: {$recipients->count()} recipients");
        }

        $this->info("Dispatched {$totalRecipients} reminder emails.");

        return Command::SUCCESS;
    }

    private function getRecipients(ScheduleSession $session)
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

        return $recipients->unique('id');
    }
}
