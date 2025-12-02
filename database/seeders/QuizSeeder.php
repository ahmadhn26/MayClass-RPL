<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Quiz;
use App\Models\QuizLevel;
use App\Models\QuizTakeaway;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $quizzesAvailable = Schema::hasTable('quizzes');

        if (! $quizzesAvailable) {
            return;
        }

        if (! Schema::hasTable('packages')) {
            return;
        }

        $levelsAvailable = Schema::hasTable('quiz_levels');
        $takeawaysAvailable = Schema::hasTable('quiz_takeaways');

        if ($levelsAvailable) {
            QuizLevel::query()->delete();
        }

        if ($takeawaysAvailable) {
            QuizTakeaway::query()->delete();
        }

        Quiz::query()->delete();

        $packageLookup = Package::query()->pluck('id', 'slug');
        $subjectLookup = Subject::query()->pluck('id', 'name');

        $quizzes = [
            [
                'slug' => 'kuis-matematika-smp-barisan-dan-deret',
                'package_slug' => 'mayclass-smp-eksplor',
                'subject' => 'Matematika',
                'class_level' => 'SMP',
                'title' => 'Barisan dan Deret',
                'summary' => 'Uji pemahaman konsep barisan aritmetika dan geometri dengan soal bertingkat.',
                'link_url' => 'https://mayclass.id/kuis/matematika/barisan-deret',
                'thumbnail_url' => 'quiz_math_sequence',
                'duration_label' => '30 menit',
                'question_count' => 20,
                'levels' => [
                    'Pemanasan konsep dasar',
                    'Latihan tingkat lanjut',
                ],
                'takeaways' => [
                    'Memahami pola kenaikan dan penurunan pada barisan.',
                    'Menentukan suku ke-n dan jumlah n suku pertama.',
                    'Menganalisis soal cerita yang berkaitan dengan barisan.',
                ],
            ],
            [
                'slug' => 'kuis-ipa-sd-energi-dan-perubahannya',
                'package_slug' => 'mayclass-sd-fundamental',
                'subject' => 'IPA',
                'class_level' => 'SD',
                'title' => 'Energi dan Perubahannya',
                'summary' => 'Latihan pilihan ganda tentang sumber energi dan cara perubahannya dalam kehidupan sehari-hari.',
                'link_url' => 'https://mayclass.id/kuis/ipa/energi-perubahan',
                'thumbnail_url' => 'quiz_science_energy',
                'duration_label' => '20 menit',
                'question_count' => 15,
                'levels' => [
                    'Pengenalan konsep energi',
                    'Penerapan energi dalam kehidupan',
                ],
                'takeaways' => [
                    'Mengklasifikasikan bentuk-bentuk energi.',
                    'Menjelaskan perubahan energi sederhana.',
                    'Menerapkan konsep energi dalam contoh nyata.',
                ],
            ],
            [
                'slug' => 'kuis-matematika-sd-pecahan',
                'package_slug' => 'mayclass-sd-unggul',
                'subject' => 'Matematika',
                'class_level' => 'SD',
                'title' => 'Penerapan Pecahan',
                'summary' => 'Latihan soal cerita dan pecahan campuran untuk kelas atas SD.',
                'link_url' => 'https://mayclass.id/kuis/matematika/pecahan-terapan',
                'thumbnail_url' => 'quiz_math_fraction',
                'duration_label' => '25 menit',
                'question_count' => 16,
                'levels' => [
                    'Pemanasan konsep pecahan',
                    'Soal cerita pecahan campuran',
                ],
                'takeaways' => [
                    'Mengubah pecahan campuran menjadi pecahan biasa.',
                    'Menyelesaikan soal cerita dengan langkah sistematis.',
                    'Memperkirakan hasil operasi pecahan.',
                ],
            ],
            [
                'slug' => 'kuis-bahasa-inggris-sma-recount-text',
                'package_slug' => 'mayclass-sma-premium',
                'subject' => 'Bahasa Inggris',
                'class_level' => 'SMA',
                'title' => 'Recount Text Mastery',
                'summary' => 'Evaluasi pemahaman struktur recount text dan penggunaan simple past tense.',
                'link_url' => 'https://mayclass.id/kuis/bahasa-inggris/recount-text',
                'thumbnail_url' => 'quiz_english_recount',
                'duration_label' => '25 menit',
                'question_count' => 18,
                'levels' => [
                    'Memahami struktur teks',
                    'Latihan grammar dan kosa kata',
                ],
                'takeaways' => [
                    'Mengidentifikasi orientation, events, dan re-orientation.',
                    'Menggunakan simple past tense secara tepat.',
                    'Menyusun recount text singkat dengan runtut.',
                ],
            ],
        ];

        foreach ($quizzes as $quizData) {
            $packageId = $packageLookup[$quizData['package_slug']] ?? null;

            if (! $packageId) {
                continue;
            }

            $quiz = Quiz::create([
                'slug' => $quizData['slug'],
                'package_id' => $packageId,
                'subject_id' => $subjectLookup[$quizData['subject']] ?? null,
                'class_level' => $quizData['class_level'],
                'title' => $quizData['title'],
                'summary' => $quizData['summary'],
                'link_url' => $quizData['link_url'],
                'thumbnail_url' => $quizData['thumbnail_url'],
                'duration_label' => $quizData['duration_label'],
                'question_count' => $quizData['question_count'],
            ]);

            if ($levelsAvailable) {
                foreach (array_values($quizData['levels']) as $index => $label) {
                    QuizLevel::create([
                        'quiz_id' => $quiz->id,
                        'label' => $label,
                        'position' => $index + 1,
                    ]);
                }
            }

            if ($takeawaysAvailable) {
                foreach (array_values($quizData['takeaways']) as $index => $description) {
                    QuizTakeaway::create([
                        'quiz_id' => $quiz->id,
                        'description' => $description,
                        'position' => $index + 1,
                    ]);
                }
            }
        }
    }
}
