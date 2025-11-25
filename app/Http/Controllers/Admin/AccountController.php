<?php

namespace App\Http\Controllers\Admin;

use App\Support\AvatarResolver;
use App\Support\AvatarUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AccountController extends BaseAdminController
{
    public function edit()
    {
        $admin = Auth::user();

        $avatarUrl = AvatarResolver::resolve([$admin?->avatar_path]);

        return $this->render('admin.account.index', [
            'account' => $admin,
            'avatarUrl' => $avatarUrl,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $admin = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($admin?->id)],
            'phone' => ['nullable', 'string', 'max:40'],
            'avatar' => ['nullable', 'image', 'max:5000'],
        ]);

        $avatarPath = $admin?->avatar_path;

        if ($request->hasFile('avatar')) {
            try {
                $avatarPath = AvatarUploader::store($request->file('avatar'), [$admin?->avatar_path]);
            } catch (\Throwable $exception) {
                report($exception);

                return back()
                    ->withInput($request->except('avatar'))
                    ->withErrors(['avatar' => 'Gagal mengunggah foto profil. Silakan coba lagi.']);
            }
        }

        if ($admin) {
            $admin->forceFill([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'avatar_path' => $avatarPath,
            ])->save();
        }

        return redirect()
            ->route('admin.account.edit')
            ->with('status', __('Profil admin berhasil diperbarui.'));
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
            ->route('admin.account.edit')
            ->with('password_status', __('Password admin berhasil diperbarui.'));
    }
}
