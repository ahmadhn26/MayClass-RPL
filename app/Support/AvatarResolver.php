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

            if (!$normalized) {
                continue;
            }

            if ($disk->exists($normalized)) {
                // JURUS NUKLIR: Base64 Embed
                // Kita baca filenya, ubah jadi teks, dan kirim langsung ke browser
                try {
                    $mime = $disk->mimeType($normalized);
                    $content = base64_encode($disk->get($normalized));
                    return 'data:' . $mime . ';base64,' . $content;
                } catch (\Exception $e) {
                    // Jika gagal baca file, biarkan lanjut ke null/placeholder
                }
            }
        }

        // 2. Fallback: kalau ternyata kandidat sudah berupa URL penuh / storage/...
        foreach ($candidates as $candidate) {
            if (!$candidate) {
                continue;
            }

            // sudah URL lengkap (https://...)
            if (filter_var($candidate, FILTER_VALIDATE_URL)) {
                return $candidate;
            }

            // Fallback terakhir: biarkan apa adanya (mungkin URL eksternal atau relative path lain)
            // Tapi untuk kasus ini kita abaikan storage/ prefix karena sudah pasti gagal di hosting ini
            if (Str::startsWith($candidate, 'storage/')) {
                return null; // Force null supaya pakai placeholder daripada error
            }
        }

        return null;
    }

    private static function normalize(?string $path): ?string
    {
        if (!$path) {
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
