<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Package;
use App\Support\UnsplashPlaceholder;
use Illuminate\Support\Facades\Auth;
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
                ->with(['subject', 'materialItems']) // Eager load material items
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

        // AMBIL DATA PAKET UNTUK DROPDOWN MODAL
        $packages = Schema::hasTable('packages')
            ? Auth::user()->packages()->orderBy('level')->orderBy('price')->get()
            : collect();

        return $this->render('tutor.materials.index', [
            'materials' => $materials,
            'search' => $search,
            'tableReady' => $tableReady,
            'packages' => $packages, // Dikirim ke view untuk modal
        ]);
    }

    // Method create() bisa dihapus atau dibiarkan (tidak terpakai lagi karena pakai modal)
    public function create()
    {
        $packages = Schema::hasTable('packages')
            ? Auth::user()->packages()->orderBy('level')->orderBy('price')->get()
            : collect();

        return $this->render('tutor.materials.create', [
            'packages' => $packages,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!Schema::hasTable('materials')) {
            return redirect()
                ->route('tutor.materials.index')
                ->with('alert', __('Tabel materi belum siap. Jalankan migrasi database terlebih dahulu.'));
        }

        if (!Schema::hasTable('packages')) {
            return redirect()
                ->route('tutor.materials.index')
                ->with('alert', __('Tabel paket belum siap. Pastikan migrasi paket sudah dijalankan.'));
        }

        $data = $request->validate([
            'package_ids' => ['required', 'array', 'min:1'],
            'package_ids.*' => ['required', 'exists:packages,id'],
            'title' => ['required', 'string', 'max:255'], // Folder name
            'subject_id' => ['required', 'exists:subjects,id'],
            'level' => ['required', 'string', 'max:120'],
            'summary' => ['required', 'string'], // Folder description
            'material_items' => ['required', 'array', 'min:1'], // Array of items in folder
            'material_items.*.name' => ['required', 'string', 'max:255'],
            'material_items.*.description' => ['required', 'string'],
            'material_items.*.link' => ['required', 'url', 'max:500'],
        ]);

        $slug = Str::slug($data['title']) ?: 'folder-' . Str::random(6);
        $uniqueSlug = $slug;
        $counter = 1;
        while (Material::where('slug', $uniqueSlug)->exists()) {
            $uniqueSlug = $slug . '-' . $counter++;
        }

        DB::transaction(function () use ($data, $uniqueSlug) {
            // Create the folder (material)
            $material = Material::create([
                'slug' => $uniqueSlug,
                'subject_id' => $data['subject_id'],
                'title' => $data['title'],
                'level' => $data['level'],
                'summary' => $data['summary'],
                'thumbnail_url' => UnsplashPlaceholder::material(\App\Models\Subject::find($data['subject_id'])->name ?? 'Material'),
                'resource_path' => [], // No longer used for folder system
                'quiz_urls' => [], // No longer used for folder system
            ]);

            // Sync packages to pivot table
            $material->packages()->sync($data['package_ids']);

            // Create material items within the folder
            foreach ($data['material_items'] as $index => $item) {
                $material->materialItems()->create([
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'link' => $item['link'],
                    'position' => $index,
                ]);
            }
        });

        return redirect()
            ->route('tutor.materials.index')
            ->with('status', __('Folder materi berhasil disimpan.'));
    }

    public function edit(Material $material)
    {
        $material->load(['materialItems', 'packages']);

        $packages = Schema::hasTable('packages')
            ? Auth::user()->packages()->orderBy('level')->orderBy('price')->get()
            : collect();

        return $this->render('tutor.materials.edit', [
            'material' => $material,
            'packages' => $packages,
            'selectedPackageIds' => $material->packages->pluck('id')->toArray(),
        ]);
    }

    public function update(Request $request, Material $material): RedirectResponse
    {
        if (!Schema::hasTable('materials')) {
            return redirect()
                ->route('tutor.materials.index')
                ->with('alert', __('Tabel materi belum siap. Jalankan migrasi database terlebih dahulu.'));
        }

        if (!Schema::hasTable('packages')) {
            return redirect()
                ->route('tutor.materials.index')
                ->with('alert', __('Tabel paket belum siap. Pastikan migrasi paket sudah dijalankan.'));
        }

        $data = $request->validate([
            'package_ids' => ['required', 'array', 'min:1'],
            'package_ids.*' => ['required', 'exists:packages,id'],
            'title' => ['required', 'string', 'max:255'], // Folder name
            'subject_id' => ['required', 'exists:subjects,id'],
            'level' => ['required', 'string', 'max:120'],
            'summary' => ['required', 'string'], // Folder description
            'material_items' => ['required', 'array', 'min:1'], // Array of items in folder
            'material_items.*.name' => ['required', 'string', 'max:255'],
            'material_items.*.description' => ['required', 'string'],
            'material_items.*.link' => ['required', 'url', 'max:500'],
        ]);

        $payload = [
            'subject_id' => $data['subject_id'],
            'title' => $data['title'],
            'level' => $data['level'],
            'summary' => $data['summary'],
        ];

        $payload['resource_path'] = []; // No longer used
        $payload['quiz_urls'] = []; // No longer used

        if ($material->subject_id !== $data['subject_id']) {
            $payload['thumbnail_url'] = UnsplashPlaceholder::material(\App\Models\Subject::find($data['subject_id'])->name ?? 'Material');
        }

        DB::transaction(function () use ($material, $payload, $data) {
            $material->update($payload);

            // Sync packages
            $material->packages()->sync($data['package_ids']);

            // Delete old material items and create new ones
            $material->materialItems()->delete();

            // Create new material items
            foreach ($data['material_items'] as $index => $item) {
                $material->materialItems()->create([
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'link' => $item['link'],
                    'position' => $index,
                ]);
            }
        });

        return redirect()
            ->route('tutor.materials.index')
            ->with('status', __('Folder materi berhasil diperbarui.'));
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
            ->map(fn($value) => trim((string) $value))
            ->filter()
            ->values()
            ->map(fn($description, $index) => [
                'description' => $description,
                'position' => $index + 1,
            ]);

        if ($payloads->isEmpty()) {
            return;
        }

        $payloads->each(fn($attributes) => $material->objectives()->create($attributes));
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
            ->filter(fn($chapter) => $chapter['title'] !== '' || $chapter['description'] !== '')
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

        $payloads->each(fn($attributes) => $material->chapters()->create($attributes));
    }

    public function destroy(Material $material): RedirectResponse
    {
        // Security check: ensure material belongs to one of user's packages
        $userPackageIds = Auth::user()->packages()->pluck('packages.id');
        
        $hasAccess = DB::table('material_package')
            ->where('material_id', $material->id)
            ->whereIn('package_id', $userPackageIds)
            ->exists();

        // Alternative check: if the material has no packages, maybe we should check if created by user? 
        // But current schema revolves around packages. 
        // If strict ownership is needed:
        // if (!$hasAccess) abort(403); 
        
        // For now, allow delete if connected to user packages, or just proceed if we trust the route model binding + middleware.
        // Let's implement a basic check.
        if (!$hasAccess) {
             return redirect()
                ->route('tutor.materials.index')
                ->with('error', __('Anda tidak memiliki akses untuk menghapus materi ini.'));
        }

        try {
            DB::transaction(function () use ($material) {
                // Detach packages
                $material->packages()->detach();
                // Delete items
                $material->materialItems()->delete();
                // Delete objectives/chapters if any (though currently unused in folder mode, best to clean up)
                $material->objectives()->delete();
                $material->chapters()->delete();
                
                // Delete material
                $material->delete();
            });

            return redirect()
                ->route('tutor.materials.index')
                ->with('status', __('Materi berhasil dihapus.'));
        } catch (\Exception $e) {
            return redirect()
                ->route('tutor.materials.index')
                ->with('error', __('Gagal menghapus materi: ' . $e->getMessage()));
        }
    }



    public function getPackageSubjects(Package $package): \Illuminate\Http\JsonResponse
    {
        $subjects = $package->subjects()->select('subjects.id', 'subjects.name', 'subjects.level')->get();
        return response()->json($subjects);
    }
}