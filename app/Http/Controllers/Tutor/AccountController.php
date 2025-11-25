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
            'tutor'        => $tutor,
            'tutorProfile' => $tutorProfile,
            'avatarUrl'    => $avatarUrl,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $tutor = Auth::user();

        $data = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'email', 'max:255', Rule::unique('users')->ignore($tutor?->id)],
            'phone'             => ['nullable', 'string', 'max:40'],
            'specializations'   => ['required', 'string', 'max:255'],
            'experience_years'  => ['required', 'integer', 'min:0', 'max:60'],
            'education'         => ['nullable', 'string', 'max:255'],
            'avatar'            => ['nullable', 'image', 'max:5000'],
        ]);

        if ($tutor) {
            // default: pakai avatar lama
            $avatarPath = $tutor->avatar_path ?? optional($tutor->tutorProfile)->avatar_path;

            // kalau ada upload baru
            if ($request->hasFile('avatar')) {
                try {
                    $avatarPath = AvatarUploader::store(
                        $request->file('avatar'),
                        [
                            $tutor->avatar_path,
                            optional($tutor->tutorProfile)->avatar_path,
                        ]
                    );
                } catch (\Throwable $exception) {
                    report($exception);

                    return back()
                        ->withInput($request->except('avatar'))
                        ->withErrors(['avatar' => __('Gagal mengunggah foto profil. Silakan coba lagi.')]);
                }
            }

            // update data user
            $tutor->update([
                'name'        => $data['name'],
                'email'       => $data['email'],
                'phone'       => $data['phone'] ?? null,
                'avatar_path' => $avatarPath,
            ]);

            // siapkan profil tutor
            $profile = $tutor->tutorProfile;
            $slug = optional($profile)->slug ?? (Str::slug($tutor->name) ?: 'tutor-' . $tutor->id);

            // update / buat profil tutor
            $tutor->tutorProfile()->updateOrCreate(
                ['user_id' => $tutor->id],
                [
                    'slug'             => $slug,
                    'specializations'  => $data['specializations'],
                    'experience_years' => $data['experience_years'],
                    'education'        => $data['education'] ?? null,
                    'avatar_path'      => $avatarPath,
                ]
            );
        }

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
