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

        // Hero section
        LandingContent::create([
            'section' => 'hero',
            'title' => 'Hero Section',
            'content' => [
                'title' => 'Bimbel Intensif Menuju Kampus Impian',
                'subtitle' => 'Langkah Pasti Menuju Prestasi',
                'description' => 'MayClass membantu kamu belajar dengan sistem yang rapi, mentor yang suportif, dan materi yang dirancang khusus untuk mengejar kampus dan sekolah kedinasan impian.',
            ],
            'image' => null, // gunakan default di blade jika null
            'order' => 0,
            'is_active' => true,
        ]);

        // Articles
        $articles = [
            [
                'title' => 'Strategi Menyusun Jadwal Belajar Efektif untuk Siswa SMA',
                'description' => 'Tips membagi waktu antara sekolah, belajar mandiri, dan latihan soal agar tetap produktif tanpa kelelahan.',
                'link' => 'https://blog.mayclass.id/strategi-jadwal-belajar-sma',
            ],
            [
                'title' => 'Rahasia Lulus UTBK dari Mentor MayClass',
                'description' => 'Kumpulan pengalaman dan strategi dari mentor yang sudah mendampingi banyak siswa lolos PTN favorit.',
                'link' => 'https://blog.mayclass.id/rahasia-lulus-utbk',
            ],
            [
                'title' => 'Meningkatkan Fokus Belajar di Era Digital',
                'description' => 'Cara mengelola distraksi gadget dan media sosial agar sesi belajarmu lebih berkualitas.',
                'link' => 'https://www.bing.com/videos/riverview/relatedvideo?q=detik%2ccom+barca+3+0&mid=BE5BFF483176B8413553BE5BFF483176B8413553&FORM=VIRE',
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
                'image' => null, // akan di-handle oleh og:image fetch jika diedit via panel
                'order' => $index,
                'is_active' => true,
            ]);
        }

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

        // Testimonials
        $testimonials = [
            [
                'name' => 'Yohanna',
                'role' => 'Lulus UTBK Masuk PTN Favorit',
                'quote' => 'Belajar di MayClass bikin aku lebih percaya diri menghadapi ujian. Mentornya sabar banget dan selalu kasih feedback yang jelas.',
                'image' => null,
            ],
            [
                'name' => 'Xavier',
                'role' => 'Peringkat 3 Besar di Sekolah',
                'quote' => 'Sesi belajarnya terstruktur dan seru. Aku jadi punya kebiasaan belajar yang lebih konsisten.',
                'image' => null,
            ],
            [
                'name' => 'Lisa',
                'role' => 'Lolos Sekolah Kedinasan',
                'quote' => 'Tryout dan pembahasan soal dari MayClass sangat membantu memahami pola soal seleksi kedinasan.',
                'image' => null,
            ],
        ];

        foreach ($testimonials as $index => $testimonial) {
            LandingContent::create([
                'section' => 'testimonial',
                'title' => $testimonial['name'],
                'content' => [
                    'name' => $testimonial['name'],
                    'role' => $testimonial['role'],
                    'quote' => $testimonial['quote'],
                ],
                'image' => $testimonial['image'], // null -> pakai avatar placeholder/initial
                'order' => $index,
                'is_active' => true,
            ]);
        }

        // FAQs
        $faqs = [
            [
                'question' => 'Apa saja jenjang yang difasilitasi oleh MayClass?',
                'answer' => 'Saat ini MayClass menyediakan program bimbingan untuk jenjang SD, SMP, SMA, serta alumni yang sedang mempersiapkan UTBK atau sekolah kedinasan.',
            ],
            [
                'question' => 'Apakah kelas dilakukan secara online atau offline?',
                'answer' => 'Mayoritas kelas berjalan secara online interaktif. Untuk beberapa wilayah tertentu, kami juga membuka kelas offline sesuai jadwal dan ketersediaan mentor.',
            ],
            [
                'question' => 'Bagaimana cara mendaftar paket belajar di MayClass?',
                'answer' => 'Kamu bisa memilih paket di halaman landing, kemudian klik tombol “Gabung Sekarang” atau “Detail Paket” dan ikuti alur pendaftaran hingga pembayaran.',
            ],
            [
                'question' => 'Apakah orang tua bisa memantau progres belajar anak?',
                'answer' => 'Bisa. Kami menyediakan laporan berkala yang bisa diakses orang tua mengenai absensi, pencapaian materi, dan hasil evaluasi.',
            ],
        ];

        foreach ($faqs as $index => $faq) {
            LandingContent::create([
                'section' => 'faq',
                'title' => $faq['question'],
                'content' => $faq,
                'image' => null,
                'order' => $index,
                'is_active' => true,
            ]);
        }
    }
}