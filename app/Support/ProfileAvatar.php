<?php

namespace App\Support;

use App\Models\User;

use function asset;

class ProfileAvatar
{
    public static function forUser(?User $user): string
    {
        $placeholder = asset('images/avatar-placeholder.svg');

        if (! $user) {
            return $placeholder;
        }

        $candidates = [];

        if ($user->role === 'tutor' && method_exists($user, 'tutorProfile')) {
            if (! $user->relationLoaded('tutorProfile')) {
                $user->loadMissing('tutorProfile');
            }

            $profile = $user->tutorProfile;

            if ($profile && $profile->avatar_path) {
                $candidates[] = $profile->avatar_path;
            }
        }

        if ($user->avatar_path) {
            $candidates[] = $user->avatar_path;
        }

        return AvatarResolver::resolve($candidates) ?? $placeholder;
    }
}
