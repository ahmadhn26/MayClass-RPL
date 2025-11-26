<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'slug' => 'sd-basic-level-classA',
                'level' => 'SD Kelas 1–3',
                'grade_range' => '1-3',
                'tag' => 'SD',
                'card_price_label' => 'Rp350.000',
                'detail_title' => 'MayClass SD Basic Level (Class A)',
                'detail_price_label' => 'Rp350.000/bulan',
                'image_url' => 'https://via.placeholder.com/400x300?text=SD+Basic',
                'price' => 350000,
                'max_students' => 10,
                'summary' => 'Fondasi numerasi dan literasi dengan pendampingan interaktif dari tutor MayClass.',
            ],
            [
                'slug' => 'sd-basic-level-classB',
                'level' => 'SD Kelas 1–3',
                'grade_range' => '1-3',
                'tag' => 'SD',
                'card_price_label' => 'Rp350.000',
                'detail_title' => 'MayClass SD Basic Level (Class B)',
                'detail_price_label' => 'Rp350.000/bulan',
                'image_url' => 'https://via.placeholder.com/400x300?text=SD+Basic',
                'price' => 350000,
                'max_students' => 10,
                'summary' => 'Fondasi numerasi dan literasi dengan pendampingan interaktif dari tutor MayClass.',
            ],
            [
                'slug' => 'sd-basic-level-classC',
                'level' => 'SD Kelas 1–3',
                'grade_range' => '1-3',
                'tag' => 'SD',
                'card_price_label' => 'Rp350.000',
                'detail_title' => 'MayClass SD Basic Level (Class C)',
                'detail_price_label' => 'Rp350.000/bulan',
                'image_url' => 'https://via.placeholder.com/400x300?text=SD+Basic',
                'price' => 350000,
                'max_students' => 10,
                'summary' => 'Fondasi numerasi dan literasi dengan pendampingan interaktif dari tutor MayClass.',
            ],
            [
                'slug' => 'sd-skill-builder-level-classA',
                'level' => 'SD Kelas 4–6',
                'grade_range' => '4-6',
                'tag' => 'SD',
                'card_price_label' => 'Rp400.000',
                'detail_title' => 'MayClass SD Skill Builder Level (Class A)',
                'detail_price_label' => 'Rp400.000/bulan',
                'image_url' => 'https://via.placeholder.com/400x300?text=SD+Skill+Builder',
                'price' => 400000,
                'max_students' => 10,
                'summary' => 'Penguatan logika & kemampuan problem solving dalam numerasi dan literasi.',
            ],
            [
                'slug' => 'sd-skill-builder-level-classB',
                'level' => 'SD Kelas 4–6',
                'grade_range' => '4-6',
                'tag' => 'SD',
                'card_price_label' => 'Rp400.000',
                'detail_title' => 'MayClass SD Skill Builder Level (Class B)',
                'detail_price_label' => 'Rp400.000/bulan',
                'image_url' => 'https://via.placeholder.com/400x300?text=SD+Skill+Builder',
                'price' => 400000,
                'max_students' => 10,
                'summary' => 'Penguatan logika & kemampuan problem solving dalam numerasi dan literasi.',
            ],
            [
                'slug' => 'sd-skill-builder-level-classC',
                'level' => 'SD Kelas 4–6',
                'grade_range' => '4-6',
                'tag' => 'SD',
                'card_price_label' => 'Rp400.000',
                'detail_title' => 'MayClass SD Skill Builder Level (Class C)',
                'detail_price_label' => 'Rp400.000/bulan',
                'image_url' => 'https://via.placeholder.com/400x300?text=SD+Skill+Builder',
                'price' => 400000,
                'max_students' => 10,
                'summary' => 'Penguatan logika & kemampuan problem solving dalam numerasi dan literasi.',
            ],
            [
                'slug' => 'smp-concept-foundation-level-classA',
                'level' => 'SMP Kelas 7',
                'grade_range' => '7',
                'tag' => 'SMP',
                'card_price_label' => 'Rp450.000',
                'detail_title' => 'MayClass SMP/Sederajat Concept Foundation Level (Class A)',
                'detail_price_label' => 'Rp450.000/bulan',
                'image_url' => 'https://via.placeholder.com/400x300?text=SMP+Foundation',
                'price' => 450000,
                'max_students' => 10,
                'summary' => 'Membangun fondasi konsep kuat agar siap menghadapi materi menengah di kelas 8–9.',
            ],
            [
                'slug' => 'smp-concept-foundation-level-classB',
                'level' => 'SMP Kelas 7',
                'grade_range' => '7',
                'tag' => 'SMP',
                'card_price_label' => 'Rp450.000',
                'detail_title' => 'MayClass SMP/Sederajat Concept Foundation Level (Class B)',
                'detail_price_label' => 'Rp450.000/bulan',
                'image_url' => 'https://via.placeholder.com/400x300?text=SMP+Foundation',
                'price' => 450000,
                'max_students' => 10,
                'summary' => 'Membangun fondasi konsep kuat agar siap menghadapi materi menengah di kelas 8–9.',
            ],
            [
                'slug' => 'smp-concept-foundation-level-classC',
                'level' => 'SMP Kelas 7',
                'grade_range' => '7',
                'tag' => 'SMP',
                'card_price_label' => 'Rp450.000',
                'detail_title' => 'MayClass SMP/Sederajat Concept Foundation Level (Class C)',
                'detail_price_label' => 'Rp450.000/bulan',
                'image_url' => 'https://via.placeholder.com/400x300?text=SMP+Foundation',
                'price' => 450000,
                'max_students' => 10,
                'summary' => 'Membangun fondasi konsep kuat agar siap menghadapi materi menengah di kelas 8–9.',
            ],
            [
                'slug' => 'smp-intermediate-level-classA',
                'level' => 'SMP Kelas 8',
                'grade_range' => '8',
                'tag' => 'SMP',
                'card_price_label' => 'Rp450.000',
                'detail_title' => 'MayClass SMP/Sederajat Intermediate Level (Class A)',
                'detail_price_label' => 'Rp450.000/bulan',
                'image_url' => 'https://via.placeholder.com/400x300?text=SMP+Intermediate',
                'price' => 450000,
                'max_students' => 10,
                'summary' => 'Memperkuat konsep menengah dan mulai masuk pre–exam skill untuk bekal kelas 9.',
            ],
            [
                'slug' => 'smp-intermediate-level-classB',
                'level' => 'SMP Kelas 8',
                'grade_range' => '8',
                'tag' => 'SMP',
                'card_price_label' => 'Rp450.000',
                'detail_title' => 'MayClass SMP/Sederajat Intermediate Level (Class B)',
                'detail_price_label' => 'Rp450.000/bulan',
                'image_url' => 'https://via.placeholder.com/400x300?text=SMP+Intermediate',
                'price' => 450000,
                'max_students' => 10,
                'summary' => 'Memperkuat konsep menengah dan mulai masuk pre–exam skill untuk bekal kelas 9.',
            ],
            [
                'slug' => 'smp-intermediate-level-classC',
                'level' => 'SMP Kelas 8',
                'grade_range' => '8',
                'tag' => 'SMP',
                'card_price_label' => 'Rp450.000',
                'detail_title' => 'MayClass SMP/Sederajat Intermediate Level (Class C)',
                'detail_price_label' => 'Rp450.000/bulan',
                'image_url' => 'https://via.placeholder.com/400x300?text=SMP+Intermediate',
                'price' => 450000,
                'max_students' => 10,
                'summary' => 'Memperkuat konsep menengah dan mulai masuk pre–exam skill untuk bekal kelas 9.',
            ],
            [
                'slug' => 'smp-high-level-classA',
                'level' => 'SMP Kelas 9',
                'grade_range' => '9',
                'tag' => 'SMP',
                'card_price_label' => 'Rp450.000',
                'detail_title' => 'MayClass SMP/Sederajat High Level (Class A)',
                'detail_price_label' => 'Rp450.000/bulan',
                'image_url' => 'https://via.placeholder.com/400x300?text=SMP+High+Level',
                'price' => 450000,
                'max_students' => 10,
                'summary' => 'Menguasai seluruh materi SMP dan mempersiapkan transisi ke SMA.',
            ],
            [
                'slug' => 'smp-high-level-classB',
                'level' => 'SMP Kelas 9',
                'grade_range' => '9',
                'tag' => 'SMP',
                'card_price_label' => 'Rp450.000',
                'detail_title' => 'MayClass SMP/Sederajat High Level (Class B)',
                'detail_price_label' => 'Rp450.000/bulan',
                'image_url' => 'https://via.placeholder.com/400x300?text=SMP+High+Level',
                'price' => 450000,
                'max_students' => 10,
                'summary' => 'Menguasai seluruh materi SMP dan mempersiapkan transisi ke SMA.',
            ],
            [
                'slug' => 'smp-high-level-classC',
                'level' => 'SMP Kelas 9',
                'grade_range' => '9',
                'tag' => 'SMP',
                'card_price_label' => 'Rp450.000',
                'detail_title' => 'MayClass SMP/Sederajat High Level (Class C)',
                'detail_price_label' => 'Rp450.000/bulan',
                'image_url' => 'https://via.placeholder.com/400x300?text=SMP+High+Level',
                'price' => 450000,
                'max_students' => 10,
                'summary' => 'Menguasai seluruh materi SMP dan mempersiapkan transisi ke SMA.',
            ],
            [
                'slug' => 'sma-exam-mastery-level-classA',
                'level' => 'SMA Kelas 10–12',
                'grade_range' => '10-12',
                'tag' => 'SMA',
                'card_price_label' => 'Rp500.000',
                'detail_title' => 'Program SMA Class A (Exam Mastery Level 10–12)',
                'detail_price_label' => 'Rp500.000/bulan',
                'image_url' => 'https://via.placeholder.com/400x300?text=SMA+Exam+Mastery',
                'price' => 500000,
                'max_students' => 10,
                'summary' => 'Menguasai seluruh materi SMA dan mempersiapkan transisi ke perguruan tinggi.',
            ],
            [
                'slug' => 'sma-exam-mastery-level-classB',
                'level' => 'SMA Kelas 10–12',
                'grade_range' => '10-12',
                'tag' => 'SMA',
                'card_price_label' => 'Rp500.000',
                'detail_title' => 'Program SMA Class B (Exam Mastery Level 10–12)',
                'detail_price_label' => 'Rp500.000/bulan',
                'image_url' => 'https://via.placeholder.com/400x300?text=SMA+Exam+Mastery',
                'price' => 500000,
                'max_students' => 10,
                'summary' => 'Menguasai seluruh materi SMA dan mempersiapkan transisi ke perguruan tinggi.',
            ],
            [
                'slug' => 'sma-exam-mastery-level-classC',
                'level' => 'SMA Kelas 10–12',
                'grade_range' => '10-12',
                'tag' => 'SMA',
                'card_price_label' => 'Rp500.000',
                'detail_title' => 'Program SMA Class C (Exam Mastery Level 10–12)',
                'detail_price_label' => 'Rp500.000/bulan',
                'image_url' => 'https://via.placeholder.com/400x300?text=SMA+Exam+Mastery',
                'price' => 500000,
                'max_students' => 10,
                'summary' => 'Menguasai seluruh materi SMA dan mempersiapkan transisi ke perguruan tinggi.',
            ],
        ];

        // hapus record di DB yang tidak ada di $packages
        $existing = Package::all();
        foreach ($existing as $ex) {
            $found = collect($packages)->contains(function ($p) use ($ex) {
                return $p['slug'] === $ex->slug;
            });

            if (! $found) {
                $hasOrders = DB::table('orders')->where('package_id', $ex->id)->exists();
                if ($hasOrders) {
                    continue;
                }

                if ($ex->image_url && file_exists(public_path($ex->image_url))) {
                    unlink(public_path($ex->image_url));
                }
                $ex->delete();
            }
        }

        foreach ($packages as $package) {
            Package::updateOrCreate(
                ['slug' => $package['slug']],
                $package
            );
        }
    }
}