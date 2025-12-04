<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LandingContent;

class LandingContentSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing landing contents to avoid duplicates during development
        LandingContent::truncate();



        // Articles - DISABLED (akan dikelola manual via admin panel)
        // Uncomment lines below if you want to seed articles

        // Features (Keunggulan)
        $features = [
            [
                'title' => 'Mentor Lulusan Kampus Top',
                'description' => 'Belajar langsung dengan mentor yang berpengalaman dan berasal dari kampus unggulan di Indonesia.',
            ],
            [
                'title' => 'Kelas Kecil & Interaktif',
                'description' => 'Jumlah siswa yang terbatas di setiap kelas membuat interaksi lebih intens dan terarah.',
            ],
            [
                'title' => 'Monitoring Progres Rutin',
                'description' => 'Orang tua dan siswa mendapatkan laporan rutin mengenai perkembangan belajar dan capaian target.',
            ],
            [
                'title' => 'Materi & Soal Terstruktur',
                'description' => 'Modul belajar disusun bertahap dari konsep dasar hingga soal tingkat lanjutan.',
            ],
        ];

        foreach ($features as $index => $feature) {
            LandingContent::create([
                'section' => 'feature',
                'title' => $feature['title'],
                'content' => $feature,
                'image' => null,
                'order' => $index,
                'is_active' => true,
            ]);
        }

        // Testimonials - DISABLED (akan dikelola manual via admin panel)
        // Uncomment lines below if you want to seed testimonials

        // FAQs - DISABLED (akan dikelola manual via admin panel)
        // Uncomment lines below if you want to seed FAQs
    }
}