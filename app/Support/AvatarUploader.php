<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class AvatarUploader
{
    /**
     * Store a newly uploaded avatar and clean up previous ones.
     */
    public static function store(UploadedFile $file, array $pathsToDelete = []): string
    {
        $path = $file->store('avatars', 'public');

        if (! $path) {
            throw new RuntimeException('Gagal menyimpan foto profil.');
        }

        $disk = Storage::disk('public');

        foreach (array_unique(array_filter($pathsToDelete)) as $oldPath) {
            if ($oldPath === $path) {
                continue;
            }

            try {
                $disk->delete($oldPath);
            } catch (Throwable $exception) {
                report($exception);
            }
        }

        return $path;
    }
}
