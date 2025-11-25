<?php

namespace App\Support;

use App\Models\User;

class ProfileLinkResolver
{
    public static function forUser(?User $user): ?string
    {
        if (! $user) {
            return null;
        }

        return match ($user->role) {
            'student' => route('student.profile'),
            'tutor' => route('tutor.account.edit'),
            'admin' => route('admin.account.edit'),
            default => route('student.profile'),
        };
    }
}
