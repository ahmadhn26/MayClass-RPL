<?php

namespace App\Support;

use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Throwable;

class StudentAccess
{
    /**
     * Cache resolved enrollments for the current request to avoid repeated queries.
     *
     * @var array<int|string, Enrollment|null>
     */
    private static array $cache = [];

    public static function activeEnrollment(?User $user): ?Enrollment
    {
        if (! $user || $user->role !== 'student') {
            return null;
        }

        $cacheKey = $user->getAuthIdentifier();

        if (array_key_exists($cacheKey, self::$cache)) {
            return self::$cache[$cacheKey];
        }

        try {
            if (! Schema::hasTable('enrollments') || ! Schema::hasColumn('enrollments', 'is_active')) {
                return self::$cache[$cacheKey] = null;
            }
        } catch (Throwable $exception) {
            return self::$cache[$cacheKey] = null;
        }

        try {
            $query = $user->enrollments()
                ->with('package')
                ->where('is_active', true)
                ->orderByDesc('ends_at');

            if (Schema::hasColumn('enrollments', 'ends_at')) {
                $query->where(function ($subQuery) {
                    $subQuery
                        ->whereNull('ends_at')
                        ->orWhere('ends_at', '>=', now());
                });
            }

            return self::$cache[$cacheKey] = $query->first();
        } catch (Throwable $exception) {
            return self::$cache[$cacheKey] = null;
        }
    }

    public static function hasActivePackage(?User $user): bool
    {
        $enrollment = self::activeEnrollment($user);

        return $enrollment && $enrollment->package !== null;
    }
}
