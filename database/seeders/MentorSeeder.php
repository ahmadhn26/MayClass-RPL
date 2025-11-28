<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LandingContent;

class MentorSeeder extends Seeder
{
    /**
     * Seed Mentor/Team section
     */
    public function run(): void
    {
        // Clear existing mentors
        LandingContent::where('section', 'mentor')->delete();

        $mentors = [
            [
                'name' => 'Dr. Ahmad Fauzi, M.Pd',
                'role' => 'Founder & Head Mentor',
                'specialization' => 'Matematika & Fisika',
                'education' => 'S3 Pendidikan Matematika - UI',
                'experience' => '15+ tahun mengajar SBMPTN/UTBK',
                'description' => 'Berpengalaman membimbing ratusan siswa lolos PTN favorit. Spesialisasi dalam strategi problem solving untuk soal-soal HOTS.',
                'quote' => 'Belajar bukan tentang menghafal, tapi memahami konsep dan melatih nalar.',
                'image' => '/images/mentors/ahmad.jpg',
            ],
            [
                'name' => 'Putri Maharani, S.Si',
                'role' => 'Senior Mentor - SAINTEK',
                'specialization' => 'Kimia & Biologi',
                'education' => 'S1 Kimia - ITB',
                'experience' => '8 tahun mengajar UTBK & Olimpiade',
                'description' => 'Alumni ITB dengan IPK 3.95. Ahli dalam menyederhanakan konsep kimia yang kompleks menjadi mudah dipahami.',
                'quote' => 'Kimia itu mudah kalau kamu tahu trik dan polanya.',
                'image' => null,
            ],
            [
                'name' => 'Rizki Ramadhan, S.S',
                'role' => 'Senior Mentor - SOSHUM',
                'specialization' => 'Bahasa Indonesia & Inggris',
                'education' => 'S1 Sastra Indonesia - UGM',
                'experience' => '10 tahun mengajar TPS & Bahasa',
                'description' => 'Spesialis dalam Tes Potensi Skolastik (TPS) dengan metode smart solving untuk mengoptimalkan waktu ujian.',
                'quote' => 'TPS bukan tentang pintar, tapi tentang strategi dan kecepatan.',
                'image' => null,
            ],
            [
                'name' => 'Dimas Prakoso, S.Sos',
                'role' => 'Mentor - Kedinasan',
                'specialization' => 'TWK, TIU, TKP',
                'education' => 'S1 Ilmu Politik - UI',
                'experience' => '6 tahun mengajar persiapan STAN & IPDN',
                'description' => 'Mantan peserta STAN dengan skor TWK/TIU/TKP tertinggi. Mengajarkan strategi jitu untuk passing grade kedinasan.',
                'quote' => 'Kunci lolos kedinasan: konsisten latihan dan pahami pola soal.',
                'image' => null,
            ],
            [
                'name' => 'Sarah Amelia, S.Pd',
                'role' => 'Mentor - SMP & SMA',
                'specialization' => 'Matematika & IPA',
                'education' => 'S1 Pendidikan MIPA - UNJ',
                'experience' => '7 tahun mengajar kurikulum nasional',
                'description' => 'Berpengalaman mendampingi siswa dari berbagai sekolah swasta dan negeri. Fokus pada penguatan konsep fundamental.',
                'quote' => 'Pondasi yang kuat membuat belajar jadi lebih mudah dan menyenangkan.',
                'image' => null,
            ],
            [
                'name' => 'Budi Santoso, M.Kom',
                'role' => 'Mentor - Teknologi',
                'specialization' => 'Informatika & Logika',
                'education' => 'S2 Ilmu Komputer - UGM',
                'experience' => '5 tahun mengajar logika & pemrograman',
                'description' => 'Spesialis dalam soal-soal logika matematika dan TIU. Menggunakan pendekatan komputasional dalam problem solving.',
                'quote' => 'Logika itu seperti coding: pola yang sama, hasil yang pasti.',
                'image' => null,
            ],
        ];

        foreach ($mentors as $index => $mentor) {
            LandingContent::create([
                'section' => 'mentor',
                'title' => $mentor['name'],
                'content' => $mentor,
                'image' => $mentor['image'] ?? null,
                'order' => $index,
                'is_active' => true,
            ]);
        }

        $this->command->info('✅ Mentors seeded successfully!');
    }
}
