<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'slug' => 'sd-basic-level',
                'level' => 'SD Kelas 1–3',
                'grade_range' => '1-3',
                'tag' => 'SD',
                'card_price_label' => 'Rp350.000',
                'detail_title' => 'MayClass SD Basic Level',
                'detail_price_label' => 'Rp350.000/bulan',
                'image_url' => 'https://via.placeholder.com/400x300?text=SD+Basic',
                'price' => 350000,
                'max_students' => 10,
                'summary' => 'Fondasi numerasi dan literasi dengan pendampingan interaktif dari tutor MayClass.',
            ],
            [
                'slug' => 'sd-skill-builder-level',
                'level' => 'SD Kelas 4–6',
                'grade_range' => '4-6',
                'tag' => 'SD',
                'card_price_label' => 'Rp400.000',
                'detail_title' => 'MayClass SD Skill Builder Level',
                'detail_price_label' => 'Rp400.000/bulan',
                'image_url' => 'https://via.placeholder.com/400x300?text=SD+Skill+Builder',
                'price' => 400000,
                'max_students' => 10,
                'summary' => 'Penguatan logika & kemampuan problem solving dalam numerasi dan literasi.',
            ],
            [
                'slug' => 'smp-concept-foundation-level',
                'level' => 'SMP Kelas 7',
                'grade_range' => '7',
                'tag' => 'SMP',
                'card_price_label' => 'Rp450.000',
                'detail_title' => 'MayClass SMP/Sederajat Concept Foundation Level',
                'detail_price_label' => 'Rp450.000/bulan',
                'image_url' => 'https://via.placeholder.com/400x300?text=SMP+Foundation',
                'price' => 450000,
                'max_students' => 10,
                'summary' => 'Membangun fondasi konsep kuat agar siap menghadapi materi menengah di kelas 8–9.',
            ],
            [
                'slug' => 'smp-intermediate-level',
                'level' => 'SMP Kelas 8',
                'grade_range' => '8',
                'tag' => 'SMP',
                'card_price_label' => 'Rp450.000',
                'detail_title' => 'MayClass SMP/Sederajat Intermediate Level',
                'detail_price_label' => 'Rp450.000/bulan',
                'image_url' => 'https://via.placeholder.com/400x300?text=SMP+Intermediate',
                'price' => 450000,
                'max_students' => 10,
                'summary' => 'Memperkuat konsep menengah dan mulai masuk pre–exam skill untuk bekal kelas 9.',
            ],
            [
                'slug' => 'smp-high-level',
                'level' => 'SMP Kelas 9',
                'grade_range' => '9',
                'tag' => 'SMP',
                'card_price_label' => 'Rp450.000',
                'detail_title' => 'MayClass SMP/Sederajat High Level',
                'detail_price_label' => 'Rp450.000/bulan',
                'image_url' => 'https://via.placeholder.com/400x300?text=SMP+High+Level',
                'price' => 450000,
                'max_students' => 10,
                'summary' => 'Menguasai seluruh materi SMP dan mempersiapkan transisi ke SMA.',
            ],
            [
                'slug' => 'sma-exam-mastery-level',
                'level' => 'SMA Kelas 10–12',
                'grade_range' => '10-12',
                'tag' => 'SMA',
                'card_price_label' => 'Rp500.000',
                'detail_title' => 'Program SMA (Exam Mastery Level 10–12)',
                'detail_price_label' => 'Rp500.000/bulan',
                'image_url' => 'https://via.placeholder.com/400x300?text=SMA+Exam+Mastery',
                'price' => 500000,
                'max_students' => 10,
                'summary' => 'Menguasai seluruh materi SMA dan mempersiapkan transisi ke perguruan tinggi.',
            ],
        ];

        foreach ($packages as $package) {
            DB::table('packages')->insertOrIgnore([
                'slug' => $package['slug'],
                'level' => $package['level'],
                'grade_range' => $package['grade_range'],
                'tag' => $package['tag'],
                'card_price_label' => $package['card_price_label'],
                'detail_title' => $package['detail_title'],
                'detail_price_label' => $package['detail_price_label'],
                'image_url' => $package['image_url'],
                'price' => $package['price'],
                'max_students' => $package['max_students'],
                'summary' => $package['summary'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}