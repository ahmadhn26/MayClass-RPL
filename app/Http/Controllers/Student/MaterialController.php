<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Support\StudentAccess;
use App\Support\SubjectPalette;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $materialsLink = (string) config('mayclass.links.materials_drive');
        $quizLink = (string) config('mayclass.links.quiz_platform');

        $package = $this->currentPackage();

        $materialsReady = Schema::hasTable('materials');
        $chaptersReady = Schema::hasTable('material_chapters');
        $objectivesReady = Schema::hasTable('material_objectives');

        if (! $package || ! $materialsReady) {
            return view('student.materials.index', [
                'page' => 'materials',
                'title' => 'Materi Pembelajaran',
                'activePackage' => $package,
                'collections' => collect(),
                'stats' => [
                    'total' => 0,
                    'subjects' => 0,
                    'levels' => [],
                ],
                'materialsLink' => $materialsLink,
                'quizLink' => $quizLink,
            ]);
        }

        $materials = Material::query()
            ->where('package_id', optional($package)->id)
            ->with('subject')
            ->when($objectivesReady, fn ($query) => $query->withCount('objectives'))
            ->when($chaptersReady, fn ($query) => $query->withCount('chapters'))
            ->orderBy('subject_id')
            ->orderBy('title')
            ->get();

        $collections = $materials
            ->groupBy(fn($material) => $material->subject->name ?? 'Tanpa Mapel')
            ->map(function ($items, $subject) use ($chaptersReady, $objectivesReady) {
                return [
                    'label' => $subject,
                    'accent' => SubjectPalette::accent($subject),
                    'items' => $items->map(function ($material) use ($chaptersReady, $objectivesReady) {
                        $resource = $this->resourceEndpoints($material);

                        return [
                            'slug' => $material->slug,
                            'level' => $material->level,
                            'title' => $material->title,
                            'summary' => $material->summary,
                            'view_url' => $resource['view'],
                            'download_url' => $resource['download'],
                            'chapter_count' => $chaptersReady ? (int) $material->chapters_count : 0,
                            'objective_count' => $objectivesReady ? (int) $material->objectives_count : 0,
                        ];
                    })->values()->all(),
                ];
            })
            ->values();

        $stats = [
            'total' => $materials->count(),
            'subjects' => $collections->count(),
            'levels' => $materials->pluck('level')->filter()->unique()->values()->all(),
        ];

        return view('student.materials.index', [
            'page' => 'materials',
            'title' => 'Materi Pembelajaran',
            'activePackage' => $package,
            'collections' => $collections,
            'stats' => $stats,
            'materialsLink' => $materialsLink,
            'quizLink' => $quizLink,
        ]);
    }

    public function show(string $slug): View
    {
        if (! Schema::hasTable('materials')) {
            abort(404);
        }

        $objectivesReady = Schema::hasTable('material_objectives');
        $chaptersReady = Schema::hasTable('material_chapters');

        $package = $this->currentPackage(true);

        $material = Material::query()
            ->where('slug', $slug)
            ->where('package_id', optional($package)->id)
            ->with('subject')
            ->when($objectivesReady, fn ($query) => $query->with('objectives'))
            ->when($chaptersReady, fn ($query) => $query->with('chapters'))
            ->firstOrFail();

        $resource = $this->resourceEndpoints($material);

        $chapters = $chaptersReady
            ? $material->chapters->map(fn ($chapter) => [
                'title' => $chapter->title,
                'description' => $chapter->description,
            ])->values()->all()
            : [];

        $objectives = $objectivesReady
            ? $material->objectives->pluck('description')->filter()->values()->all()
            : [];

        return view('student.materials.show', [
            'page' => 'materials',
            'title' => $material->title,
            'material' => [
                'subject' => $material->subject->name ?? 'Tanpa Mapel',
                'level' => $material->level,
                'title' => $material->title,
                'summary' => $material->summary,
                'thumbnail' => $material->thumbnail_asset,
                'objectives' => $objectives,
                'chapters' => $chapters,
                'view_url' => $resource['view'],
                'download_url' => $resource['download'],
                'download_label' => $resource['label'],
            ],
            'materialsLink' => $this->materialsLink(),
        ]);
    }

    public function open(string $slug)
    {
        $material = $this->resolveMaterialForAccess($slug);

        $path = $material->resource_path;

        if (! $path) {
            return redirect()->away($this->materialsLink());
        }

        if ($this->isExternalPath($path)) {
            return redirect()->away($path);
        }

        if (! Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return Storage::disk('public')->response($path, $this->downloadFilename($material, $path));
    }

    public function download(string $slug): StreamedResponse|RedirectResponse
    {
        $material = $this->resolveMaterialForAccess($slug);

        $path = $material->resource_path;

        if (! $path) {
            return redirect()->away($this->materialsLink());
        }

        if ($this->isExternalPath($path)) {
            return redirect()->away($path);
        }

        if (! Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return Storage::disk('public')->download($path, $this->downloadFilename($material, $path));
    }

    private function materialsLink(): string
    {
        return (string) config('mayclass.links.materials_drive');
    }

    private function currentPackage(bool $required = false)
    {
        $enrollment = StudentAccess::activeEnrollment(Auth::user());

        if (! $enrollment || ! $enrollment->package) {
            if ($required) {
                abort(403);
            }

            return null;
        }

        return $enrollment->package;
    }

    private function resolveMaterialForAccess(string $slug): Material
    {
        if (! Schema::hasTable('materials')) {
            abort(404);
        }

        $package = $this->currentPackage(true);

        return Material::query()
            ->where('slug', $slug)
            ->where('package_id', $package->id)
            ->firstOrFail();
    }

    private function resourceEndpoints(Material $material): array
    {
        $path = $material->resource_path;

        if (! $path) {
            $fallback = $this->materialsLink();

            return [
                'view' => $fallback,
                'download' => $fallback,
                'label' => 'PDF',
            ];
        }

        if ($this->isExternalPath($path)) {
            $label = strtoupper(pathinfo(parse_url($path, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION)) ?: 'PDF';

            return [
                'view' => $path,
                'download' => $path,
                'label' => $label,
            ];
        }

        $label = strtoupper(pathinfo($path, PATHINFO_EXTENSION)) ?: 'PDF';

        return [
            'view' => route('student.materials.open', $material->slug),
            'download' => route('student.materials.download', $material->slug),
            'label' => $label,
        ];
    }

    private function downloadFilename(Material $material, string $path): string
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION) ?: 'pdf';

        return Str::slug($material->title) . '.' . strtolower($extension);
    }

    private function isExternalPath(string $path): bool
    {
        return str_starts_with($path, 'http://') || str_starts_with($path, 'https://');
    }
}
