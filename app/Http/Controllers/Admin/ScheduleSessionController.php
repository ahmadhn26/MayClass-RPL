<?php

namespace App\Http\Controllers\Admin;

use App\Models\ScheduleSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ScheduleSessionController extends BaseAdminController
{
    public function update(Request $request, ScheduleSession $session): RedirectResponse
    {
        $data = $request->validate([
            'start_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'user_id' => ['nullable', 'exists:users,id'],
        ]);

        $startAt = \Carbon\Carbon::parse($data['start_date'] . ' ' . $data['start_time']);

        $payload = [
            'start_at' => $startAt,
        ];

        if (isset($data['user_id']) && $data['user_id']) {
            $payload['user_id'] = $data['user_id'];
        }

        $session->update($payload);

        return $this->redirectToDashboard($request, $session->user_id, __('Jadwal berhasil diperbarui.'));
    }

    public function cancel(Request $request, ScheduleSession $session): RedirectResponse
    {
        if (Schema::hasColumn('schedule_sessions', 'status')) {
            $session->forceFill([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'is_highlight' => false,
            ])->save();
        }

        return $this->redirectToDashboard($request, $session->user_id, __('Sesi berhasil dibatalkan.'));
    }

    public function restore(Request $request, ScheduleSession $session): RedirectResponse
    {
        if (Schema::hasColumn('schedule_sessions', 'status')) {
            $session->forceFill([
                'status' => 'scheduled',
                'cancelled_at' => null,
            ])->save();
        }

        return $this->redirectToDashboard($request, $session->user_id, __('Sesi dikembalikan ke jadwal.'));
    }

    private function redirectToDashboard(Request $request, ?int $tutorId, string $message): RedirectResponse
    {
        $redirectTutorId = $request->input('redirect_tutor_id');
        $routeParameters = [];

        if ($redirectTutorId && $redirectTutorId !== 'all') {
            $routeParameters['tutor_id'] = $redirectTutorId;
        } elseif ($redirectTutorId !== 'all' && $tutorId) {
            $routeParameters['tutor_id'] = $tutorId;
        }

        return redirect()->route('admin.schedules.index', $routeParameters)
            ->with('status', $message);
    }
}
