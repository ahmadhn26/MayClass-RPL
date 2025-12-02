<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Quiz;
use App\Models\QuizItem;
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

        if (!$quizzesAvailable) {
            $this->command->warn('⚠️  Quizzes table does not exist.');
            return;
        }

        if (!Schema::hasTable('packages')) {
            $this->command->warn('⚠️  Packages table does not exist.');
            return;
        }

        $levelsAvailable = Schema::hasTable('quiz_levels');
        $takeawaysAvailable = Schema::hasTable('quiz_takeaways');
        $itemsAvailable = Schema::hasTable('quiz_items');

        $this->command->info('🗑️  Clearing existing quizzes...');

        if ($itemsAvailable) {  
            QuizItem::query()->delete();
        }

        if ($levelsAvailable) {
            QuizLevel::query()->delete();
        }

        if ($takeawaysAvailable) {
            QuizTakeaway::query()->delete();
        }

        Quiz::query()->delete();

        $packageLookup = Package::query()->pluck('id', 'slug');
        $subjectLookup = Subject::query()->pluck('id', 'name');

        $this->command->info('📝 Creating quizzes with items...');

        // Quiz link provided by user
        $quizLink = 'https://wayground.com/join';

        $quizzes = [
            [
                'slug' => 'kuis-matematika-smp-bar isan-dan-deret',
                'package_slug' => 'smp-intermediate-classA',
                'subject' => 'Matematika',
                'class_level' => 'SMP',
                'title' => 'Barisan dan Deret',
                'summary' => 'Uji pemahaman konsep barisan aritmetika dan geometri dengan soal bertingkat.',
                'link_url' => $quizLink,
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
                'items' => [
                    [
                        'name' => 'Quiz Barisan Aritmetika',
                        'description' => 'Kuis interaktif tentang barisan aritmetika',
                        'link' => $quizLink,
                    ],
                    [
                        'name' => 'Quiz Deret Geometri',
                        'description' => 'Latihan soal deret geometri',
                        'link' => $quizLink,
                    ],
                ],
            ],
            [
                'slug' => 'kuis-ipa-sd-energi-dan-perubahannya',
                'package_slug' => 'sd-basic-level-classA',
                'subject' => 'IPA',
                'class_level' => 'SD',
                'title' => 'Energi dan Perubahannya',
                'summary' => 'Latihan pilihan ganda tentang sumber energi dan cara perubahannya dalam kehidupan sehari-hari.',
                'link_url' => $quizLink,
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
                'items' => [
                    [
                        'name' => 'Quiz Sumber Energi',
                        'description' => 'Kuis tentang berbagai sumber energi',
                        'link' => $quizLink,
                    ],
                ],
            ],
            [
                'slug' => 'kuis-matematika-sd-pecahan',
                'package_slug' => 'sd-basic-level-classB',
                'subject' => 'Matematika',
                'class_level' => 'SD',
                'title' => 'Penerapan Pecahan',
                'summary' => 'Latihan soal cerita dan pecahan campuran untuk kelas atas SD.',
                'link_url' => $quizLink,
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
                'items' => [
                    [
                        'name' => 'Quiz Operasi Pecahan',
                        'description' => 'Latihan penjumlahan dan pengurangan pecahan',
                        'link' => $quizLink,
                    ],
                    [
                        'name' => 'Quiz Soal Cerita Pecahan',
                        'description' => 'Aplikasi pecahan dalam soal cerita',
                        'link' => $quizLink,
                    ],
                ],
            ],
            [
                'slug' => 'kuis-bahasa-inggris-sma-recount-text',
                'package_slug' => 'sma-advanced-classA',
                'subject' => 'Bahasa Inggris',
                'class_level' => 'SMA',
                'title' => 'Recount Text Mastery',
                'summary' => 'Evaluasi pemahaman struktur recount text dan penggunaan simple past tense.',
                'link_url' => $quizLink,
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
                'items' => [
                    [
                        'name' => 'Recount Text Structure Quiz',
                        'description' => 'Kuis tentang struktur recount text',
                        'link' => $quizLink,
                    ],
                ],
            ],
            [
                'slug' => 'kuis-fisika-sma-mekanika',
                'package_slug' => 'sma-advanced-classC',
                'subject' => 'Fisika',
                'class_level' => 'SMA',
                'title' => 'Mekanika Klasik',
                'summary' => 'Kuis komprehensif tentang hukum Newton dan dinamika',
                'link_url' => $quizLink,
                'thumbnail_url' => 'quiz_physics_mechanics',
                'duration_label' => '35 menit',
                'question_count' => 25,
                'levels' => [
                    'Hukum Newton',
                    'Dinamika dan Energi',
                ],
                'takeaways' => [
                    'Memahami hukum Newton I, II, dan III',
                    'Menyelesaikan soal dinamika',
                    'Mengaplikasikan konsep energi dan momentum',
                ],
                'items' => [
                    [
                        'name' => 'Quiz Hukum Newton',
                        'description' => 'Soal-soal tentang hukum Newton',
                        'link' => $quizLink,
                    ],
                    [
                        'name' => 'Quiz Energi dan Momentum',
                        'description' => 'Latihan energi kinetik dan potensial',
                        'link' => $quizLink,
                    ],
                ],
            ],
        ];

        $createdCount = 0;
        $totalItems = 0;

        foreach ($quizzes as $quizData) {
            $packageId = $packageLookup[$quizData['package_slug']] ?? $packageLookup->first();

            if (!$packageId) {
                continue;
            }

            $quiz = Quiz::create([
                'slug' => $quizData['slug'],
                'package_id' => $packageId,
                'subject_id' => $subjectLookup[$quizData['subject']] ?? $subjectLookup->first(),
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

            // Create quiz items with links
            if ($itemsAvailable && isset($quizData['items'])) {
                foreach (array_values($quizData['items']) as $index => $item) {
                    QuizItem::create([
                        'quiz_id' => $quiz->id,
                        'name' => $item['name'],
                        'description' => $item['description'],
                        'link' => $item['link'],
                        'position' => $index + 1,
                    ]);
                    $totalItems++;
                }
            }

            $createdCount++;
        }

        $this->command->info('✅ Created ' . $createdCount . ' quizzes');
        $this->command->info('📊 Total levels: ' . QuizLevel::count());
        $this->command->info('💡 Total takeaways: ' . QuizTakeaway::count());
        $this->command->info('🔗 Total quiz items (with links): ' . $totalItems);
        $this->command->info('🎉 Quiz seeding completed!');
    }
}
