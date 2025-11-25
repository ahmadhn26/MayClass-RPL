<?php

namespace App\Http\Controllers\Tutor;

use App\Models\ScheduleSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ScheduleSessionController extends BaseTutorController
{
    public function cancel(ScheduleSession $session): RedirectResponse
    {
        $tutor = Auth::user();

        if ($session->user_id !== $tutor->id) {
            abort(403);
        }

        if (Schema::hasColumn('schedule_sessions', 'status')) {
            $session->forceFill([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'is_highlight' => false,
            ])->save();
        }

        return redirect()->route('tutor.schedule.index')
            ->with('status', __('Sesi berhasil dibatalkan.'));
    }

    public function restore(ScheduleSession $session): RedirectResponse
    {
        $tutor = Auth::user();

        if ($session->user_id !== $tutor->id) {
            abort(403);
        }

        if (Schema::hasColumn('schedule_sessions', 'status')) {
            $session->forceFill([
                'status' => 'scheduled',
                'cancelled_at' => null,
            ])->save();
        }

        return redirect()->route('tutor.schedule.index')
            ->with('status', __('Sesi dikembalikan ke jadwal.'));
    }
}
