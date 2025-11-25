<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SubjectController extends BaseAdminController
{
    public function index(Request $request): View
    {
        if (! Schema::hasTable('subjects')) {
            return $this->render('admin.subjects.index', [
                'subjects' => collect(),
                'stats' => ['total' => 0, 'active' => 0, 'inactive' => 0],
                'filters' => ['level' => 'all', 'status' => 'all', 'query' => ''],
            ]);
        }

        $level = $request->input('level', 'all');
        $status = $request->input('status', 'all');
        $queryTerm = trim((string) $request->input('q', ''));

        $query = Subject::query();

        if ($queryTerm !== '') {
            $query->where('name', 'like', '%' . $queryTerm . '%');
        }

        if ($level !== 'all') {
            $query->where('level', $level);
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $subjects = $query
            ->orderBy('level')
            ->orderBy('name')
            ->get();

        $stats = $this->subjectStats();

        return $this->render('admin.subjects.index', [
            'subjects' => $subjects,
            'stats' => $stats,
            'filters' => [
                'level' => in_array($level, ['all', 'SD', 'SMP', 'SMA'], true) ? $level : 'all',
                'status' => in_array($status, ['all', 'active', 'inactive'], true) ? $status : 'all',
                'query' => $queryTerm,
            ],
        ]);
    }

    public function create(): View
    {
        return $this->render('admin.subjects.create', [
            'subject' => null,
            'levels' => $this->levelOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePayload($request);

        Subject::create($data);

        return redirect()
            ->route('admin.subjects.index')
            ->with('status', __('Mata pelajaran berhasil ditambahkan.'));
    }

    public function edit(Subject $subject): View
    {
        return $this->render('admin.subjects.edit', [
            'subject' => $subject,
            'levels' => $this->levelOptions(),
        ]);
    }

    public function update(Request $request, Subject $subject): RedirectResponse
    {
        $data = $this->validatePayload($request, $subject->id);

        $subject->update($data);

        return redirect()
            ->route('admin.subjects.index')
            ->with('status', __('Mata pelajaran berhasil diperbarui.'));
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        // Soft delete by setting is_active to false
        $subject->update(['is_active' => false]);

        return redirect()
            ->route('admin.subjects.index')
            ->with('status', __('Mata pelajaran berhasil dinonaktifkan.'));
    }

    private function validatePayload(Request $request, ?int $subjectId = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', Rule::in(['SD', 'SMP', 'SMA'])],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = array_key_exists('is_active', $validated)
            ? (bool) $validated['is_active']
            : true;

        return $validated;
    }

    private function levelOptions(): array
    {
        return [
            'SD' => 'Sekolah Dasar (SD)',
            'SMP' => 'Sekolah Menengah Pertama (SMP)',
            'SMA' => 'Sekolah Menengah Atas (SMA)',
        ];
    }

    private function subjectStats(): array
    {
        $base = Subject::query();

        return [
            'total' => (clone $base)->count(),
            'active' => (clone $base)->where('is_active', true)->count(),
            'inactive' => (clone $base)->where('is_active', false)->count(),
        ];
    }
}
