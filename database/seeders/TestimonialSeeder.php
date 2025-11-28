<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LandingContent;

class TestimonialSeeder extends Seeder
{
    /**
     * Seed Testimonials section
     */
    public function run(): void
    {
        // Clear existing testimonials
        LandingContent::where('section', 'testimonial')->delete();

        $testimonials = [
            [
                'name' => 'Aisyah Putri Maharani',
                'role' => 'Siswa SMA - Lulus STAN',
                'quote' => 'Alhamdulillah berkat bimbingan dari MayClass, saya berhasil lolos STAN tahun ini! Kakak-kakak mentornya sangat sabar menjelaskan materi TWK, TIU, dan TKP. Metode belajarnya juga asik dan tidak membosankan.',
                'rating' => 5,
                'image' => '/images/testimonials/aisyah.jpg',
            ],
            [
                'name' => 'Muhammad Rizki Ramadhan',
                'role' => 'Alumni - Mahasiswa ITB',
                'quote' => 'MayClass benar-benar membantu saya dalam mempersiapkan SBMPTN. Soal-soal latihannya mirip dengan ujian asli dan pembahasannya detail banget. Recommended buat yang mau masuk PTN!',
                'rating' => 5,
                'image' => null,
            ],
            [
                'name' => 'Siti Nurhaliza',
                'role' => 'Orang Tua Siswa',
                'quote' => 'Sebagai orang tua, saya sangat puas dengan sistem monitoring di MayClass. Setiap minggu saya dapat laporan perkembangan anak saya. Nilai matematikanya yang tadinya 60 sekarang sudah 85!',
                'rating' => 5,
                'image' => null,
            ],
            [
                'name' => 'Dimas Prakoso',
                'role' => 'Siswa SMA - Persiapan UTBK',
                'quote' => 'Try out yang diadakan MayClass sangat membantu saya mengukur kemampuan. Setelah 3 bulan bimbel di sini, skor saya meningkat signifikan dari 450 ke 620. Terima kasih MayClass!',
                'rating' => 5,
                'image' => null,
            ],
            [
                'name' => 'Zahra Amelia',
                'role' => 'Siswa SMP - Program Reguler',
                'quote' => 'Belajar di MayClass fun banget! Kakak tutornya asik-asik dan cara ngajarnya mudah dipahami. Sekarang saya jadi lebih percaya diri saat ulangan di sekolah.',
                'rating' => 5,
                'image' => null,
            ],
            [
                'name' => 'Bapak Hendro Wijaya',
                'role' => 'Orang Tua Siswa',
                'quote' => 'Sangat terbantu dengan adanya konseling berkala. Anak saya yang tadinya malas belajar sekarang jadi rajin dan termotivasi. Harga juga terjangkau dibanding bimbel lain.',
                'rating' => 5,
                'image' => null,
            ],
        ];

        foreach ($testimonials as $index => $testimonial) {
            LandingContent::create([
                'section' => 'testimonial',
                'title' => $testimonial['name'],
                'content' => $testimonial,
                'image' => $testimonial['image'],
                'order' => $index,
                'is_active' => true,
            ]);
        }

        $this->command->info('✅ Testimonials seeded successfully!');
    }
}
