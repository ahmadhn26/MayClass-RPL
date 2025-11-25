<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Support\AvatarUploader;
use App\Support\StudentAccess;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as PasswordRule;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = Auth::user();

        $avatarUrl = null;

        if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
            $avatarUrl = Storage::disk('public')->url($user->avatar_path);
        }

        return view('student.profile', [
            'profile' => [
                'name' => $user->name,
                'email' => $user->email,
                'studentId' => $user->student_id,
                'phone' => $user->phone,
                'gender' => $user->gender,
                'genderLabel' => $this->translateGender($user->gender),
                'parentName' => $user->parent_name,
                'address' => $user->address,
            ],
            'genderOptions' => $this->genderOptions(),
            'avatarUrl' => $avatarUrl,
            'hasActivePackage' => StudentAccess::hasActivePackage($user),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', Rule::in(array_keys($this->genderOptions()))],
            'parent_name' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'avatar' => ['nullable', 'image', 'max:5000'],
        ]);

        $avatarPath = $user->avatar_path;

        if ($request->hasFile('avatar')) {
            try {
                $avatarPath = AvatarUploader::store($request->file('avatar'), [$user->avatar_path]);
            } catch (\Throwable $exception) {
                report($exception);

                return back()
                    ->withInput($request->except('avatar'))
                    ->withErrors(['avatar' => 'Gagal mengunggah foto profil. Silakan coba lagi.']);
            }
        }

        $user->forceFill([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'gender' => $data['gender'] ?? null,
            'parent_name' => $data['parent_name'] ?? null,
            'address' => $data['address'] ?? null,
            'avatar_path' => $avatarPath,
        ])->save();

        return redirect()
            ->route('student.profile')
            ->with('status', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('passwordUpdate', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', PasswordRule::min(8), 'confirmed'],
        ], [
            'current_password.current_password' => 'Password lama tidak sesuai.',
        ]);

        $request->user()->forceFill([
            'password' => $validated['password'],
        ])->save();

        return redirect()
            ->route('student.profile')
            ->with('password_status', 'Kata sandi berhasil diperbarui.');
    }

    private function translateGender(?string $gender): ?string
    {
        return match ($gender) {
            'male' => 'Laki-laki',
            'female' => 'Perempuan',
            default => null,
        };
    }

    private function genderOptions(): array
    {
        return [
            'male' => 'Laki-laki',
            'female' => 'Perempuan',
        ];
    }
}
