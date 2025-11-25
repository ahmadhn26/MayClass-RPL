<?php

namespace App\Http\Controllers\Admin;

use App\Models\Enrollment;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StudentController extends BaseAdminController
{
    public function index(): View
    {
        if (! Schema::hasTable('users')) {
            $students = collect();
        } else {
            $hasEnrollments = Schema::hasTable('enrollments');

            $query = User::query()->where('role', 'student')->orderBy('name');

            if ($hasEnrollments) {
                $query->with(['enrollments' => function ($relation) {
                    $relation->with('package')->orderByDesc('ends_at');
                }]);
            }

            $students = $query
                ->get()
                ->map(function (User $student) use ($hasEnrollments) {
                    $activeEnrollment = null;

                    if ($hasEnrollments) {
                        $activeEnrollment = $student->enrollments->firstWhere('is_active', true)
                            ?? $student->enrollments->first();
                    }

                    $endsAt = null;

                    if ($activeEnrollment && $activeEnrollment->ends_at) {
                        $endsAt = CarbonImmutable::parse($activeEnrollment->ends_at)
                            ->locale('id')
                            ->translatedFormat('d F Y');
                    }

                    return [
                        'id' => $student->id,
                        'name' => $student->name,
                        'email' => $student->email,
                        'student_id' => $student->student_id,
                        'package' => $activeEnrollment?->package?->detail_title,
                        'status' => $activeEnrollment?->is_active ? 'Aktif' : 'Tidak aktif',
                        'status_state' => $activeEnrollment?->is_active ? 'active' : 'inactive',
                        'ends_at' => $endsAt,
                    ];
                });
        }

        return $this->render('admin.students.index', [
            'students' => $students,
        ]);
    }

    public function show(User $student): View|RedirectResponse
    {
        if ($student->role !== 'student') {
            return redirect()->route('admin.students.index')->with('status', __('Pengguna tersebut bukan siswa.'));
        }

        $hasEnrollments = Schema::hasTable('enrollments');

        if ($hasEnrollments) {
            $student->loadMissing(['enrollments.package', 'enrollments.order']);
        }

        $timeline = $hasEnrollments
            ? $student->enrollments
                ->sortByDesc('ends_at')
                ->map(function (Enrollment $enrollment) {
                    $starts = $enrollment->starts_at ? CarbonImmutable::parse($enrollment->starts_at) : null;
                    $ends = $enrollment->ends_at ? CarbonImmutable::parse($enrollment->ends_at) : null;

                    return [
                        'package' => $enrollment->package?->detail_title ?? '-',
                        'status' => $enrollment->is_active ? 'Aktif' : 'Tidak aktif',
                        'status_state' => $enrollment->is_active ? 'active' : 'inactive',
                        'period' => $starts && $ends
                            ? $starts->locale('id')->translatedFormat('d M Y') . ' - ' . $ends->locale('id')->translatedFormat('d M Y')
                            : '-',
                        'invoice' => $enrollment->order?->id,
                        'total' => $enrollment->order ? 'Rp ' . number_format($enrollment->order->total, 0, ',', '.') : '-',
                    ];
                })
            : collect();

        $activeEnrollment = $hasEnrollments
            ? ($student->enrollments->firstWhere('is_active', true)
                ?? $student->enrollments->sortByDesc('ends_at')->first())
            : null;

        $summary = [
            'package' => $activeEnrollment?->package?->detail_title ?? __('Belum ada paket'),
            'status' => $activeEnrollment?->is_active ? 'Paket Aktif' : 'Tidak aktif',
            'status_state' => $activeEnrollment?->is_active ? 'active' : 'inactive',
            'expires' => $activeEnrollment?->ends_at
                ? CarbonImmutable::parse($activeEnrollment->ends_at)->locale('id')->translatedFormat('d F Y')
                : '-',
        ];

        return $this->render('admin.students.show', [
            'student' => $student,
            'summary' => $summary,
            'timeline' => $timeline,
        ]);
    }

    public function resetPassword(User $student): RedirectResponse
    {
        if ($student->role !== 'student') {
            return redirect()->route('admin.students.index')->with('status', __('Pengguna tersebut bukan siswa.'));
        }

        $newPassword = Str::random(12);

        $student->forceFill([
            'password' => Hash::make($newPassword),
        ])->save();

        return redirect()
            ->route('admin.students.show', $student)
            ->with('status', __('Kata sandi baru berhasil dibuat. Segera bagikan ke siswa melalui kanal resmi.'))
            ->with('generated_password', $newPassword);
    }
}
