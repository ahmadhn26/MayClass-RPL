<?php

namespace App\Http\Controllers\Tutor;

use App\Models\Package;
use App\Models\ScheduleTemplate;
use App\Models\Subject;
use App\Models\User;
use App\Support\ScheduleTemplateGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class ScheduleTemplateController extends BaseTutorController
{
    public function store(Request $request): RedirectResponse
    {
        if (! Schema::hasTable('schedule_templates')) {
            return redirect()->route('tutor.schedule.index')
                ->with('alert', __('Tabel jadwal belum siap. Jalankan migrasi terbaru.'));
        }

        $tutor = Auth::user();

        $data = $this->validatedData($request, $tutor->id);
        $data['user_id'] = $tutor->id;

        $template = ScheduleTemplate::create($data);

        ScheduleTemplateGenerator::refreshTemplate($template);

        return redirect()->route('tutor.schedule.index')
            ->with('status', __('Pola jadwal berhasil ditambahkan.'));
    }

    public function update(Request $request, ScheduleTemplate $template): RedirectResponse
    {
        $tutor = Auth::user();

        if ($template->user_id !== $tutor->id) {
            abort(403);
        }

        $data = $this->validatedData($request, $tutor->id, $template);

        $template->update($data);

        ScheduleTemplateGenerator::refreshTemplate($template);

        return redirect()->route('tutor.schedule.index')
            ->with('status', __('Pola jadwal berhasil diperbarui.'));
    }

    public function destroy(ScheduleTemplate $template): RedirectResponse
    {
        $tutor = Auth::user();

        if ($template->user_id !== $tutor->id) {
            abort(403);
        }

        ScheduleTemplateGenerator::removeTemplateSessions($template);
        $template->delete();

        return redirect()->route('tutor.schedule.index')
            ->with('status', __('Pola jadwal dihapus dan pertemuan mendatang dibatalkan.'));
    }

    private function validatedData(Request $request, int $userId, ?ScheduleTemplate $existing = null): array
    {
        $payload = $request->validate([
            'package_id' => ['required', 'exists:packages,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:120'],
            'class_level' => ['nullable', 'string', 'max:120'],
            'location' => ['nullable', 'string', 'max:255'],
            'day_of_week' => ['required', 'integer', 'between:1,7'],
            'start_time' => ['required', 'date_format:H:i'],
            'duration_minutes' => ['required', 'integer', 'min:30', 'max:240'],
            'student_count' => ['nullable', 'integer', 'min:1', 'max:200'],
        ]);

        // Check tutor competency and package compatibility
        $this->validateSubjectCompatibility(
            $userId,
            $payload['package_id'],
            $payload['subject_id']
        );

        // Check for overlapping schedules
        $this->validateNoOverlap(
            $userId,
            $payload['day_of_week'],
            $payload['start_time'],
            $payload['duration_minutes'],
            $existing?->id
        );

        $payload['is_active'] = true;

        return $payload;
    }

    private function validateNoOverlap(int $userId, int $dayOfWeek, string $startTime, int $durationMinutes, ?int $excludeId = null): void
    {
        // Convert start time to minutes since midnight
        [$hours, $minutes] = explode(':', $startTime);
        $newStartMinutes = ($hours * 60) + $minutes;
        $newEndMinutes = $newStartMinutes + $durationMinutes;

        $overlapping = ScheduleTemplate::query()
            ->where('user_id', $userId)
            ->where('day_of_week', $dayOfWeek)
            ->when($excludeId, fn ($query) => $query->where('id', '!=', $excludeId))
            ->get()
            ->filter(function (ScheduleTemplate $template) use ($newStartMinutes, $newEndMinutes) {
                [$hours, $minutes] = explode(':', $template->start_time);
                $existingStartMinutes = ($hours * 60) + $minutes;
                $existingEndMinutes = $existingStartMinutes + $template->duration_minutes;

                return $newStartMinutes < $existingEndMinutes && $existingStartMinutes < $newEndMinutes;
            });

        if ($overlapping->isNotEmpty()) {
            $conflictTitles = $overlapping->pluck('title')->join(', ');
            throw new ValidationException(
                validator([], [], [], [], [
                    'schedule' => "Jadwal bertumpang tindih dengan: {$conflictTitles}"
                ])
            );
        }
    }

    private function validateSubjectCompatibility(int $userId, int $packageId, int $subjectId): void
    {
        $user = User::find($userId);
        $package = Package::find($packageId);
        $subject = Subject::find($subjectId);

        // Check if tutor can teach this subject
        if (!$user->subjects()->where('subject_id', $subjectId)->exists()) {
            throw new ValidationException(
                validator([], [], [], [], [
                    'subject_id' => "Anda tidak kompeten mengajar mata pelajaran {$subject->name}"
                ])
            );
        }

        // Check if package includes this subject
        if (!$package->subjects()->where('subject_id', $subjectId)->exists()) {
            throw new ValidationException(
                validator([], [], [], [], [
                    'subject_id' => "Paket {$package->detail_title} tidak include mata pelajaran {$subject->name}"
                ])
            );
        }
    }
}
