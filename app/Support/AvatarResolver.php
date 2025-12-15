<?php

namespace App\Support;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AvatarResolver
{
    /**
     * Resolve the first accessible image path and return as Base64 Data URI.
     * This bypasses ALL hosting URL restrictions.
     */
    public static function resolve(iterable $candidates): ?string
    {
        foreach ($candidates as $candidate) {
            if (!$candidate) {
                continue;
            }

            // Jika sudah URL lengkap, return langsung
            if (filter_var($candidate, FILTER_VALIDATE_URL)) {
                return $candidate;
            }

            // Coba cari file di berbagai lokasi
            $fullPath = self::findFile($candidate);

            if ($fullPath && File::exists($fullPath)) {
                return self::toDataUri($fullPath);
            }
        }

        return null;
    }

    /**
     * Cari file di berbagai kemungkinan lokasi
     */
    private static function findFile(string $path): ?string
    {
        $path = ltrim($path, '/\\');

        // Kemungkinan lokasi file
        $locations = [
            // Direct public path (untuk uploads/landing-images/...)
            public_path($path),

            // Storage public disk (untuk avatars/...)
            storage_path('app/public/' . $path),

            // Storage tanpa prefix
            storage_path('app/public/' . preg_replace('#^storage/#', '', $path)),

            // Public storage symlink path
            public_path('storage/' . $path),
            public_path('storage/' . preg_replace('#^storage/#', '', $path)),
        ];

        foreach ($locations as $location) {
            if (File::exists($location)) {
                return $location;
            }
        }

        return null;
    }

    /**
     * Convert file to Base64 Data URI
     */
    private static function toDataUri(string $filePath): string
    {
        $content = File::get($filePath);
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
        ];

        $mimeType = $mimeTypes[$extension] ?? 'image/jpeg';

        return 'data:' . $mimeType . ';base64,' . base64_encode($content);
    }
}

