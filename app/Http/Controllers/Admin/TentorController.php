<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subject;
use App\Models\TutorProfile;
use App\Models\User;
use App\Support\AvatarUploader;
use App\Support\ProfileAvatar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

class TentorController extends BaseAdminController
{
    public function index(Request $request): View
    {
        if (! Schema::hasTable('users')) {
            return $this->render('admin.tentors.index', [
                'tentors' => collect(),
                'stats' => ['total' => 0, 'active' => 0, 'inactive' => 0],
                'filters' => ['query' => '', 'status' => 'all'],
            ]);
        }

        $status = $request->input('status', 'all');
        $queryTerm = trim((string) $request->input('q', ''));

        $query = User::query()
            ->with(['tutorProfile', 'subjects'])
            ->where('role', 'tutor');

        if ($queryTerm !== '') {
            $query->where(function ($builder) use ($queryTerm) {
                $like = '%' . $queryTerm . '%';
                $builder
                    ->where('name', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('phone', 'like', $like)
                    ->orWhere('username', 'like', $like);
            });
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $tentors = $query
            ->orderBy('name')
            ->get()
            ->map(fn (User $tentor) => [
                'id' => $tentor->id,
                'name' => $tentor->name,
                'email' => $tentor->email,
                'username' => $tentor->username,
                'phone' => $tentor->phone,
                'avatar' => ProfileAvatar::forUser($tentor),
                'specializations' => optional($tentor->tutorProfile)->specializations,
                'education' => optional($tentor->tutorProfile)->education,
                'experience_years' => optional($tentor->tutorProfile)->experience_years ?? 0,
                'is_active' => (bool) $tentor->is_active,
                'subjects' => $tentor->subjects,
            ]);

        $stats = $this->tentorStats();

        return $this->render('admin.tentors.index', [
            'tentors' => $tentors,
            'stats' => $stats,
            'filters' => [
                'query' => $queryTerm,
                'status' => in_array($status, ['all', 'active', 'inactive'], true) ? $status : 'all',
            ],
        ]);
    }

    public function create(): View
    {
        return $this->render('admin.tentors.create', [
            'tentor' => null,
            'tentorProfile' => null,
            'avatarPreview' => asset('images/avatar-placeholder.svg'),
            'subjectsByLevel' => $this->getSubjectsByLevel(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePayload($request, true);

        $avatarPath = null;

        if ($request->hasFile('avatar')) {
            $avatarPath = AvatarUploader::store($request->file('avatar'));
        }

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'tutor',
            'phone' => $data['phone'] ?? null,
            'is_active' => $data['is_active'],
            'avatar_path' => $avatarPath,
        ]);

        $this->syncTutorProfile($user, $data, $avatarPath);

        // Sync subjects
        if ($request->has('subjects')) {
            $user->subjects()->sync($request->subjects);
        }

        return redirect()
            ->route('admin.tentors.index')
            ->with('status', __('Tentor baru berhasil ditambahkan.'));
    }

    public function edit(User $tentor): View
    {
        $this->ensureTutor($tentor);
        $tentor->loadMissing(['tutorProfile', 'subjects']);

        return $this->render('admin.tentors.edit', [
            'tentor' => $tentor,
            'tentorProfile' => $tentor->tutorProfile,
            'avatarPreview' => ProfileAvatar::forUser($tentor),
            'subjectsByLevel' => $this->getSubjectsByLevel(),
        ]);
    }

    public function update(Request $request, User $tentor): RedirectResponse
    {
        $this->ensureTutor($tentor);
        $tentor->loadMissing('tutorProfile');

        $data = $this->validatePayload($request, false, $tentor);

        $avatarPath = $tentor->avatar_path ?? optional($tentor->tutorProfile)->avatar_path;

        if ($request->hasFile('avatar')) {
            $avatarPath = AvatarUploader::store($request->file('avatar'), [
                $tentor->avatar_path,
                optional($tentor->tutorProfile)->avatar_path,
            ]);
        }

        $updatePayload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'phone' => $data['phone'] ?? null,
            'is_active' => $data['is_active'],
            'avatar_path' => $avatarPath,
        ];

        if (! empty($data['password'])) {
            $updatePayload['password'] = Hash::make($data['password']);
        }

        $tentor->update($updatePayload);

        $this->syncTutorProfile($tentor, $data, $avatarPath);

        // Sync subjects
        if ($request->has('subjects')) {
            $tentor->subjects()->sync($request->subjects);
        }

        return redirect()
            ->route('admin.tentors.edit', $tentor)
            ->with('status', __('Profil tentor berhasil diperbarui.'));
    }

    public function destroy(User $tentor): RedirectResponse
    {
        $this->ensureTutor($tentor);

        $tentor->delete();

        return redirect()
            ->route('admin.tentors.index')
            ->with('status', __('Tentor berhasil dihapus.'));
    }

    private function validatePayload(Request $request, bool $isCreate, ?User $tentor = null): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($tentor?->id)],
            'username' => ['required', 'string', 'alpha_dash', 'min:4', 'max:50', Rule::unique('users', 'username')->ignore($tentor?->id)],
            'phone' => ['nullable', 'string', 'max:40'],
            'specializations' => ['required', 'string', 'max:255'],
            'headline' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:60'],
            'education' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
            'avatar' => ['nullable', 'image', 'max:5000'],
            'subjects' => ['required', 'array', 'min:1'],
            'subjects.*' => ['exists:subjects,id'],
        ];

        $rules['password'] = $isCreate
            ? ['required', PasswordRule::min(8), 'confirmed']
            : ['nullable', PasswordRule::min(8), 'confirmed'];

        $validated = $request->validate($rules);

        $validated['is_active'] = array_key_exists('is_active', $validated)
            ? (bool) $validated['is_active']
            : true;

        $validated['experience_years'] = $validated['experience_years'] ?? 0;

        return $validated;
    }

    private function syncTutorProfile(User $tentor, array $data, ?string $avatarPath): void
    {
        $profile = $tentor->tutorProfile;
        $slug = $this->generateUniqueSlug($data['name'], optional($profile)->id);

        $tentor->tutorProfile()->updateOrCreate(
            ['user_id' => $tentor->id],
            [
                'slug' => $slug,
                'headline' => $data['headline'] ?? null,
                'bio' => $data['bio'] ?? null,
                'specializations' => $data['specializations'],
                'experience_years' => $data['experience_years'] ?? 0,
                'education' => $data['education'] ?? null,
                'avatar_path' => $avatarPath,
            ]
        );
    }

    private function generateUniqueSlug(string $name, ?int $ignoreProfileId = null): string
    {
        $base = Str::slug($name) ?: 'tentor';
        $slug = $base;
        $counter = 2;

        while ($this->slugExists($slug, $ignoreProfileId)) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }

    private function slugExists(string $slug, ?int $ignoreProfileId): bool
    {
        return TutorProfile::query()
            ->where('slug', $slug)
            ->when($ignoreProfileId, fn ($query) => $query->where('id', '!=', $ignoreProfileId))
            ->exists();
    }

    private function ensureTutor(User $user): void
    {
        abort_if($user->role !== 'tutor', 404);
    }

    private function tentorStats(): array
    {
        $base = User::query()->where('role', 'tutor');

        return [
            'total' => (clone $base)->count(),
            'active' => (clone $base)->where('is_active', true)->count(),
            'inactive' => (clone $base)->where('is_active', false)->count(),
        ];
    }

    private function getSubjectsByLevel(): array
    {
        if (! Schema::hasTable('subjects')) {
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
}
