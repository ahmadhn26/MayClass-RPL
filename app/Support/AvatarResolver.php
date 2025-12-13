<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AvatarResolver
{
    /**
     * Resolve the first accessible avatar URL from the given candidates.
     */
    public static function resolve(iterable $candidates): ?string
    {
        $disk = Storage::disk('public');

        // 1. Cek path yang disimpan di disk public (storage/app/public/...)
        foreach ($candidates as $candidate) {
            $normalized = self::normalize($candidate);

            if (! $normalized) {
                continue;
            }

            if ($disk->exists($normalized)) {
                // KUNCI: pakai asset('storage/...'), bukan $disk->url()
                return asset('storage/' . $normalized);
            }
        }

        // 2. Fallback: kalau ternyata kandidat sudah berupa URL penuh / storage/...
        foreach ($candidates as $candidate) {
            if (! $candidate) {
                continue;
            }

            // sudah URL lengkap (https://...)
            if (filter_var($candidate, FILTER_VALIDATE_URL)) {
                return $candidate;
            }

            // sudah dalam bentuk "storage/avatars/xxx.jpg"
            if (Str::startsWith($candidate, 'storage/')) {
                return asset($candidate);
            }
        }

        return null;
    }

    private static function normalize(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $trimmed = ltrim($path);

        if ($trimmed === '') {
            return null;
        }

        // buang prefix storage/ atau public/ kalau ada
        return preg_replace('#^(?:storage/|public/)+#', '', $trimmed) ?: $trimmed;
    }
}
