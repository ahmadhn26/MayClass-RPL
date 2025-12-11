<?php

namespace App\Http\Controllers\Tutor;

use App\Support\AvatarResolver;
use App\Support\AvatarUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Str;

class AccountController extends BaseTutorController
{
    public function edit()
    {
        $tutor = Auth::user();
        $tutor?->loadMissing('tutorProfile');

        $tutorProfile = $tutor?->tutorProfile;

        // ambil kandidat path avatar dari profile dulu, baru dari user
        $avatarCandidates = [];

        if ($tutorProfile && $tutorProfile->avatar_path) {
            $avatarCandidates[] = $tutorProfile->avatar_path;
        }

        if ($tutor && $tutor->avatar_path) {
            $avatarCandidates[] = $tutor->avatar_path;
        }

        $avatarUrl = $tutor
            ? AvatarResolver::resolve($avatarCandidates)
            : null;

        return $this->render('tutor.account.index', [
            'tutor' => $tutor,
            'tutorProfile' => $tutorProfile,
            'avatarUrl' => $avatarUrl,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $tutor */
        $tutor = Auth::user();
        $tutor->load('tutorProfile'); // Pastikan relasi terload

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($tutor->id)],
            'phone' => ['nullable', 'string', 'max:40'],
            'specializations' => ['required', 'string', 'max:255'],
            'experience_years' => ['required', 'integer', 'min:0', 'max:60'],
            'education' => ['nullable', 'string', 'max:255'],
            'avatar' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:10240'],
        ]);

        // 1. Tentukan path avatar saat ini (Fallback ke profile jika di user null)
        $currentAvatarPath = $tutor->avatar_path ?? optional($tutor->tutorProfile)->avatar_path;
        $avatarPath = $currentAvatarPath;

        // 2. Jika ada upload baru, proses upload & hapus yang lama
        if ($request->hasFile('avatar')) {
            $avatarPath = AvatarUploader::store($request->file('avatar'), [
                $tutor->avatar_path,
                optional($tutor->tutorProfile)->avatar_path,
            ]);
        }

        // 3. Update User Table
        $tutor->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'avatar_path' => $avatarPath, // Simpan path di user juga
        ]);

        // 4. Update Tutor Profile Table
        $profile = $tutor->tutorProfile;
        // Slug logic: Kalau belum punya slug, bikin baru. Kalau sudah, pertahankan (atau mau regenerate?)
        // Kita pertahankan logic lama: existing slug ?? generate new
        $slug = optional($profile)->slug ?? (Str::slug($tutor->name) ?: 'tutor-' . $tutor->id);

        $tutor->tutorProfile()->updateOrCreate(
            ['user_id' => $tutor->id],
            [
                'slug' => $slug,
                'specializations' => $data['specializations'],
                'experience_years' => $data['experience_years'],
                'education' => $data['education'] ?? null,
                'avatar_path' => $avatarPath, // Simpan path di profile juga
            ]
        );

        return redirect()
            ->route('tutor.account.edit')
            ->with('status', __('Profil tutor berhasil diperbarui.'));
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('passwordUpdate', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', PasswordRule::min(8), 'confirmed'],
        ], [
            'current_password.current_password' => __('Password lama tidak sesuai.'),
        ]);

        $request->user()?->forceFill([
            'password' => $validated['password'],
        ])->save();

        return redirect()
            ->route('tutor.account.edit')
            ->with('password_status', __('Password tutor berhasil diperbarui.'));
    }
}
