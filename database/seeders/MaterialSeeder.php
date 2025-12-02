<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\MaterialChapter;
use App\Models\MaterialObjective;
use App\Models\Package;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        $materialsAvailable = Schema::hasTable('materials');

        if (! $materialsAvailable) {
            return;
        }

        if (! Schema::hasTable('packages')) {
            return;
        }

        $chaptersAvailable = Schema::hasTable('material_chapters');
        $objectivesAvailable = Schema::hasTable('material_objectives');

        if ($chaptersAvailable) {
            MaterialChapter::query()->delete();
        }

        if ($objectivesAvailable) {
            MaterialObjective::query()->delete();
        }

        Material::query()->delete();

        $packageLookup = Package::query()->pluck('id', 'slug');
        $subjectLookup = Subject::query()->pluck('id', 'name');

        $materials = [
            [
                'slug' => 'matematika-sd-pecahan-dasar',
                'package_slug' => 'mayclass-sd-fundamental',
                'subject' => 'Matematika',
                'title' => 'Pecahan Dasar untuk SD',
                'level' => 'SD',
                'summary' => 'Memahami konsep pecahan melalui visualisasi dan latihan interaktif untuk siswa sekolah dasar.',
                'thumbnail_url' => 'math_fraction_sd',
                'resource_path' => 'materials/matematika-sd-pecahan.pdf',
                'objectives' => [
                    'Mengenal bagian-bagian pecahan dan simbolnya.',
                    'Membandingkan pecahan berpenyebut sama maupun berbeda.',
                    'Menerapkan pecahan dalam masalah sehari-hari.',
                ],
                'chapters' => [
                    [
                        'title' => 'Memahami Penyebut dan Pembilang',
                        'description' => 'Penjelasan konsep dasar pecahan dengan ilustrasi dan contoh sederhana.',
                    ],
                    [
                        'title' => 'Membandingkan Pecahan',
                        'description' => 'Strategi membandingkan pecahan melalui garis bilangan dan model area.',
                    ],
                    [
                        'title' => 'Latihan Pecahan dalam Kehidupan Nyata',
                        'description' => 'Soal cerita yang mengaitkan pecahan dengan konteks makanan dan permainan.',
                    ],
                ],
            ],
            [
                'slug' => 'ipa-sd-proyek-sains',
                'package_slug' => 'mayclass-sd-unggul',
                'subject' => 'IPA',
                'title' => 'Proyek Sains Terapan',
                'level' => 'SD',
                'summary' => 'Eksperimen sederhana untuk memahami konsep gaya, energi, dan perubahan wujud benda tingkat lanjut.',
                'thumbnail_url' => 'science_sd_project',
                'resource_path' => 'materials/ipa-sd-proyek-sains.pdf',
                'objectives' => [
                    'Menghubungkan konsep gaya dan energi melalui eksperimen rumah.',
                    'Menganalisis perubahan wujud benda dalam kehidupan sehari-hari.',
                    'Menyusun laporan ilmiah sederhana dari hasil percobaan.',
                ],
                'chapters' => [
                    [
                        'title' => 'Eksperimen Gaya dan Gerak',
                        'description' => 'Proyek balapan mobil sederhana dan analisis gaya dorong/tarik.',
                    ],
                    [
                        'title' => 'Energi dalam Kehidupan',
                        'description' => 'Mengamati energi panas, cahaya, dan listrik dengan alat sekitar.',
                    ],
                    [
                        'title' => 'Perubahan Wujud Benda',
                        'description' => 'Simulasi mencair, membeku, dan menguap dengan panduan eksperimen.',
                    ],
                ],
            ],
            [
                'slug' => 'ipa-smp-sistem-tata-surya',
                'package_slug' => 'mayclass-smp-eksplor',
                'subject' => 'IPA',
                'title' => 'Sistem Tata Surya',
                'level' => 'SMP',
                'summary' => 'Eksplorasi anggota tata surya, karakteristik planet, dan fenomena astronomi untuk siswa SMP.',
                'thumbnail_url' => 'science_solar_system',
                'resource_path' => 'materials/ipa-smp-tata-surya.pdf',
                'objectives' => [
                    'Mengidentifikasi susunan tata surya dan objek langit penting.',
                    'Menjelaskan pergerakan planet dan dampaknya terhadap kehidupan di bumi.',
                    'Menganalisis fenomena gerhana dan fase bulan.',
                ],
                'chapters' => [
                    [
                        'title' => 'Mengenal Anggota Tata Surya',
                        'description' => 'Deskripsi matahari, planet, satelit alami, dan benda langit lainnya.',
                    ],
                    [
                        'title' => 'Pergerakan Planet',
                        'description' => 'Penjelasan orbit, rotasi, dan revolusi serta dampaknya terhadap musim.',
                    ],
                    [
                        'title' => 'Fenomena Astronomi',
                        'description' => 'Gerhana, fase bulan, dan fenomena lainnya yang dapat diamati dari bumi.',
                    ],
                ],
            ],
            [
                'slug' => 'bahasa-inggris-sma-analytical-exposition',
                'package_slug' => 'mayclass-sma-premium',
                'subject' => 'Bahasa Inggris',
                'title' => 'Analytical Exposition Text',
                'level' => 'SMA',
                'summary' => 'Panduan menulis teks analytical exposition lengkap dengan contoh dan latihan untuk siswa SMA.',
                'thumbnail_url' => 'english_analytical_exposition',
                'resource_path' => 'materials/bahasa-inggris-sma-analytical-exposition.pdf',
                'objectives' => [
                    'Memahami struktur dan tujuan teks analytical exposition.',
                    'Mengidentifikasi ciri kebahasaan yang tepat.',
                    'Menulis teks analytical exposition dengan argumen yang kuat.',
                ],
                'chapters' => [
                    [
                        'title' => 'Struktur Teks',
                        'description' => 'Pembahasan orientation, thesis, argument, dan reiteration.',
                    ],
                    [
                        'title' => 'Ciri Kebahasaan',
                        'description' => 'Penggunaan simple present tense, modal verbs, dan konjungsi kausal.',
                    ],
                    [
                        'title' => 'Workshop Penulisan',
                        'description' => 'Langkah-langkah menyusun kerangka dan menulis teks analytical exposition.',
                    ],
                ],
            ],
        ];

        foreach ($materials as $materialData) {
            $packageId = $packageLookup[$materialData['package_slug']] ?? null;

            if (! $packageId) {
                continue;
            }

            $material = Material::create([
                'slug' => $materialData['slug'],
                'package_id' => $packageId,
                'subject_id' => $subjectLookup[$materialData['subject']] ?? null,
                'title' => $materialData['title'],
                'level' => $materialData['level'],
                'summary' => $materialData['summary'],
                'thumbnail_url' => $materialData['thumbnail_url'],
                'resource_path' => $materialData['resource_path'],
            ]);

            if ($objectivesAvailable) {
                foreach (array_values($materialData['objectives']) as $index => $objective) {
                    MaterialObjective::create([
                        'material_id' => $material->id,
                        'description' => $objective,
                        'position' => $index + 1,
                    ]);
                }
            }

            if ($chaptersAvailable) {
                foreach (array_values($materialData['chapters']) as $index => $chapter) {
                    MaterialChapter::create([
                        'material_id' => $material->id,
                        'title' => $chapter['title'],
                        'description' => $chapter['description'],
                        'position' => $index + 1,
                    ]);
                }
            }
        }
    }
}
