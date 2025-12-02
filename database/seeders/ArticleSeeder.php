<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LandingContent;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        // Articles data
        $articles = [
            [
                'title' => 'Tips Lolos UTBK SNBT 2025',
                'description' => 'Strategi jitu menghadapi soal-soal UTBK terbaru dengan manajemen waktu yang efektif dan pemahaman konsep yang mendalam.',
                'image' => 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?auto=format&fit=crop&w=800&q=80',
                'link' => '#',
            ],
            [
                'title' => 'Pentingnya Belajar Sejak Dini',
                'description' => 'Mengapa memulai persiapan ujian lebih awal dapat meningkatkan peluang kelulusan secara signifikan dibandingkan sistem kebut semalam.',
                'image' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=800&q=80',
                'link' => '#',
            ],
            [
                'title' => 'Cara Mengatur Waktu Belajar Efektif',
                'description' => 'Teknik Pomodoro dan metode blocking time untuk memaksimalkan retensi materi tanpa merasa burnout.',
                'image' => 'https://images.unsplash.com/photo-1456324504439-367cee13d656?auto=format&fit=crop&w=800&q=80',
                'link' => '#',
            ],
            [
                'title' => 'Mengenal Jurusan Kuliah Masa Depan',
                'description' => 'Tren jurusan kuliah yang memiliki prospek karir cerah di era digital dan teknologi informasi.',
                'image' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=800&q=80',
                'link' => '#',
            ],
            [
                'title' => 'Kisah Sukses Alumni MayClass',
                'description' => 'Inspirasi dari kakak tingkat yang berhasil menembus PTN impian mereka berkat ketekunan dan bimbingan yang tepat.',
                'image' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=800&q=80',
                'link' => '#',
            ],
            [
                'title' => 'Menjaga Kesehatan Mental Saat Ujian',
                'description' => 'Tips menjaga keseimbangan antara belajar dan istirahat agar tetap fokus dan tenang saat menghadapi ujian penting.',
                'image' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?auto=format&fit=crop&w=800&q=80',
                'link' => '#',
            ],
        ];

        foreach ($articles as $index => $article) {
            LandingContent::create([
                'section' => 'article',
                'title' => $article['title'],
                'content' => [
                    'title' => $article['title'],
                    'description' => $article['description'],
                    'link' => $article['link'],
                ],
                'image' => $article['image'],
                'order' => $index,
                'is_active' => true,
            ]);
        }
    }
}
