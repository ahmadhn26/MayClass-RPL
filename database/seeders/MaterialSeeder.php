<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\MaterialChapter;
use App\Models\MaterialItem;
use App\Models\MaterialObjective;
use App\Models\Package;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        $materialsAvailable = Schema::hasTable('materials');

        if (!$materialsAvailable) {
           $this->command->warn('⚠️  Materials table does not exist.');
           return;
        }

        if (!Schema::hasTable('packages')) {
            $this->command->warn('⚠️  Packages table does not exist.');
            return;
        }

        $chaptersAvailable = Schema::hasTable('material_chapters');
        $objectivesAvailable = Schema::hasTable('material_objectives');
        $itemsAvailable = Schema::hasTable('material_items');

        $this->command->info('🗑️  Clearing existing materials...');

        if ($itemsAvailable) {
            MaterialItem::query()->delete();
        }

        if ($chaptersAvailable) {
            MaterialChapter::query()->delete();
        }

        if ($objectivesAvailable) {
            MaterialObjective::query()->delete();
        }

        Material::query()->delete();

        $packageLookup = Package::query()->pluck('id', 'slug');
        $subjectLookup = Subject::query()->pluck('id', 'name');

        $this->command->info('📚 Creating material folders with items...');

        // Google Drive link provided by user
        $googleDriveLink = 'https://drive.google.com/drive/folders/1-1EAROM-rNnxqriYAOIXiVDPkAEudV_A?usp=share_link';

        $materials = [
            [
                'slug' => 'matematika-sd-pecahan-dasar',
                'package_slug' => 'sd-basic-level-classA',
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
                'items' => [
                    [
                        'name' => 'Modul Lengkap Matematika SD',
                        'description' => 'Kumpulan materi matematika SD dari berbagai topik',
                        'link' => $googleDriveLink,
                    ],
                    [
                        'name' => 'Latihan Soal Pecahan',
                        'description' => 'Bank soal latihan pecahan dengan pembahasan',
                        'link' => $googleDriveLink,
                    ],
                ],
            ],
            [
                'slug' => 'ipa-sd-proyek-sains',
                'package_slug' => 'sd-basic-level-classB',
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
                'items' => [
                    [
                        'name' => 'E-Book IPA SD',
                        'description' => 'Buku elektronik IPA lengkap untuk SD',
                        'link' => $googleDriveLink,
                    ],
                    [
                        'name' => 'Video Pembelajaran IPA',
                        'description' => 'Koleksi video pembelajaran IPA interaktif',
                        'link' => $googleDriveLink,
                    ],
                ],
            ],
            [
                'slug' => 'ipa-smp-sistem-tata-surya',
                'package_slug' => 'smp-intermediate-classA',
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
                'items' => [
                    [
                        'name' => 'Materi Tata Surya Lengkap',
                        'description' => 'Dokumen lengkap tentang sistem tata surya',
                        'link' => $googleDriveLink,
                    ],
                ],
            ],
            [
                'slug' => 'bahasa-inggris-sma-analytical-exposition',
                'package_slug' => 'sma-advanced-classA',
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
                'items' => [
                    [
                        'name' => 'English Learning Pack',
                        'description' => 'Paket lengkap belajar bahasa Inggris',
                        'link' => $googleDriveLink,
                    ],
                    [
                        'name' => 'Sample Essays',
                        'description' => 'Contoh-contoh essay analytical exposition',
                        'link' => $googleDriveLink,
                    ],
                ],
            ],
        ];

        $createdCount = 0;
        $totalItems = 0;

        foreach ($materials as $materialData) {
            $packageId = $packageLookup[$materialData['package_slug']] ?? $packageLookup->first();

            if (!$packageId) {
                continue;
            }

            $material = Material::create([
                'slug' => $materialData['slug'],
                'package_id' => $packageId,
                'subject_id' => $subjectLookup[$materialData['subject']] ?? $subjectLookup->first(),
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

            // Create material items with Google Drive links
            if ($itemsAvailable && isset($materialData['items'])) {
                foreach (array_values($materialData['items']) as $index => $item) {
                    MaterialItem::create([
                        'material_id' => $material->id,
                        'name' => $item['name'],
                        'description' => $item['description'],
                        'link' => $item['link'],
                        'position' => $index + 1,
                    ]);
                    $totalItems++;
                }
            }

            // Attach material to package (many-to-many relationship)
            $material->packages()->attach($packageId);

            $createdCount++;
        }

        $this->command->info('✅ Created ' . $createdCount . ' material folders');
        $this->command->info('📦 Total objectives: ' . MaterialObjective::count());
        $this->command->info('📖 Total chapters: ' . MaterialChapter::count());
        $this->command->info('🔗 Total material items (with links): ' . $totalItems);
        $this->command->info('🎉 Material seeding completed!');
    }
}
