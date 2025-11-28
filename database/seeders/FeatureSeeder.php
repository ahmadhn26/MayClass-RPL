<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LandingContent;

class FeatureSeeder extends Seeder
{
    /**
     * Seed Keunggulan/Features section
     */
    public function run(): void
    {
        // Clear existing features
        LandingContent::where('section', 'feature')->delete();

        $features = [
            [
                'title' => 'Mentor Lulusan Kampus Top',
                'description' => 'Belajar langsung dengan mentor yang berpengalaman dan berasal dari kampus unggulan di Indonesia seperti UI, ITB, UGM, dan lainnya.',
                'icon' => '🎓',
            ],
            [
                'title' => 'Kelas Kecil & Interaktif',
                'description' => 'Jumlah siswa yang terbatas di setiap kelas membuat interaksi lebih intens dan terarah, memudahkan pemahaman materi.',
                'icon' => '👥',
            ],
            [
                'title' => 'Monitoring Progres Rutin',
                'description' => 'Orang tua dan siswa mendapatkan laporan rutin mengenai perkembangan belajar dan capaian target secara transparan.',
                'icon' => '📊',
            ],
            [
                'title' => 'Materi & Soal Terstruktur',
                'description' => 'Modul belajar disusun bertahap dari konsep dasar hingga soal tingkat lanjutan dengan kurikulum yang teruji.',
                'icon' => '📚',
            ],
            [
                'title' => 'Flexible Learning Method',
                'description' => 'Tersedia kelas online dan offline dengan jadwal yang fleksibel sesuai kebutuhan siswa dan orang tua.',
                'icon' => '⏰',
            ],
            [
                'title' => 'Try Out & Evaluasi Berkala',
                'description' => 'Simulasi ujian rutin dan evaluasi mendalam untuk mengukur kesiapan siswa menghadapi ujian sebenarnya.',
                'icon' => '✍️',
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

        $this->command->info('✅ Features seeded successfully!');
    }
}
