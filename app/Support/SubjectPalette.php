<?php

namespace App\Support;

class SubjectPalette
{
    private const DEFAULT_ACCENT = '#37b6ad';

    private const MAP = [
        'Matematika' => '#37b6ad',
        'Kimia' => '#5f6af8',
        'Fisika' => '#7a5cf6',
        'Biologi' => '#36a47c',
        'Bahasa Inggris' => '#f1a82e',
        'Bahasa Indonesia' => '#f27d42',
        'SD Terpadu' => '#8e65d4',
        'SMP Terpadu' => '#3d97b8',
        'SMA IPA' => '#2f8cb0',
        'SMA IPS' => '#c05bd8',
        'TPS' => '#ffb200',
        'TKP' => '#ff7e67',
        'TIU' => '#6c63ff',
        'TWK' => '#2c9c8d',
    ];

    public static function accent(string $subject): string
    {
        return self::MAP[$subject] ?? self::DEFAULT_ACCENT;
    }
}
