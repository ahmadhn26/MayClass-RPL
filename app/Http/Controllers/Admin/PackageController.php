<?php

namespace App\Http\Controllers\Admin;

use App\Models\Package;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Str;



class PackageController extends BaseAdminController
{
    public function index(): View
    {
        $packages = Schema::hasTable('packages')
            ? Package::withQuotaUsage()->with(['subjects', 'tutors'])->orderBy('level')->get()
            : collect();

        return $this->render('admin.packages.index', [
            'packages' => $packages,
            'stages' => $this->stageOptions(),
            'subjectsByLevel' => $this->getSubjectsByLevel(),
            'tutors' => User::where('role', 'tutor')->where('is_active', true)->orderBy('name')->get(),
        ]);
    }



    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePayload($request);

        $package = Package::create($data);

        // Sync subjects
        if ($request->has('subjects')) {
            $package->subjects()->sync($request->subjects);
        }

        // Sync tutors
        if ($request->has('tutors')) {
            $package->tutors()->sync($request->tutors);
        }

        // Create card features
        if ($request->has('card_features')) {
            $this->syncCardFeatures($package, $request->card_features);
        }

        return redirect()->route('admin.packages.index')->with('status', __('Paket berhasil dibuat.'));
    }

    public function edit(Package $package, Request $request): View|\Illuminate\Http\JsonResponse
    {
        $package->load(['subjects', 'cardFeatures', 'tutors']);

        // Return JSON for AJAX requests (modal)
        if ($request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'id' => $package->id,
                'level' => $package->level,
                'grade_range' => $package->grade_range,
                'tag' => $package->tag,
                'price' => $package->price,
                'max_students' => $package->max_students,
                'card_price_label' => $package->card_price_label,
                'detail_title' => $package->detail_title,
                'summary' => $package->summary,
                'card_features' => $package->cardFeatures->pluck('label')->toArray(),
                'subject_ids' => $package->subjects->pluck('id')->toArray(),
                'tutor_ids' => $package->tutors->pluck('id')->toArray(),
            ]);
        }

        // Return view for regular page access (fallback)
        return $this->render('admin.packages.edit', [
            'package' => $package,
            'stages' => $this->stageOptions(),
            'subjectsByLevel' => $this->getSubjectsByLevel(),
            'tutors' => User::where('role', 'tutor')->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Package $package): RedirectResponse
    {
        $data = $this->validatePayload($request, $package->id);

        $package->update($data);

        // Sync subjects
        if ($request->has('subjects')) {
            $package->subjects()->sync($request->subjects);
        }

        // Sync tutors
        if ($request->has('tutors')) {
            $package->tutors()->sync($request->tutors);
        }

        // Sync card features - delete old and create new
        $package->cardFeatures()->delete();
        if ($request->has('card_features')) {
            $this->syncCardFeatures($package, $request->card_features);
        }

        return redirect()->route('admin.packages.index')->with('status', __('Paket berhasil diperbarui.'));
    }

    public function destroy(Package $package): RedirectResponse
    {
        $package->delete();

        return redirect()->route('admin.packages.index')->with('status', __('Paket berhasil dihapus.'));
    }

    private function validatePayload(Request $request, ?int $packageId = null): array
    {
        $stageKeys = array_keys($this->stageOptions());

        $validated = $request->validate([
            // Slug is auto-generated
            'level' => ['required', 'string', 'max:255', Rule::in($stageKeys)],
            'grade_range' => ['required', 'string', 'max:255'],
            'tag' => ['nullable', 'string', 'max:50'],
            'card_price_label' => ['required', 'string', 'max:50'],
            'detail_title' => ['required', 'string', 'max:255'],
            'image_url' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'max_students' => ['nullable', 'integer', 'min:1'],
            'summary' => ['required', 'string'],
            'subjects' => ['required', 'array', 'min:1'],
            'subjects.*' => ['exists:subjects,id'],
            'tutors' => ['nullable', 'array'],
            'tutors.*' => ['exists:users,id'],
            'card_features' => ['nullable', 'array'],
            'card_features.*' => ['nullable', 'string', 'max:255'],
        ]);

        // Mirror card_price_label to detail_price_label
        $validated['detail_price_label'] = $validated['card_price_label'];

        // Auto-generate slug if not present (create) or if title changed (update)
        if (!isset($validated['slug'])) {
            $validated['slug'] = $this->generateUniqueSlug($validated['detail_title'], $packageId);
        }

        return $validated;
    }

    private function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 2;

        while (Package::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    /**
     * Sync card features for a package.
     * Filters empty values and creates features with proper positioning.
     */
    private function syncCardFeatures(Package $package, array $features): void
    {
        $filtered = collect($features)
            ->map(fn($value) => trim((string) $value))
            ->filter(fn($value) => !empty($value))
            ->values();

        foreach ($filtered as $index => $label) {
            $package->features()->create([
                'type' => 'card',
                'label' => $label,
                'position' => $index + 1,
            ]);
        }
    }

    private function stageOptions(): array
    {
        $definitions = config('mayclass.package_stages');

        if (!is_array($definitions) || empty($definitions)) {
            $definitions = [
                'SD' => ['label' => 'Sekolah Dasar (SD)'],
                'SMP' => ['label' => 'Sekolah Menengah Pertama (SMP)'],
                'SMA' => ['label' => 'Sekolah Menengah Atas (SMA)'],
            ];
        }

        $options = [];

        foreach ($definitions as $key => $definition) {
            if (is_array($definition)) {
                $options[$key] = $definition['label'] ?? (string) $key;
            } else {
                $options[$key] = (string) $definition;
            }
        }

        return $options;
    }

    private function getSubjectsByLevel(): array
    {
        if (!Schema::hasTable('subjects')) {
            return [
                'SD' => collect(),
                'SMP' => collect(),
                'SMA' => collect(),
            ];
        }

        $subjects = Subject::where('is_active', true)
            ->orderBy('level')
            ->orderBy('name')
            ->get()
            ->groupBy('level');

        return [
            'SD' => $subjects->get('SD', collect()),
            'SMP' => $subjects->get('SMP', collect()),
            'SMA' => $subjects->get('SMA', collect()),
        ];
    }

    public function getSubjects(Package $package): \Illuminate\Http\JsonResponse
    {
        $subjects = $package->subjects()->select('id', 'name', 'level')->get();
        return response()->json($subjects);
    }
}
