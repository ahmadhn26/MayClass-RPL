<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Schedule Reminder Emails
|--------------------------------------------------------------------------
| Send H-1 (day before) schedule reminders every day at 07:00 Jakarta time.
| Make sure to set up cron job on server:
| * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
*/
Schedule::command('schedule:send-reminders')
    ->dailyAt('07:00')
    ->timezone('Asia/Jakarta')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/schedule-reminders.log'));

