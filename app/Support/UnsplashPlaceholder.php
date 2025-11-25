<?php

namespace App\Support;

class UnsplashPlaceholder
{
    private const MATERIAL_IMAGES = [
        'matematika' => 'https://images.unsplash.com/photo-1523580846011-d3a5bc25702b?auto=format&fit=crop&w=1200&q=80',
        'kimia' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1200&q=80',
        'fisika' => 'https://images.unsplash.com/photo-1470167290877-7d5d3446de4c?auto=format&fit=crop&w=1200&q=80',
        'bahasa' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1200&q=80',
        'sd' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1200&q=80',
        'default' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1200&q=80',
    ];

    private const QUIZ_IMAGES = [
        'matematika' => 'https://images.unsplash.com/photo-1517430816045-df4b7de11d1d?auto=format&fit=crop&w=1200&q=80',
        'kimia' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1200&q=80',
        'bahasa' => 'https://images.unsplash.com/photo-1523580846011-d3a5bc25702b?auto=format&fit=crop&w=1200&q=80',
        'sd' => 'https://images.unsplash.com/photo-1486723312829-27b787d6e3d1?auto=format&fit=crop&w=1200&q=80',
        'default' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=1200&q=80',
    ];

    public static function material(string $subject): string
    {
        return self::pick(self::MATERIAL_IMAGES, $subject);
    }

    public static function quiz(string $subject): string
    {
        return self::pick(self::QUIZ_IMAGES, $subject);
    }

    private static function pick(array $map, string $subject): string
    {
        $slug = strtolower($subject);

        foreach ($map as $key => $url) {
            if ($key !== 'default' && str_contains($slug, $key)) {
                return $url;
            }
        }

        return $map['default'];
    }
}
