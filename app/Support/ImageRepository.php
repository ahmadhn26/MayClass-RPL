<?php

namespace App\Support;

use Illuminate\Support\Arr;

class ImageRepository
{
    public static function url(string $keyPath): string
    {
        $config = config('mayclass.images');
        $node = Arr::get($config, $keyPath);

        if ($node === null) {
            return $keyPath;
        }

        if (is_string($node)) {
            return $node;
        }

        $file = $node['file'] ?? null;
        $fallback = $node['fallback'] ?? '';

        if ($file) {
            $relativePath = ltrim($file, '/');
            $publicPath = public_path('images/' . $relativePath);

            if (is_file($publicPath)) {
                return asset('images/' . $relativePath);
            }
        }

        return $fallback;
    }
}
