<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MaterialChapter;
use App\Models\MaterialObjective;
use App\Models\Package;
use App\Support\UnsplashPlaceholder;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MaterialController extends BaseTutorController
{
    public function index(Request $request)
    {
        $search = (string) $request->input('q', '');

        $tableReady = Schema::hasTable('materials');

        $materials = $tableReady
            ? Material::query()
                ->with('subject')
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($inner) use ($search) {
                        $inner->where('title', 'like', "%{$search}%")
                            ->orWhereHas('subject', function ($q) use ($search) {
                                $q->where('name', 'like', "%{$search}%");
                            })
                            ->orWhere('level', 'like', "%{$search}%");
                    });
                })
                ->orderByDesc('created_at')
                ->get()
            : collect();

        return $this->render('tutor.materials.index', [
            'materials' => $materials,
            'search' => $search,
            'tableReady' => $tableReady,
        ]);
    }

    public function create()
    {
        $packages = Schema::hasTable('packages')
            ? Package::orderBy('level')->orderBy('price')->get()
            : collect();

        return $this->render('tutor.materials.create', [
            'packages' => $packages,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (! Schema::hasTable('materials')) {
            return redirect()
                ->route('tutor.materials.index')
                ->with('alert', __('Tabel materi belum siap. Jalankan migrasi database terlebih dahulu.'));
        }

        if (! Schema::hasTable('packages')) {
            return redirect()
                ->route('tutor.materials.index')
                ->with('alert', __('Tabel paket belum siap. Pastikan migrasi paket sudah dijalankan.'));
        }

        $data = $request->validate([
            'package_id' => ['required', 'exists:packages,id'],
            'title' => ['required', 'string', 'max:255'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'level' => ['required', 'string', 'max:120'],
            'summary' => ['required', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,ppt,pptx,doc,docx', 'max:10240'],
            'objectives' => ['nullable', 'array'],
            'objectives.*' => ['nullable', 'string', 'max:255'],
            'chapters' => ['nullable', 'array'],
            'chapters.*.title' => ['nullable', 'string', 'max:255'],
            'chapters.*.description' => ['nullable', 'string'],
        ]);

        $slug = Str::slug($data['title']) ?: 'materi-' . Str::random(6);
        $uniqueSlug = $slug;
        $counter = 1;
        while (Material::where('slug', $uniqueSlug)->exists()) {
            $uniqueSlug = $slug . '-' . $counter++;
        }

        $path = null;
        if ($request->file('attachment')) {
            $path = $request->file('attachment')->store('materials', 'public');
        }

        DB::transaction(function () use ($data, $path, $uniqueSlug, $request) {
            $material = Material::create([
                'slug' => $uniqueSlug,
                'package_id' => $data['package_id'],
                'subject_id' => $data['subject_id'],
                'title' => $data['title'],
                'level' => $data['level'],
                'summary' => $data['summary'],
                'thumbnail_url' => UnsplashPlaceholder::material(\App\Models\Subject::find($data['subject_id'])->name ?? 'Material'),
                'resource_path' => $path,
            ]);

            $this->syncObjectives($material, $request->input('objectives', []));
            $this->syncChapters($material, $request->input('chapters', []));
        });

        return redirect()
            ->route('tutor.materials.index')
            ->with('status', __('Materi baru berhasil disimpan.'));
    }

    public function edit(Material $material)
    {
        $material->load(['objectives', 'chapters']);

        $packages = Schema::hasTable('packages')
            ? Package::orderBy('level')->orderBy('price')->get()
            : collect();

        return $this->render('tutor.materials.edit', [
            'material' => $material,
            'packages' => $packages,
        ]);
    }

    public function update(Request $request, Material $material): RedirectResponse
    {
        if (! Schema::hasTable('materials')) {
            return redirect()
                ->route('tutor.materials.index')
                ->with('alert', __('Tabel materi belum siap. Jalankan migrasi database terlebih dahulu.'));
        }

        if (! Schema::hasTable('packages')) {
            return redirect()
                ->route('tutor.materials.index')
                ->with('alert', __('Tabel paket belum siap. Pastikan migrasi paket sudah dijalankan.'));
        }

        $data = $request->validate([
            'package_id' => ['required', 'exists:packages,id'],
            'title' => ['required', 'string', 'max:255'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'level' => ['required', 'string', 'max:120'],
            'summary' => ['required', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,ppt,pptx,doc,docx', 'max:10240'],
            'objectives' => ['nullable', 'array'],
            'objectives.*' => ['nullable', 'string', 'max:255'],
            'chapters' => ['nullable', 'array'],
            'chapters.*.title' => ['nullable', 'string', 'max:255'],
            'chapters.*.description' => ['nullable', 'string'],
        ]);

        $payload = [
            'package_id' => $data['package_id'],
            'subject_id' => $data['subject_id'],
            'title' => $data['title'],
            'level' => $data['level'],
            'summary' => $data['summary'],
        ];

        if (! $material->resource_path || $request->hasFile('attachment')) {
            if ($material->resource_path && ! str_starts_with($material->resource_path, 'http')) {
                Storage::disk('public')->delete($material->resource_path);
            }

            if ($request->file('attachment')) {
                $payload['resource_path'] = $request->file('attachment')->store('materials', 'public');
            } else {
                $payload['resource_path'] = null;
            }
        }

        if ($material->subject_id !== $data['subject_id']) {
            $payload['thumbnail_url'] = UnsplashPlaceholder::material(\App\Models\Subject::find($data['subject_id'])->name ?? 'Material');
        }

        DB::transaction(function () use ($material, $payload, $request) {
            $material->update($payload);

            $material->objectives()->delete();
            $material->chapters()->delete();

            $this->syncObjectives($material, $request->input('objectives', []));
            $this->syncChapters($material, $request->input('chapters', []));
        });

        return redirect()
            ->route('tutor.materials.index')
            ->with('status', __('Materi berhasil diperbarui.'));
    }

    public function preview(Material $material)
    {
        return $this->serveAttachment($material, false);
    }

    public function download(Material $material): StreamedResponse|RedirectResponse
    {
        return $this->serveAttachment($material, true);
    }

    private function syncObjectives(Material $material, array $objectives): void
    {
        $payloads = collect($objectives)
            ->map(fn ($value) => trim((string) $value))
            ->filter()
            ->values()
            ->map(fn ($description, $index) => [
                'description' => $description,
                'position' => $index + 1,
            ]);

        if ($payloads->isEmpty()) {
            return;
        }

        $payloads->each(fn ($attributes) => $material->objectives()->create($attributes));
    }

    private function syncChapters(Material $material, array $chapters): void
    {
        $payloads = collect($chapters)
            ->map(function ($chapter) {
                return [
                    'title' => trim((string) ($chapter['title'] ?? '')),
                    'description' => trim((string) ($chapter['description'] ?? '')),
                ];
            })
            ->filter(fn ($chapter) => $chapter['title'] !== '' || $chapter['description'] !== '')
            ->values()
            ->map(function ($chapter, $index) {
                return [
                    'title' => $chapter['title'] !== ''
                        ? $chapter['title']
                        : __('Bab :number', ['number' => $index + 1]),
                    'description' => $chapter['description'] !== '' ? $chapter['description'] : null,
                    'position' => $index + 1,
                ];
            });

        if ($payloads->isEmpty()) {
            return;
        }

        $payloads->each(fn ($attributes) => $material->chapters()->create($attributes));
    }

    private function serveAttachment(Material $material, bool $download)
    {
        $path = $material->resource_path;

        if (! $path) {
            return redirect()->route('tutor.materials.index')
                ->with('alert', __('Tidak ada lampiran untuk materi ini.'));
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return redirect()->away($path);
        }

        if (! Storage::disk('public')->exists($path)) {
            return redirect()->route('tutor.materials.index')
                ->with('alert', __('Berkas lampiran tidak ditemukan di penyimpanan.'));
        }

        $filename = Str::slug($material->title) . '.' . strtolower(pathinfo($path, PATHINFO_EXTENSION) ?: 'pdf');

        return $download
            ? Storage::disk('public')->download($path, $filename)
            : Storage::disk('public')->response($path, $filename);
    }

    public function getPackageSubjects(Package $package): \Illuminate\Http\JsonResponse
    {
        $subjects = $package->subjects()->select('subjects.id', 'subjects.name', 'subjects.level')->get();
        return response()->json($subjects);
    }
}
