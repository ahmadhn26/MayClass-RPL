<?php

namespace App\Http\Controllers\Admin;

use App\Models\Package;
use App\Models\ScheduleTemplate;
use App\Models\Subject;
use App\Models\User;
use App\Support\ScheduleTemplateGenerator;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ScheduleTemplateController extends BaseAdminController
{
    /**
     * Decode Base64 payload from request
     */
    private function decodeBase64Payload(Request $request): array
    {
        $payload = $request->input('payload');
        if (!$payload) {
            return [];
        }

        try {
            $decoded = base64_decode($payload);
            return json_decode($decoded, true) ?? [];
        } catch (\Exception $e) {
            \Log::error('Base64 decode failed: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Store schedule template - supports both regular POST and Base64 AJAX
     */
    public function store(Request $request)
    {
        if (!Schema::hasTable('schedule_templates')) {
            // Check if AJAX request
            if ($request->expectsJson() || $request->has('payload')) {
                return response()->json([
                    'response' => base64_encode(json_encode([
                        'status' => 'error',
                        'message' => 'Tabel template jadwal belum tersedia. Jalankan migrasi terbaru.'
                    ]))
                ], 400);
            }
            return redirect()->route('admin.schedules.index')
                ->with('alert', __('Tabel template jadwal belum tersedia. Jalankan migrasi terbaru.'));
        }

        // Check if this is a Base64 encoded AJAX request
        $isAjax = $request->expectsJson() || $request->has('payload');

        // Log incoming request for debugging
        \Log::info('ScheduleTemplateController::store - Request received', [
            'is_ajax' => $isAjax,
            'has_payload' => $request->has('payload'),
            'content_type' => $request->header('Content-Type'),
        ]);

        if ($isAjax && $request->has('payload')) {
            // Decode Base64 payload and merge into request
            $decodedData = $this->decodeBase64Payload($request);

            \Log::info('ScheduleTemplateController::store - Decoded payload', [
                'decoded_data' => $decodedData,
            ]);

            if (empty($decodedData)) {
                \Log::error('ScheduleTemplateController::store - Failed to decode payload');
                return response()->json([
                    'response' => base64_encode(json_encode([
                        'status' => 'error',
                        'message' => 'Gagal decode data. Pastikan format request benar.'
                    ]))
                ], 400);
            }

            $request->merge($decodedData);
        }

        try {
            $data = $this->validatedData($request);

            $template = ScheduleTemplate::create($data);

            // Try to generate sessions, but don't fail if it errors
            try {
                ScheduleTemplateGenerator::refreshTemplate($template);
            } catch (\Exception $sessionError) {
                \Log::error('Failed to generate sessions for template: ' . $sessionError->getMessage());
                // Continue anyway - template is created, sessions can be generated later
            }

            // Return JSON response for AJAX requests
            if ($isAjax) {
                return response()->json([
                    'response' => base64_encode(json_encode([
                        'status' => 'success',
                        'message' => 'Jadwal berhasil ditambahkan dan sesi telah dibuat',
                        'redirect' => route('admin.schedules.index', ['tutor_id' => $data['user_id']])
                    ]))
                ]);
            }

            // Use query parameter instead of session flash (more reliable)
            return redirect()->route('admin.schedules.index', [
                'tutor_id' => $data['user_id'],
                'success' => '1',
                'message' => 'Jadwal berhasil ditambahkan dan sesi telah dibuat'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();

            if ($isAjax) {
                return response()->json([
                    'response' => base64_encode(json_encode([
                        'status' => 'error',
                        'message' => 'Validasi gagal',
                        'errors' => $errors
                    ]))
                ], 422);
            }

            // Pass all validation errors directly to session
            return redirect()->route('admin.schedules.index', ['tutor_id' => $request->input('user_id')])
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Schedule creation failed: ' . $e->getMessage());

            if ($isAjax) {
                return response()->json([
                    'response' => base64_encode(json_encode([
                        'status' => 'error',
                        'message' => 'Error: ' . $e->getMessage()
                    ]))
                ], 500);
            }

            // Catch any other exceptions and display error message
            return redirect()->route('admin.schedules.index', ['tutor_id' => $request->input('user_id')])
                ->withErrors(['exception' => 'Error: ' . $e->getMessage()])
                ->withInput();
        }
    }


    public function update(Request $request, ScheduleTemplate $template): RedirectResponse
    {
        $data = $this->validatedData($request, $template);

        $template->update($data);

        ScheduleTemplateGenerator::refreshTemplate($template);

        return redirect()->route('admin.schedules.index', ['tutor_id' => $data['user_id']])
            ->with('status', __('Pola jadwal berhasil diperbarui.'));
    }

    public function destroy(Request $request, ScheduleTemplate $template): RedirectResponse
    {
        $tutorId = $template->user_id;

        ScheduleTemplateGenerator::removeTemplateSessions($template);

        $template->delete();

        $redirectTutorId = $request->input('redirect_tutor_id');
        $routeParameters = [];

        if ($redirectTutorId && $redirectTutorId !== 'all') {
            $routeParameters['tutor_id'] = $redirectTutorId;
        } elseif ($tutorId) {
            $routeParameters['tutor_id'] = $tutorId;
        }

        return redirect()->route('admin.schedules.index', $routeParameters)
            ->with('status', __('Pola jadwal dihapus dan sesi mendatang dibatalkan.'));
    }

    /**
     * Delete ALL schedule templates for a specific tutor.
     */
    public function destroyAll(Request $request): RedirectResponse
    {
        $userId = $request->input('user_id');
        $redirectTutorId = $request->input('redirect_tutor_id');

        if (!$userId) {
            return redirect()->route('admin.schedules.index')
                ->with('alert', 'User ID tidak ditemukan.');
        }

        // Get all templates for this tutor
        $templates = ScheduleTemplate::where('user_id', $userId)->get();
        $deletedCount = $templates->count();

        // Delete sessions and templates
        foreach ($templates as $template) {
            ScheduleTemplateGenerator::removeTemplateSessions($template);
            $template->delete();
        }

        \Log::info("Deleted {$deletedCount} schedule templates for user {$userId}");

        $routeParameters = [];
        if ($redirectTutorId && $redirectTutorId !== 'all') {
            $routeParameters['tutor_id'] = $redirectTutorId;
        }

        return redirect()->route('admin.schedules.index', $routeParameters)
            ->with('status', __("{$deletedCount} pola jadwal berhasil dihapus dan semua sesi mendatang dibatalkan."));
    }

    private function validatedData(Request $request, ?ScheduleTemplate $existing = null): array
    {
        $payload = $request->validate([
            'user_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn($query) => $query->where('role', 'tutor')),
            ],
            'package_id' => ['required', 'exists:packages,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:120'],
            'class_level' => ['nullable', 'string', 'max:120'],
            'location' => ['nullable', 'string', 'max:255'],
            'zoom_link' => ['nullable', 'url', 'max:500'],
            'reference_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'duration_minutes' => ['required', 'integer', 'min:30', 'max:240'],
            'student_count' => ['nullable', 'integer', 'min:1', 'max:200'],
        ]);


        $reference = CarbonImmutable::parse($payload['reference_date']);
        $dayOfWeek = $reference->dayOfWeek === 0 ? 7 : $reference->dayOfWeek;

        // Check tutor competency and package compatibility
        $this->validateSubjectCompatibility(
            $payload['user_id'],
            $payload['package_id'],
            $payload['subject_id']
        );

        // Check for overlapping schedules
        $this->validateNoOverlap(
            $payload['user_id'],
            $dayOfWeek,
            $payload['start_time'],
            $payload['duration_minutes'],
            $existing?->id
        );

        $payload['day_of_week'] = $dayOfWeek;
        $payload['is_active'] = true;

        unset($payload['reference_date']);

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
            ->when($excludeId, fn($query) => $query->where('id', '!=', $excludeId))
            ->get()
            ->filter(function (ScheduleTemplate $template) use ($newStartMinutes, $newEndMinutes) {
                [$hours, $minutes] = explode(':', $template->start_time);
                $existingStartMinutes = ($hours * 60) + $minutes;
                $existingEndMinutes = $existingStartMinutes + $template->duration_minutes;

                return $newStartMinutes < $existingEndMinutes && $existingStartMinutes < $newEndMinutes;
            });

        if ($overlapping->isNotEmpty()) {
            $conflictInfo = $overlapping->map(function ($t) {
                return "{$t->title} ({$t->start_time}, {$t->duration_minutes} menit)";
            })->join(', ');

            throw ValidationException::withMessages([
                'schedule' => "Jadwal bertumpang tindih dengan jadwal yang sudah ada: {$conflictInfo}. Silakan pilih waktu yang berbeda."
            ]);
        }
    }

    private function validateSubjectCompatibility(int $userId, int $packageId, int $subjectId): void
    {
        $user = User::find($userId);
        $package = Package::find($packageId);
        $subject = Subject::find($subjectId);

        // Check if tutor is assigned to this package
        if (!$user->packages()->where('package_id', $packageId)->exists()) {
            throw ValidationException::withMessages([
                'package_id' => "Tutor {$user->name} tidak ditugaskan untuk mengajar paket {$package->detail_title}. Tambahkan tutor ke paket ini terlebih dahulu."
            ]);
        }

        // Check if tutor can teach this subject
        if (!$user->subjects()->where('subject_id', $subjectId)->exists()) {
            throw ValidationException::withMessages([
                'subject_id' => "Tutor {$user->name} belum memiliki kompetensi mengajar mata pelajaran {$subject->name}. Tambahkan kompetensi di profil tutor."
            ]);
        }

        // Check if package includes this subject
        if (!$package->subjects()->where('subject_id', $subjectId)->exists()) {
            throw ValidationException::withMessages([
                'subject_id' => "Paket {$package->detail_title} tidak mencakup mata pelajaran {$subject->name}. Periksa konfigurasi paket."
            ]);
        }
    }
}
