<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LandingContent;

class FaqSeeder extends Seeder
{
    /**
     * Seed FAQ section
     */
    public function run(): void
    {
        // Clear existing FAQs
        LandingContent::where('section', 'faq')->delete();

        $faqs = [
            [
                'question' => 'Apa saja program yang tersedia di MayClass?',
                'answer' => 'MayClass menyediakan berbagai program bimbingan belajar, antara lain: Program UTBK SBMPTN, Persiapan Sekolah Kedinasan (STAN, IPDN, STIS), Bimbel SMA (Kelas 10-12), Bimbel SMP (Kelas 7-9), dan Program Intensif menjelang ujian. Setiap program dirancang dengan kurikulum terstruktur dan disesuaikan dengan kebutuhan siswa.',
                'category' => 'Program',
            ],
            [
                'question' => 'Bagaimana sistem pembelajaran di MayClass?',
                'answer' => 'Pembelajaran di MayClass menggunakan metode hybrid dengan pilihan kelas online maupun offline. Setiap kelas dibatasi maksimal 15 siswa untuk memastikan interaksi yang efektif. Siswa akan mendapatkan modul lengkap, akses platform online untuk latihan soal, dan try out berkala untuk mengukur progress belajar.',
                'category' => 'Pembelajaran',
            ],
            [
                'question' => 'Berapa biaya bimbel di MayClass?',
                'answer' => 'Biaya bimbel bervariasi tergantung program dan durasi yang dipilih. Untuk Program UTBK mulai dari Rp 2.500.000 per semester, Program Kedinasan mulai dari Rp 2.000.000 per semester, dan Program Reguler SMP/SMA mulai dari Rp 1.500.000 per semester. Kami juga menyediakan cicilan pembayaran dan diskon untuk early bird. Hubungi kami untuk informasi promo terkini!',
                'category' => 'Biaya',
            ],
            [
                'question' => 'Apakah ada garansi kelulusan?',
                'answer' => 'MayClass berkomitmen memberikan pembelajaran terbaik namun tidak memberikan garansi kelulusan 100% karena hasil bergantung pada banyak faktor termasuk ketekunan siswa. Yang kami jamin adalah: mentor berkualitas dari kampus top, materi dan soal yang sesuai dengan ujian, monitoring progress secara rutin, dan try out berkala dengan sistem penilaian yang akurat.',
                'category' => 'Garansi',
            ],
            [
                'question' => 'Bagaimana cara mendaftar di MayClass?',
                'answer' => 'Pendaftaran sangat mudah! Anda bisa: 1) Klik tombol "Daftar Sekarang" di website, 2) Pilih program yang diinginkan, 3) Isi formulir pendaftaran online, 4) Lakukan pembayaran sesuai instruksi, 5) Tim kami akan menghubungi untuk konfirmasi dan jadwal placement test (jika diperlukan). Atau bisa juga langsung datang ke kantor kami atau hubungi WhatsApp customer service untuk bantuan pendaftaran.',
                'category' => 'Pendaftaran',
            ],
            [
                'question' => 'Apakah orang tua bisa memantau perkembangan anak?',
                'answer' => 'Ya, tentu! MayClass menyediakan sistem monitoring yang transparan untuk orang tua. Setiap minggu orang tua akan mendapat laporan via WhatsApp berisi: kehadiran siswa, nilai try out, progress penguasaan materi, dan catatan dari mentor. Kami juga mengadakan parent meeting setiap 2 bulan sekali untuk konsultasi langsung dengan mentor dan koordinator akademik.',
                'category' => 'Monitoring',
            ],
            [
                'question' => 'Apakah bisa trial class dulu sebelum daftar?',
                'answer' => 'Bisa! Kami menyediakan 1x sesi trial class GRATIS untuk calon siswa. Trial class ini bertujuan agar siswa bisa merasakan metode pembelajaran kami sebelum memutuskan untuk mendaftar. Untuk mendaftar trial class, silakan hubungi customer service kami via WhatsApp atau isi formulir trial class di website. Kuota trial class terbatas setiap minggunya.',
                'category' => 'Trial',
            ],
            [
                'question' => 'Dimana lokasi bimbel MayClass?',
                'answer' => 'MayClass memiliki cabang di beberapa kota besar: Jakarta (Menteng, Kelapa Gading), Bandung (Dago, Cihampelas), Surabaya (Gubeng), dan Yogyakarta (UGM Area). Untuk siswa di luar kota, tersedia kelas online dengan kualitas yang sama dengan kelas offline. Cek website kami untuk alamat lengkap dan denah lokasi setiap cabang.',
                'category' => 'Lokasi',
            ],
            [
                'question' => 'Apa keunggulan MayClass dibanding bimbel lain?',
                'answer' => 'Keunggulan MayClass: 1) Mentor dari kampus top (UI, ITB, UGM) dengan track record terbukti, 2) Kelas kecil maksimal 15 siswa untuk pembelajaran efektif, 3) Sistem monitoring transparan dengan laporan rutin ke orang tua, 4) Materi dan bank soal yang selalu update sesuai kurikulum terbaru, 5) Try out berkala dengan analisis mendalam, 6) Flexible learning (online/offline), 7) Harga kompetitif dengan sistem cicilan.',
                'category' => 'Keunggulan',
            ],
            [
                'question' => 'Bagaimana jika tidak bisa hadir di kelas?',
                'answer' => 'Jika siswa berhalangan hadir, kami menyediakan: 1) Rekaman video pembelajaran yang bisa diakses kapan saja, 2) Modul dan materi digital yang bisa diunduh, 3) Sesi makeup class (kelas pengganti) sesuai kesepakatan dengan mentor, 4) Konsultasi personal via chat dengan mentor untuk membahas materi yang terlewat. Pastikan memberitahukan ketidakhadiran maksimal H-1 untuk memudahkan koordinasi.',
                'category' => 'Kehadiran',
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

        $this->command->info('✅ FAQs seeded successfully!');
    }
}
